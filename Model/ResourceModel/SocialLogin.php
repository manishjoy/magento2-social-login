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

namespace Sulaeman\SocialLogin\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SocialLogin extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected $_idFieldName = 'entity_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            'customer_social_login', 
            $this->_idFieldName
        );
    }
}
