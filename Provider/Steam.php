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

class Steam extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected $socialType = 'Steam';

    /**
     * {@inheritdoc}
     */
    public function authenticate()
    {
        $basePath = $this->directoryList->getRoot();
        $basePath .= '/vendor/hybridauth/hybridauth/additional-providers';
        
        $this->config['wrapper'] = [
            'path'  => $basePath.'/hybridauth-steam/Providers/Steam.php', 
            'class' => 'Hybrid_Providers_Steam'
        ];

        return parent::authenticate();
    }
}
