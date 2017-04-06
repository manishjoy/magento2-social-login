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

namespace Sulaeman\SocialLogin\Api;

use Sulaeman\SocialLogin\Api\Data\SocialLoginInterface;

interface SocialLoginRepositoryInterface 
{
    /**
     * @param \Sulaeman\SocialLogin\Model\SocialLoginFactory $object
     * @return \Sulaeman\SocialLogin\Api\Data\SocialLoginInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(SocialLoginInterface $object);

    /**
     * @param int $id
     * @return \Sulaeman\SocialLogin\Api\Data\SocialLoginInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param string $type
     * @param mixed $id
     * @return \Sulaeman\SocialLogin\Api\Data\SocialLoginInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBySocial($type, $id);

    /**
     * @param \Sulaeman\SocialLogin\Model\SocialLoginFactory $object
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(SocialLoginInterface $object);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($id);
}
