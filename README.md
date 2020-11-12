# ActiveLearningStudio Admin Panel

## Introduction

Administration Panel for the CurrikiStudio application. The administrative panel is a laravel/php application built on top of the CurrikiStudio API to help manage users, instructional resources (projects, playlists, and activities), as well as a place to configure the CurrikiStudion ReactJs application.

See the following links for the repositories that contain the CurrikiStudio API and ReactJS applications:
https://github.com/ActiveLearningStudio/ActiveLearningStudio-API<br />
https://github.com/ActiveLearningStudio/ActiveLearningStudio-react-client<br />
<br />
<img src="https://www.curriki.org/wp-content/uploads/2020/11/admin-panel.png">
<br /><br />
## Requirements

- PHP > 7.3
- NodeJS with npm
- HTTP server with PHP support (e.g.: Apache, Nginx, Caddy)
- [Composer](https://getcomposer.org)
- A supported database: PostgreSQL

 #### PHP Extensions
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- CURL PHP Extension

## Installation / Clone

Clone the repository with git clone.
```bash
git clone https://github.com/ActiveLearningStudio/ActiveLearningStudio-admin-panel.git
```

Copy .env.example file to .env and edit database credentials there.

Install dependencies:
```bash
composer install
```
```bash
npm install
```

Generate the key:
```bash
php artisan key:generate
```

Once `Curriki Admin Panel Repo` setup, run the migrations
```bash
php artisan migrate
```
## Used Assets & Packages

- [Admin LTE](https://github.com/jeroennoten/Laravel-AdminLTE/) (Version 3)
- [DataTables](https://github.com/yajra/laravel-datatables) (version 9)
- [Laravel UI](https://github.com/laravel/ui) (version 2.2)
- [Laravel form builder](https://github.com/glhd/aire) (version 2.3)

## Credits

- [CurrikiStudio](https://github.com/ActiveLearningStudio)

Bug reports, and pull requests can be submitted by following github docs [Contribution Guide](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request).

## Contributors

Initially contributed by Ahmad Mukhtar.
- [Coding Standards](https://www.php-fig.org/psr/psr-12/)
- [Pull Requests](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request)

## License

 © 2020 Curriki Studio, All rights reserved. 
