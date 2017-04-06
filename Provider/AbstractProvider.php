<?php
/**
 * This file is part of the Sulaeman Social Login package.
 *
 * @author Sulaeman <me@sulaeman.com>
 * @copyright Copyright (c) 2017
 * @package Sulaeman_SocialLogin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sulaeman\SocialLogin\Provider;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Url;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;

use Hybrid_User_Profile;

use Sulaeman\SocialLogin\Helper\Cookie;
use Sulaeman\SocialLogin\Helper\Social;
use Sulaeman\SocialLogin\Api\SocialLoginRepositoryInterface;

use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;

abstract class AbstractProvider
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\Url
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    protected $customerData;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Sulaeman\SocialLogin\Helper\Cookie
     */
    protected $cookie;

    /**
     * @var \Sulaeman\SocialLogin\Helper\Social
     */
    protected $helper;

    /**
     * @var \Sulaeman\SocialLogin\Api\SocialLoginRepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $socialType;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $returnUrl;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Url $urlBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\Data\CustomerInterface $customerData
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Sulaeman\SocialLogin\Helper\Cookie $cookie
     * @param \Sulaeman\SocialLogin\Helper\Social $helper
     * @param \Sulaeman\SocialLogin\Api\SocialLoginRepositoryInterface $repository
     */
    public function __construct(
        RequestInterface $request,
        DirectoryList $directoryList,
        Url $urlBuilder,
        StoreManagerInterface $storeManager,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customerData,
        Customer $customer,
        Cookie $cookie,
        Social $helper,
        SocialLoginRepositoryInterface $repository
    )
    {
        $this->request            = $request;
        $this->directoryList      = $directoryList;
        $this->urlBuilder         = $urlBuilder;
        $this->storeManager       = $storeManager;
        $this->session            = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerData       = $customerData;
        $this->customer           = $customer;
        $this->cookie             = $cookie;
        $this->helper             = $helper;
        $this->repository         = $repository;

        $loginRedirect = $this->cookie->get('login_redirect');
        if ($loginRedirect) {
            $this->returnUrl = $loginRedirect;
        } else {
            $this->returnUrl = $this->request->get('back');
        }
    }

    /**
     * @return \Hybrid_User_Profile|mixed
     */
    public function authenticate()
    {
        $profile = $this->getProfile();
        if (!$profile->email) {
            $this->saveState($profile);

            return $this->socialType;
        }

        return $profile;
    }

    /**
     * Return user profile
     *
     * @return \Hybrid_User_Profile
     */
    public function getProfile()
    {
        $auth = $this->helper->getAuth(
            $this->socialType, 
            $this->config, 
            $this->getCallbackUrl()
        )->authenticate($this->socialType);
        
        return $auth->getUserProfile();
    }

    /**
     * @param \Hybrid_User_Profile $profile
     */
    public function saveState(Hybrid_User_Profile $profile)
    {
        $this->cookie->set('social_login', json_encode($profile), 3600);
    }

    /**
     * @param \Hybrid_User_Profile $profile
     * @return string
     */
    public function register(Hybrid_User_Profile $profile)
    {
        $customer = $this->identifyCustomer($profile);

        if ( ! is_null($customer)) {
            $this->login($customer);
        }

        return $this->getReturnUrl();
    }

    /**
     * @param \Hybrid_User_Profile $profile
     * @return string
     */
    public function identifyCustomer(Hybrid_User_Profile $profile)
    {
        $customer = $this->getCustomer($profile->identifier);

        if (is_null($customer)) {
            $name     = $profile->displayName;
            $user     = [
                'email'      => $profile->email,
                'firstname'  => $profile->firstName ?: $name,
                'lastname'   => $profile->lastName ?: $name,
                'identifier' => $profile->identifier
            ];
            $customer = $this->createCustomer($user);
        }

        return $customer;
    }

    /**
     * Return customer if exist
     *
     * @param string $identifier
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getCustomer($identifier)
    {
        try {
            $social = $this->repository->getBySocial($this->socialType, $identifier);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        try {
            $customer = $this->customerRepository->getById($social->getCustomerId());

            if ($customer) {
                $customer = $this->customer->load($customer->getId());

                if ($customer->getConfirmation()) {
                    try {
                        $customer->setConfirmation(null);
                        $customer->save();
                    } catch (Exception $e) {
                    }
                }

                return $customer;
            }
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Create customer from social data
     *
     * @param array $user
     * @return \Magento\Customer\Model\Customer|null
     */
    public function createCustomer(Array $user)
    {
        try {
            $customer = $this->customerRepository->get($user['email']);
            $customer = $this->customer->load($customer->getId());
        } catch (NoSuchEntityException $e) {
            $this->customerData->setWebsiteId($this->storeManager->getStore()->getWebsiteId());
            $this->customerData->setStoreId($this->storeManager->getStore()->getId());
            $this->customerData->setFirstname($user['firstname']);
            $this->customerData->setLastname($user['lastname']);
            $this->customerData->setEmail($user['email']);

            try {
                $customer = $this->customerRepository->save($this->customerData);
            } catch (InputException $e) {
                return null;
            }

            $customer = $this->customer->load($customer->getId());
            $customer->sendNewAccountEmail();
        }

        $socialLogin = $this->repository->create();
        $socialLogin->setSocialId($user['identifier']);
        $socialLogin->setCustomerId($customer->getId());
        $socialLogin->setType($this->socialType);

         try {
            $this->repository->save($socialLogin);
        } catch (CouldNotSaveException $e) {
            return null;
        }

        return $customer;
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     */
    public function login($customer)
    {
        $this->session->setCustomerAsLoggedIn($customer);
        $this->session->regenerateId();

        if ($this->cookie->get('mage-cache-sessid')) {
            $this->cookie->delete('mage-cache-sessid');
        }

        if ($this->cookie->get('login_redirect')) {
            $this->cookie->delete('login_redirect');
        }
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        $returnUrl = $this->returnUrl;
        if (is_null($returnUrl) || empty($returnUrl)) {
            $returnUrl = $this->storeManager->getStore()->getUrl();
        }

        return $returnUrl;
    }

    /**
     * @return string
     */
    private function getCallbackUrl()
    {
        return $this->urlBuilder->getUrl(
            'sociallogin/login/callback', 
            ['_nosid' => true]
        );
    }
}
