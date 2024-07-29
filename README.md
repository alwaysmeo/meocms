<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Change php version to >= 8.2

`sudo update-alternatives --config php`

## Install php expand

- must: `php8.3-redis` `php8.3-dom` `php8.3-xml` `php8.3-curl` `php8.3-mysql` `php8.3-fpm` `php8.3-gd`
- selectable: `php8.3-mbstring` `php-xdebug` `php8.3-xdebug`

## Database migrations

`php artisan migrate`

## Database seeding

`php artisan db:seed`

## License

- The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
