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

namespace Sulaeman\SocialLogin\Helper;

use Hybrid_Auth;
use Hybrid_Endpoint;

use RuntimeException;

class Social extends Helper
{
    /**
     * @var array
     */
    const XML_PATHS = [
        'enabled'       => 'sulaeman_social_login/{social_type}/is_enabled',
        'client_id'     => 'sulaeman_social_login/{social_type}/client_id',
        'client_secret' => 'sulaeman_social_login/{social_type}/client_secret',
        'permissions'   => 'sulaeman_social_login/{social_type}/permissions',
        'redirect_url'  => 'sulaeman_social_login/{social_type}/redirect_url',
        'window_width'  => 'sulaeman_social_login/{social_type}/window_width',
        'window_height' => 'sulaeman_social_login/{social_type}/window_height'
    ];

    /**
     * @var string
     */
    protected $socialType = '';

    /**
     * @var array
     */
    protected $xmlPaths = [];

    /**
     * @var \Hybrid_Auth
     */
    protected $auth;

    /**
     * @param string $socialType
     * @return self
     */
    public function buildXmlPath($socialType)
    {
        $this->socialType = strtolower($socialType);

        if ( ! isset($this->xmlPaths[$this->socialType])) {
            $this->xmlPaths[$this->socialType] = [];

            foreach (self::XML_PATHS as $key => $value) {
                $this->xmlPaths[$this->socialType][$key] = str_replace('{social_type}', $this->socialType, $value);
            }
        }

        return $this;
    }

    /**
     * @param string $provider
     * @param string $socialStype
     * @param array  $config
     * @param string $returnUrl
     * @return \Hybrid_Auth
     */
    public function getAuth($provider = null, Array $additionalConfig, $returnUrl)
    {
        if (is_null($this->auth)) {
            if (is_null($provider)) {
                throw new RuntimeException('A provider is required');
            }

            $this->buildXmlPath($provider);

            $config = [
                'base_url' => $returnUrl,
                'providers' => [
                    $provider => array_merge([
                        'enabled' => true,
                        'keys'    => [
                            'id'     => $this->getClientId(), 
                            'secret' => $this->getClientSecret()
                        ],
                        'scope'   => $this->getPermissions()
                    ], $additionalConfig)
                ]
            ];

            $this->auth = new Hybrid_Auth($config);
        }

        return $this->auth;
    }

    /**
     * @return \Hybrid_Endpoint
     */
    public function getEndpoint()
    {
        return new Hybrid_Endpoint();
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function isEnabled($storeId = null)
    {
        return $this->getConfigValue($this->getXmlConfig('enabled'), $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getClientId($storeId = null)
    {
        return $this->getConfigValue($this->getXmlConfig('client_id'), $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getClientSecret($storeId = null)
    {
        return $this->getConfigValue($this->getXmlConfig('client_secret'), $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getPermissions($storeId = null)
    {
        return $this->getConfigValue($this->getXmlConfig('permissions'), $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getWindowWidth($storeId = null)
    {
        return $this->getConfigValue($this->getXmlConfig('window_width'), $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getWindowHeight($storeId = null)
    {
        return $this->getConfigValue($this->getXmlConfig('window_height'), $storeId);
    }

    /**
     * @param string $type
     * @return string
     */
    public function getAuthUrl($socialType)
    {
        $authUrl = $this->getBaseAuthUrl();

        return $authUrl . (strpos($authUrl, '?') ? '&' : '?') . "hauth.done={$socialType}";
    }

    /**
     * @return string
     */
    public function getBaseAuthUrl()
    {
        return $this->_getUrl(
            'sociallogin/login/callback', 
            ['_nosid' => true]
        );
    }

    /**
     * @param string $var
     * @return string
     * @throws \RuntimeException
     */
    private function getXmlConfig($var)
    {
        if ( ! isset($this->xmlPaths[$this->socialType][$var])) {
            throw new RuntimeException(sprintf('Build the %s XML path first', $var));
        }

        return $this->xmlPaths[$this->socialType][$var];
    }
}