# Shop Engine

![Cithub Actions](https://github.com/signalfire/shopengine/actions/workflows/php.yml/badge.svg)

![StyleCI](https://github.styleci.io/repos/448303978/shield)

## Installation

Remove app/Nova resource files (not needed)

In the config/auth.php set the user provider model to Signalfire\Shopengine\Models\User

To seed data `php artisan db:seed --class=\\Signalfire\\Shopengine\\Database\\Seeders\\DatabaseSeeder`