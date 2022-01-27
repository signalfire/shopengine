# Shop Engine

![Cithub Actions](https://github.com/signalfire/shopengine/actions/workflows/php.yml/badge.svg)

![StyleCI](https://github.styleci.io/repos/448303978/shield)

## Installation

Delete the 2014_10_12_000000_create_users_table.php laravel migration (@TODO: publish our migrations instead ?) to enable UUID for user

Remove app/Nova resource files (not needed)

In the config/auth.php set the user provider model to Signalfire\Shopengine\Models\User

To seed data `php artisan db:seed --class=\\Signalfire\\Shopengine\\Database\\Seeders\\DatabaseSeeder`