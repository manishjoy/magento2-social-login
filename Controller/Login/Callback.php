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

namespace Sulaeman\SocialLogin\Controller\Login;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

use Sulaeman\SocialLogin\Helper\Social;

/**
 * Class AbstractSocial
 * @package Sulaeman\SocialLogin\Controller
 */
class Callback extends Action
{
    /**
     * @var \Sulaeman\SocialLogin\Helper\Social
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Sulaeman\SocialLogin\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        Social $helper
    )
    {
        parent::__construct($context);

        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $endPoint = $this->helper->getEndpoint();
        $endPoint->process();
    }
}