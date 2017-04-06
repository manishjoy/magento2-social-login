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

class Paypal extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected $socialType = 'Paypal';

    /**
     * {@inheritdoc}
     */
    public function authenticate()
    {
        $basePath = realpath(dirname(__FILE__)).'/Thirdparty';
        
        $this->config['wrapper'] = [
            'path'  => $basePath.'/Paypal.php', 
            'class' => '\Sulaeman\SocialLogin\Provider\Thirdparty\Paypal'
        ];

        $result = parent::authenticate();

        $identifier = explode('/', $result->identifier);
        $result->identifier = end($identifier);

        return $result;
    }
}
