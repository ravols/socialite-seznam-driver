# Laravel Socialite Seznam Driver
![image](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)  ![image](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

This package offers a [Seznam.cz](https://www.seznam.cz) driver for [Laravel Socialite](https://laravel.com/docs/11.x/socialite), allowing seamless integration of OAuth in Laravel projects. It simplifies the process of leveraging Laravel Socialite for user authentication via Seznam.cz, enhancing the overall project efficiency.

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
