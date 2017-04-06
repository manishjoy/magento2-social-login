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

namespace Sulaeman\SocialLogin\Controller\Auth;

use Magento\Framework\App\Action\Action;

use Hybrid_User_Profile;

class Login extends Action
{
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

        $provider = $this->_objectManager->get(
            sprintf('\Sulaeman\SocialLogin\Provider\%s', ucfirst($provider))
        );

        $result = $provider->authenticate();

        if ($result instanceOf Hybrid_User_Profile) {
            $returnUrl = $provider->register($result);

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

            return $result;
        } else if (is_string($result)) {
            $this->messageManager->addErrorMessage(
                __('Email is not provided from %1, please enter your email address', $result)
            );

            return $this->_redirect(sprintf('sociallogin/email/request/provider/%s', $result));
        }

        return $result;
    }
}
