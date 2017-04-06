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

namespace Sulaeman\SocialLogin\Controller\Email;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

use Hybrid_User_Profile;

use Sulaeman\SocialLogin\Helper\Cookie;
use Sulaeman\SocialLogin\Helper\Social;

/**
 * Class AbstractSocial
 * @package Sulaeman\SocialLogin\Controller
 */
class Request extends Action
{
    /**
     * @var \Sulaeman\SocialLogin\Helper\Cookie
     */
    protected $cookie;

    /**
     * @var \Sulaeman\SocialLogin\Helper\Social
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Sulaeman\SocialLogin\Helper\Cookie $cookie
     * @param \Sulaeman\SocialLogin\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        Cookie $cookie,
        Social $helper
    )
    {
        parent::__construct($context);

        $this->cookie = $cookie;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $provider = $this->getRequest()->get('provider');

        if ( ! $provider) {
            $content = "<script type=\"text/javascript\">
                window.close();
            </script>";

            $result = $this->resultFactory->create('raw');
            $result->setContents($content);

            return $result;
        }

        if ($this->getRequest()->getMethod() == 'POST') {
            $continue = (bool) $this->getRequest()->get('continue', 0);
            $cancel = (bool) $this->getRequest()->get('cancel', 0);

            if ($continue) {
                $social = $this->cookie->get('social_login');
                if ( ! $social) {
                    $continue = false;
                    $cancel   = true;
                }
            }

            if ($continue) {
                $email = $this->getRequest()->get('email');

                if (empty($email)) {
                    $this->messageManager->addError(__('Your email address is required.'));

                    $continue = false;
                }

                if ($continue && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->messageManager->addError(__('Your email address is not valid.'));

                    $continue = false;
                }
            }

            if ($continue) {
                $provider = $this->_objectManager->get(
                    sprintf('\Sulaeman\SocialLogin\Provider\%s', ucfirst($provider))
                );

                $social = (array) json_decode($social);
                $social['email'] = $email;

                $profile = new Hybrid_User_Profile();
                foreach ($social as $key => $val) {
                    $profile->$key = $val;
                }

                $returnUrl = $provider->register($profile);

                $content = "<script type=\"text/javascript\">
                    try{
                        window.opener.location.href=\"" . $returnUrl . "\";
                    } catch(e){
                        window.opener.location.reload(true);
                    }
                    window.close();
                </script>";

                $result = $this->resultFactory->create('raw');
                $result->setContents($content);
            }

            if ($cancel) {
                $this->cookie->delete('social_login');

                $content = "<script type=\"text/javascript\">
                    window.close();
                </script>";

                $result = $this->resultFactory->create('raw');
                $result->setContents($content);
            }
        } else {
            $result = $this->resultFactory->create('page');
        }

        return $result;
    }
}