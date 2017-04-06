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

class PopupEffect implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => PopupEffectValue::ZOOM_IN, 
                'label' => __('Zoom')
            ],
            [
                'value' => PopupEffectValue::NEWSPAPER, 
                'label' => __('Newspaper')
            ],
            [
                'value' => PopupEffectValue::MOVE_HORIZONTAL, 
                'label' => __('Horizontal move')
            ],
            [
                'value' => PopupEffectValue::MOVE_FROM_TOP, 
                'label' => __('Move from top')
            ],
            [
                'value' => PopupEffectValue::D3_UNFOLD, 
                'label' => __('3D unfold')
            ],
            [
                'value' => PopupEffectValue::ZOOM_OUT, 
                'label' => __('Zoom-out')
            ]
        ];
    }
}
