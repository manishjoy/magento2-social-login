# Magento 2 - Social Login
Implement social login for Magento 2.x. Inspired by **[mageplaza/magento-2-social-login](https://github.com/mageplaza/magento-2-social-login)** that focus everything on a popup. While this extension social login implemented on several areas following Magento default implementation.

I'm not forking or help to improve the **mageplaza/magento-2-social-login**, because too much modification required and that extension having ads for Mageplaza products, so I just create a new one.  While I'm copy some small amount of the source codes to use in this extension.

## SOCIAL LOGIN SUPPORT
- [Facebook](https://developers.facebook.com/apps)
- [Google](https://code.google.com/apis/console/)
- [Steam](http://steamcommunity.com/dev/apikey)
- [PayPal](https://developer.paypal.com/docs/integration/admin/manage-apps/)

You can help to add more social login support based on **[hybridauth/hybridauth](https://github.com/hybridauth/hybridauth)** support.

## DEPENDENCIES
- **PHP 7.0.12**, I think should also working in **PHP 5.5.9** and above.
- **[hybridauth/hybridauth](https://github.com/hybridauth/hybridauth) version 2.9.x**, not tested with HybridAuth 3 (not yet released). Install it using composer first.

## INSTALLATION
- Create "**app/code/Sulaeman/SocialLogin**" folder.
- Clone or download this repository into "**Sulaeman/SocialLogin**" folder. 
- Run "**php bin/magento setup:upgrade**".
- Run "**php bin/magento setup:di:compile**"
- Run '**php bin/magento setup:static-content:deploy --area="frontend"**'.

## CONFIGURATION
Go to **Stores -> Configuration -> Customers -> Social Login**


## UPDATES
-|- 05-04-2017 (Sulaeman)
   - Magento 2.1.5 support

## SCREENSHOTS
Description | Screenshot
------------ | -------------
Login Page | ![Login Page](../../blob/master/screenshots/login.jpg?raw=true)
Register Page | ![Register Page](../../blob/master/screenshots/register.jpg?raw=true)
Login Popup, when you click the checkout button and guest checkout is disabled | ![Login Popup](../../blob/master/screenshots/popup.jpg?raw=true)
Checkout Page | ![Checkout Page](../../blob/master/screenshots/checkout.jpg?raw=true)

## FOUND BUGS
Please open a issue (please check if similar issue exist reported here, just comment). We will consider to fix or close without fixing it.

## IMPROVING
Thank you for your help improving it.

## LICENSE
[MIT license](http://opensource.org/licenses/MIT).