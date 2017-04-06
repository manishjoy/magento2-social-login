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
/*global define*/
define(
    [
        'jquery',
        'mage/storage',
        'Magento_Ui/js/model/messageList'
    ],
    function($, storage, globalMessageList) {
        'use strict';

        var callbacks = [];
        var socialLogin = function() {
            this.loadInfo = function(isGlobal, messageContainer) {
                messageContainer = messageContainer || globalMessageList;
                return storage.get(
                    'sociallogin/ajax/info',
                    null,
                    isGlobal
                ).done(function (response) {
                    if (response.errors) {
                        messageContainer.addErrorMessage(response);
                        callbacks.forEach(function(callback) {
                            callback(response);
                        });
                    } else {
                        callbacks.forEach(function(callback) {
                            callback(response);
                        });
                    }
                });
            }

            this.loadInfo.registerCallback = function(callback) {
                callbacks.push(callback);
            }

            this.popup = function(login) {
                var screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft;
                var screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop;
                var outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth;
                var outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22);
                var width = login.window.width ? login.window.width : 500;
                var height = login.window.height ? login.window.height : 270;
                var left = parseInt(screenX + ((outerWidth - width) / 2), 10);
                var top = parseInt(screenY + ((outerHeight - height) / 2.5), 10);
                var features = (
                    'width=' + width +
                    ',height=' + height +
                    ',left=' + left +
                    ',top=' + top
                );
                
                window.open(login.url + '?back=' + encodeURIComponent(window.location.href), login.label, features);
            }
        }

        return new socialLogin();
    }
);
