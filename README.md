# FTP

Laravel 5 wrapper for the FTP with multiple connections.

[![Build Status](https://img.shields.io/travis/BlueBayTravel/FTP.svg?style=flat-square)](https://travis-ci.org/BlueBayTravel/FTP)
[![StyleCI](https://styleci.io/repos/25159957/shield)](https://styleci.io/repos/25159957)

```php
// Return the files on the server.
Ftp::ls()

// Dependency injection example.
$ftpManager->ls()
````

## Installation

Require this package, with [Composer](https://getcomposer.org/), in the root directory of your project.

```bash
composer require bluebaytravel/ftp
```

Add the service provider to `config/app.php` in the `providers` array.

```php
BlueBayTravel\Ftp\FtpServiceProvider::class
```

If you want you can use the [facade](http://laravel.com/docs/facades). Add the reference in `config/app.php` to your aliases array.

```php
'Ftp' => BlueBayTravel\Ftp\Facades\Ftp::class
```

## Configuration

Laravel Ftp requires connection configuration. To get started, you'll need to publish all vendor assets:

```bash
php artisan vendor:publish
```

This will create a `config/ftp.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

#### Default Connection Name

This option `default` is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is `main`.

#### Ftp Connections

This option `connections` is where each of the connections are setup for your application. Example configuration has been included, but you may add as many connections as you would like.

## Usage

### Ftp

This is a wrapper around the native `ftp_` functions.

#### FtpManager

This is the class of most interest. It is bound to the ioc container as `ftp` and can be accessed using the `Facades\Ftp` facade. This class implements the ManagerInterface by extending AbstractManager. The interface and abstract class are both part of [Graham Campbell's](https://github.com/GrahamCampbell) [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at that repository. Note that the connection class returned will always be an instance of `BlueBayTravel\Ftp\Ftp`.

#### Facades\Ftp

This facade will dynamically pass static method calls to the `ftp` object in the ioc container which by default is the `FtpManager` class.

#### FtpServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

### Examples
Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `main`. After you enter your authentication details in the config file, it will just work:

```php
// You can alias this in config/app.php.
use BlueBayTravel\Ftp\Facades\Ftp;

Ftp::users();
```

The Ftp manager will behave like it is a `BlueBayTravel\Ftp\Ftp`. If you want to call specific connections, you can do that with the connection method:

```php
use BlueBayTravel\Ftp\Facades\Ftp;

// Writing this…
Ftp::connection('main')->ls();

// ...is identical to writing this
Ftp::ls();

// and is also identical to writing this.
Ftp::connection()->ls();

// This is because the main connection is configured to be the default.
Ftp::getDefaultConnection(); // This will return main.

// We can change the default connection.
Ftp::setDefaultConnection('alternative'); // The default is now alternative.
```

If you prefer to use dependency injection over facades like me, then you can inject the manager:

```php
use BlueBayTravel\Ftp\FtpManager;

class Foo
{
    protected $ftp;

    public function __construct(FtpManager $ftp)
    {
        $this->ftp = $ftp;
    }

    public function bar($id)
    {
        $this->ftp->ls();
    }
}

App::make('ftp')->ls();
```

## License

FTP is licensed under [The MIT License (MIT)](LICENSE).
