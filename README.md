# Laravel 5 database config

This is **Laravel 5** package for storing dynamic config in database.

**Table of content**

* [Features](#features)
* [Installation](#installation)
* [Usage](#usage)
* [License](#license)

---
[Back to top][top]

## Features

* Dynamic config stored in database
* Easily add new keys

---
[Back to top][top]

## Installation

Via `composer` run following command in your project root:

```bash
composer require vi-kon/laravel-db-config
```

In your Laravel 5 project add following lines to `app.php`:

```php
// to app.php's providers array
'ViKon\DbConfig\DbConfigServiceProvider',
```

To install database migration file simple call following commands:

```bash
php artisan vendor:publish --provider=ViKon\DbConfig\DbConfigServiceProvider

php artisan migrate
```

This will install `create_config_table` migration file to application `database/migrations` directory and create new `config` table in default database.

---
[Back to top][top]

## Usage

* [Getting values](#getting-values)
* [Setting values](#setting-values)
* [Organize config keys](#organize-config-keys)

### Getting values

Getting data from database is simple:

```php
$value = config_db('key');
```

Provide default value if key not exists in database

```php
$value = config_db('key', 'default_value');
```

If no parameter provided to `config_db` function, then it returns `ViKon\DbConfig\DbConfig` instance.

```php
// Get value by key
$value = config_db()->get('key');

// Get value by key with default value
$value = config_db()->get('key', 'default_value');
```

### Set values

Set values to database is easy simple call `config_db` function with no parameter and after it call `set` method.

```php
config_db()->set('key', 'value');
```

###Organize config keys

For better maintenance there is a support organizing config values to groups. To get or set config value to group simple use `{group}::{key}` schema as key. 

```php
// Gets 'db' group username value 
$value = config_db('db::username');
```

---
[Back to top][top]

## License

This package is licensed under the MIT License

---
[Back to top][top]

[top]: #laravel-5-database-config
