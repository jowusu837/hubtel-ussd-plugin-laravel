# Hubtel USSD plugin for Laravel
[![Latest Release on GitHub][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Based on [Hubtel's USSD API](https://developers.hubtel.com/documentations/ussd)

## About

This package allows you to integrate Hubtel USSD service directly into your existing Laravel application. This allows users to access your application on USSD like <strong>*1234#</strong>.
Check out [this link](https://developers.hubtel.com/documentations/ussd#how-to-get-ussd-short-code) to know how you can acquire a USSD code from Hubtel.

## Installation

Require the `jowusu837/hubtel-ussd-plugin-laravel` package in your `composer.json` and update your dependencies:
```sh
$ composer require jowusu837/hubtel-ussd-plugin-laravel
```
If you're using Laravel 5.5, this is all there is to do.

Should you still be on older versions of Laravel, the final steps for you are to add the service provider of the package and alias the package. To do this open your `config/app.php` file.

Add the HubtelUssd\ServiceProvider to your `providers` array:
```php
Jowusu837\HubtelUssd\ServiceProvider::class,
```

## Usage

Your application should now have an endpoint for USSD access. You can use the [Hubtel USSD Simulator](http://apps.hubtel.com/USSDSimulator/) to test your it out by supplying your application url as: http://your-application.com/ussd

## Customization
Your application should have the default USSD flow just to show you the the plugin is working. 
You create your own custom USSD flows by simply creating `Activities`. 
You can start by creating a new folder in your `app` directory called `USSD`. This is where you will store all your USSD related logic.

A USSD activity is just a simple `php` class that extends the `UssdActivity` class. Here's how it looks like:

```php
namespace App\Ussd\Activities;

use App\Ussd\Activities\MenuSelection;
use Jowusu837\HubtelUssd\Lib\UssdActivity;
use Jowusu837\HubtelUssd\Lib\UssdResponse;

class HomeActivity extends UssdActivity
{
    public function run() {
        $this->response->Type = UssdResponse::RELEASE;
        $this->response->Message = 'Ussd is working!';
        return $this;
    }
    
    public function next() {
        return MenuSelection::class;
    }

}
```
So basically, an activity class such as the one above must implement 2 methods: `run()` and `next()`. The `run()` method is the main entry point for the activity and must always return `$this`. The `next()` must just return a reference to the next activity to be executed. You can do this by simply returning a string with the full namespace to the activity class or you can use the `::class` approach to have php resolve that for you.

You have access to the current request `$this->request`, the response to be sent `$this->response`, and the current USSD session `$this->session` from within the activity as well. The request and response properties exposes all the properties of a USSD request and response respectively as documented [here](https://developers.hubtel.com/documentations/ussd/reference).
The session property however is just an array. Note that the session is implemented on top of your existing Laravel cache. The session allows you to persist state throughout your USSD session. You can store a value in the session like so: `$this->session['name'] = 'John Doe'`, and retrieve it elsewhere like so: `$name = $this->session['name']`. 

Once you have created your activiy files, you need to set your entry point activity in the config file as shown in the next section.

## Configuration

The defaults are set in `config/hubtel-ussd.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:
```sh
$ php artisan vendor:publish --provider="Jowusu837\HubtelUssd\ServiceProvider"
```

    
```php
return [
    "home" => \App\Ussd\Activities\HomeActivity::class
];
```
    
## License

Released under the MIT License, see [LICENSE](LICENSE).

[ico-version]: https://img.shields.io/github/release/jowusu837/hubtel-ussd-plugin-laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jowusu837/hubtel-ussd-plugin-laravel.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jowusu837/hubtel-ussd-plugin-laravel.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jowusu837/hubtel-ussd-plugin-laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jowusu837/hubtel-ussd-plugin-laravel
[link-scrutinizer]: https://scrutinizer-ci.com/g/jowusu837/hubtel-ussd-plugin-laravel/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jowusu837/hubtel-ussd-plugin-laravel
[link-downloads]: https://packagist.org/packages/jowusu837/hubtel-ussd-plugin-laravel
[link-author]: https://github.com/jowusu837
[link-contributors]: ../../contributors
