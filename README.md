# Laravel Socialite Seznam Driver

This package provides Seznam.cz driver for Laravle socialite. This can be used to utilize oAuth via Laravel Socialite in Laravel projects with ease.

## Installation

You can install the package via composer:

```bash
composer require ravols/socialite-seznam-driver
```


Put values into config/services.php file:

```php
'seznam' => [
    'client_id' => env('SEZNAM_OAUTH_CLIENT_ID'),
    'client_secret' => env('SEZNAM_OAUTH_SECRET'),
    'redirect' => env('SEZNAM_OAUTH_REDIRECT'),
];
```

## Usage

Redirect to Seznam oAuth sing in page via:
```php
return Socialite::driver('seznam')->redirect();
```
After loging in on the page retrieve the user via:
```php
 $user = Socialite::driver('seznam')->stateless()->user();
```
Rest of the logic is depending on your project and business needs.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
