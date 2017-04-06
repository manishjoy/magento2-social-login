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

namespace Sulaeman\SocialLogin\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

use Sulaeman\SocialLogin\Api\Data\SocialLoginInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup, 
        ModuleContextInterface $context
    )
    {
        $installer = $setup;

        //START: install stuff
        $installer->startSetup();
        
        //START table setup
        $table = $installer->getConnection()->newTable(
            $installer->getTable('customer_social_login')
        )->addColumn(
            SocialLoginInterface::ID,
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [
                'identity' => true,
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true
            ],
            'Entity ID'
        )->addColumn(
            SocialLoginInterface::SOCIAL_ID,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            [
                'nullable' => false
            ],
            'Social Id'
        )->addColumn(
            SocialLoginInterface::CUSTOMER_ID,
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [
                'nullable' => false, 
                'unsigned' => true
            ],
            'Customer Id'
        )->addColumn(
            SocialLoginInterface::TYPE,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            [
                'default' => '',
                'nullable' => false
            ],
            'Type'
        )->addColumn(
            SocialLoginInterface::CREATED_AT,
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false, 
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
            ],
            'Creation Time'
        )->addColumn(
            SocialLoginInterface::UPDATED_AT,
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false, 
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
            ],
            'Modification Time'
        );

        $installer->getConnection()->createTable($table);
        //END table setup

        $installer->getConnection()->addIndex(
            $setup->getTable('customer_social_login'),
            $setup->getIdxName('customer_social_login', ['social_id']),
            ['social_id']
        );

        $installer->getConnection()->addIndex(
            $setup->getTable('customer_social_login'),
            $setup->getIdxName('customer_social_login', ['customer_id']),
            ['customer_id']
        );

        $installer->getConnection()->addIndex(
            $setup->getTable('customer_social_login'),
            $setup->getIdxName('customer_social_login', ['type']),
            ['type']
        );

        $installer->endSetup();
        //END: install stuff
    }
}
