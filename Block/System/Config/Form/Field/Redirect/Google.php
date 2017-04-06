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

namespace Sulaeman\SocialLogin\Block\System\Config\Form\Field\Redirect;

use Sulaeman\SocialLogin\Block\System\Config\Form\Field\Redirect;

/**
 * Backend system config datetime field renderer
 */
class Google extends Redirect
{
    /**
     * {@inheritdoc}
     */
    protected $socialType = 'Google';
}
