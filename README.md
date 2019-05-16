# uRepairPC - Server

<p align="center">
    <a href="https://github.com/uRepairPC">
        <img width="500" src="https://raw.githubusercontent.com/uRepairPC/docs/master/public/logo-left-icon.png" alt="uRepairPC">
    </a>
</p>
<p align="center">
    Accounting system for orders for the repair of technical means.
</p>

<p align="center">
	<a href="https://github.com/uRepairPC/server" rel="nofollow"><img src="https://img.shields.io/github/tag/urepairpc/server.svg" alt="Tag"></a>
	<a href="https://styleci.io/repos/152962669" rel="nofollow"><img src="https://styleci.io/repos/152962669/shield?branch=master" alt="StyleCI"></a>
	<a href="https://github.com/uRepairPC/server" rel="nofollow"><img src="https://img.shields.io/github/license/urepairpc/server.svg" alt="License"></a>
</p>

## Docs
See [here](https://urepairpc.github.io/docs/)

## Quick Start
```bash
# Copy .env.example to .env and config this (Database, etc)
$ php artisan key:generate
$ php artisan jwt:secret

# Install dependencies
$ composer install --optimize-autoloader --no-dev

# Migration
$ php artisan migrate
$ php artisan db:seed

# Optimization
$ php artisan config:cache
```

## Database
<img src="https://raw.githubusercontent.com/uRepairPC/docs/master/public/database.png" alt="Database">

## Ecosystem
| Project | Status | Description |
|---------|--------|-------------|
| [urepairpc-server]    | ![urepairpc-server-status]    | Backend on Laravel |
| [urepairpc-web]       | ![urepairpc-web-status]       | Frontend on Vue |
| [urepairpc-websocket] | ![urepairpc-websocket-status] | WebSocket Backend |

[urepairpc-server]: https://github.com/uRepairPC/server
[urepairpc-server-status]: https://img.shields.io/github/tag/urepairpc/server.svg

[urepairpc-web]: https://github.com/uRepairPC/web
[urepairpc-web-status]: https://img.shields.io/github/package-json/v/urepairpc/web.svg

[urepairpc-websocket]: https://github.com/uRepairPC/websocket
[urepairpc-websocket-status]: https://img.shields.io/github/package-json/v/urepairpc/websocket.svg

## Changelog
Detailed changes for each release are documented in the [CHANGELOG.md](https://github.com/uRepairPC/web/blob/master/CHANGELOG.md).

## License
[MIT](https://opensource.org/licenses/MIT)
