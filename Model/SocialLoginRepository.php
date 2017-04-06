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

use Sulaeman\SocialLogin\Api\SocialLoginRepositoryInterface;
use Sulaeman\SocialLogin\Api\Data\SocialLoginInterface;
use Sulaeman\SocialLogin\Model\SocialLoginFactory;
use Sulaeman\SocialLogin\Model\ResourceModel\SocialLogin\CollectionFactory;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class SocialLoginRepository implements SocialLoginRepositoryInterface
{
    /**
     * @var \Sulaeman\SocialLogin\Model\SocialLoginFactory
     */
    protected $objectFactory;

    /**
     * @var \Sulaeman\SocialLogin\Model\ResourceModel\SocialLogin\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \Sulaeman\SocialLogin\Model\SocialLoginFactory $objectFactory
     * @param \Sulaeman\SocialLogin\Model\ResourceModel\SocialLogin\CollectionFactory $collectionFactory
     */
    public function __construct(
        SocialLoginFactory $objectFactory, 
        CollectionFactory $collectionFactory
    )
    {
        $this->objectFactory     = $objectFactory;
        $this->collectionFactory = $collectionFactory;
    }
    
    /**
     * {@inheritdoc}
     */
    public function save(SocialLoginInterface $object)
    {
        try {
            $object->save();
        } catch(Exception $e) {
            throw new CouldNotSaveException($e->getMessage());
        }
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $object = $this->create();
        $object->load($id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySocial($type, $id)
    {
        $object = $this->collectionFactory->create();
        $object->addFieldToFilter('type', $type);
        $object->addFieldToFilter('social_id', $id);
        $login = $object->getFirstItem();
        if (!$login->getId()) {
            throw new NoSuchEntityException(__('Object with type "%1" social id "%2" does not exist.', $type, $id));
        }
        return $login;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->objectFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(SocialLoginInterface $object)
    {
        try {
            $object->delete();
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
