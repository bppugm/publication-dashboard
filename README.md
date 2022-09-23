# Publication Dashboard
Source code for BPP UGM Publication Dashboard

## System Requirements
- PHP 7.4
- PHP extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath
- MySQL Database
- Composer
- NodeJS >= 14.x

## How to Install
1. Clone this repo to your machine
2. Run `composer install`
3. Make `.env` from `.env.example`
4. Fill database section in `.env`
6. run `php artisan key:generate`
7. run `php artisan migrate --seed`
8. run `php artisan storage:link`
10. run `npm install`

## Queue Configuration
### local environment
- Make sure you have changed `.env` `QUEUE_CONNECTION` to `database` or `redis`. 
- In your project root, run `php artisan queue:work`. 
- For more information about Laravel Queue Feature, visit [the official documentation page](https://laravel.com/docs/queues).

## Broadcasting Configuration
### Via Pusher
- Create a new Pusher Channel app https://dashboard.pusher.com/
- Fill `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`, in `.env` based on your Pusher Channel App Keys
- Change `BROADCAST_DRIVER` to `pusher` in `.env`
### Via Redis and Socket.io
- Prepare a Laravel Echo Server (https://github.com/tlaverdure/laravel-echo-server)
- Change `BROADCAST_DRIVER` to `redis` in `.env`
- Fill `REDIS_BROADCAST_HOST` `REDIS_BROADCAST_PASSWORD` `REDIS_BROADCAST_PORT` `REDIS_BROADCAST_DB` in `.env` according to your Laraver Echo Server Configuration

## Credits
### Main Contributors
- [Wildan Ainurrahman](https://github.com/w1lldone)
- [Michael Roberto](https://github.com/michaelr1300)
- [Aldiansyah Triewicaksono](https://github.com/ebifurei)
### Supporting Contributors
- Bagus Gilang R
### Development
- Built using [Laravel 8](https://laravel.com/)
- Frontend framework using [VueJs 2](https://vuejs.org/)
- HTML, CSS, and Javascript framework using [Bootstrap 5](https://getbootstrap.com/)
- [Pusher](https://pusher.com/) and [Laravel Echo Server](https://github.com/tlaverdure/laravel-echo-server) for Broadcasting Service

