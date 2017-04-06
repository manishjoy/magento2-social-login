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

namespace Sulaeman\SocialLogin\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

use Sulaeman\SocialLogin\Api\Data\SocialLoginInterface;

class SocialLogin extends AbstractModel implements SocialLoginInterface, IdentityInterface
{
    const CACHE_TAG = 'customer_social_login';

    const AVAILABLE = [
        'facebook' => 'Facebook',
        'google'   => 'Google',
        'steam'    => 'Steam',
        'paypal'   => 'PayPal'
    ];

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Sulaeman\SocialLogin\Model\ResourceModel\SocialLogin');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
