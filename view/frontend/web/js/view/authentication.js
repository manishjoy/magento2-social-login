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
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Sulaeman_SocialLogin/js/social-login'
    ],
    function($, ko, Component, socialLogin) {
        'use strict';
        
        return Component.extend({
            displaySocialLogin: ko.observable(false),
            socialLoginButtons: ko.observable([]),

            defaults: {
                template: 'Sulaeman_SocialLogin/authentication'
            },

            /**
             * Init
             */
            initialize: function () {
                var self = this;
                this._super();

                socialLogin.loadInfo.registerCallback(function(response) {
                    self.displaySocialLogin(response.is_enabled);
                    self.socialLoginButtons(response.logins);
                });
                socialLogin.loadInfo();
            },

            openPopupSocialLogin: function(login) {
                socialLogin.popup(login);
            }
        });
    }
);
