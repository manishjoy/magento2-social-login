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
        'Magento_Ui/js/form/form',
        'Magento_Customer/js/action/login',
        'Magento_Customer/js/customer-data',
        'Magento_Customer/js/model/authentication-popup',
        'mage/translate',
        'mage/url',
        'Magento_Ui/js/modal/alert',
        'Sulaeman_SocialLogin/js/social-login',
        'mage/validation'
    ],
    function ($, ko, Component, loginAction, customerData, authenticationPopup, $t, url, alert, socialLogin) {
        'use strict';

        return Component.extend({
            registerUrl: window.authenticationPopup.customerRegisterUrl,
            forgotPasswordUrl: window.authenticationPopup.customerForgotPasswordUrl,
            autocomplete: window.checkout.autocomplete,
            modalWindow: null,
            isLoading: ko.observable(false),

            /** start: Social Login */
            displaySocialLogin: ko.observable(false),
            socialLoginButtons: ko.observable([]),
            /** end: Social Login */

            defaults: {
                template: 'Sulaeman_SocialLogin/authentication-popup'
            },

            /**
             * Init
             */
            initialize: function () {
                var self = this;
                this._super();
                url.setBaseUrl(window.authenticationPopup.baseUrl);
                loginAction.registerLoginCallback(function () {
                    self.isLoading(false);
                });

                /** start: Social Login */
                socialLogin.loadInfo.registerCallback(function(response) {
                    self.displaySocialLogin(response.is_enabled);
                    self.socialLoginButtons(response.logins);
                    self.isLoading(false);
                });
                socialLogin.loadInfo();
                /** end: Social Login */
            },

            /** Init popup login window */
            setModalElement: function (element) {
                if (authenticationPopup.modalWindow == null) {
                    authenticationPopup.createPopUp(element);
                }
            },

            /** Is login form enabled for current customer */
            isActive: function () {
                var customer = customerData.get('customer');

                return customer() == false;
            },

            /** Show login popup window */
            showModal: function () {
                if (this.modalWindow) {
                    $(this.modalWindow).modal('openModal');
                } else {
                    alert({
                        content: $t('Guest checkout is disabled.')
                    });
                }
            },

            /** Provide login action */
            login: function (loginForm) {
                var loginData = {},
                    formDataArray = $(loginForm).serializeArray();
                formDataArray.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });

                if ($(loginForm).validation() &&
                    $(loginForm).validation('isValid')
                ) {
                    this.isLoading(true);
                    loginAction(loginData, null, false);
                }
            },

            /** start: Social Login */
            openPopupSocialLogin: function(login) {
                socialLogin.popup(login);
            }
            /** end: Social Login */
        });
    }
);
