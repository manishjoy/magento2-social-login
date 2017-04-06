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

class Facebook extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected $socialType = 'Facebook';

    /**
     * {@inheritdoc}
     */
    protected $config = [
        'display' => 'popup'
    ];

    /**
     * {@inheritdoc}
     */
    public function authenticate()
    {
        $basePath = realpath(dirname(__FILE__)).'/Thirdparty';
        
        $this->config['wrapper'] = [
            'path'  => $basePath.'/Facebook.php', 
            'class' => '\Sulaeman\SocialLogin\Provider\Thirdparty\Facebook'
        ];

        return parent::authenticate();
    }
}
