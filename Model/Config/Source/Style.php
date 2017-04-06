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

namespace Sulaeman\SocialLogin\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Style implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => StyleValue::DEFAULT, 'label' => __('Default')],
            ['value' => StyleValue::ORANGE, 'label' => __('Orange')],
            ['value' => StyleValue::GREEN, 'label' => __('Green')],
            ['value' => StyleValue::BLACK, 'label' => __('Black')],
            ['value' => StyleValue::BLUE, 'label' => __('Blue')],
            ['value' => StyleValue::DARKBLUE, 'label' => __('Dark Blue')],
            ['value' => StyleValue::PINK, 'label' => __('Pink')],
            ['value' => StyleValue::RED, 'label' => __('Red')],
            ['value' => StyleValue::VIOLET, 'label' => __('Violet')],
            ['value' => StyleValue::CUSTOM, 'label' => __('Custom')]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            StyleValue::DEFAULT  => __('Default'),
            StyleValue::ORANGE   => __('Orange'),
            StyleValue::GREEN    => __('Green'),
            StyleValue::BLACK    => __('Black'),
            StyleValue::BLUE     => __('Blue'),
            StyleValue::DARKBLUE => __('Dark Blue'),
            StyleValue::PINK     => __('Pink'),
            StyleValue::RED      => __('Red'),
            StyleValue::VIOLET   => __('Violet'),
            StyleValue::CUSTOM   => __('Custom')
        ];
    }
}
