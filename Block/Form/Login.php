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

namespace Sulaeman\SocialLogin\Block\Form;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

use Sulaeman\SocialLogin\Helper\Social;
use Sulaeman\SocialLogin\Model\SocialLogin;

class Login extends Template
{
    /**
     * @var \Sulaeman\SocialLogin\Helper\Social
     */
    protected $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Sulaeman\SocialLogin\Helper\Social $helper
     * @param array $data
     */
    public function __construct(
      Context $context, 
      Social $helper, 
      array $data = []
    )
    {
        $this->helper = $helper;
        
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getAvailableLogins()
    {
        $availabelLogins = [];

        foreach (SocialLogin::AVAILABLE as $key => $label) {
            $this->helper->buildXmlPath($key);

            if ($this->helper->isEnabled()
             && $this->helper->getClientId()
              && $this->helper->getClientSecret()) {
                $availabelLogins[] = [
                    'identifier' => $key, 
                    'label'      => $label,
                    'url'        => $this->helper->getUrl('sociallogin/auth/login', ['provider' => $key]),
                    'window'     => [
                        'width'  => (int) $this->helper->getWindowWidth(), 
                        'height' => (int) $this->helper->getWindowHeight()
                    ]
                ];
            }
        }

        return $availabelLogins;
    }
}
