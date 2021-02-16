# A tile to display the issues from sentry

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wotta/sentry-dashboard-tile.svg?style=flat-square)](https://packagist.org/packages/wotta/sentry-dashboard-tile)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/wotta/laravel-dashboard-sentry-tile/run-tests?label=tests)](https://github.com/wotta/laravel-dashboard-sentry-tile/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/wotta/sentry-dashboard-tile.svg?style=flat-square)](https://packagist.org/packages/wotta/sentry-dashboard-tile)

This package allows you to get an overview of the latest 20 issues or get the issues from a specific project.

This tile can be used on [the Laravel Dashboard](https://docs.spatie.be/laravel-dashboard).

## Installation

You can install the package via composer:

```bash
composer require wotta/sentry-dashboard-tile
```

To publish the migration stubs you need to run the following command
```bash
php artisan vendor:publish --tag=dashboard-sentry-migrations
```

## Usage

In your dashboard view you use the `livewire:sentry-tile` component.

```html
<x-dashboard>
    <livewire:sentry-tile position="a1:a2" title="Issues" :showMeta="true" :refresh-interval-in-seconds="30" />
    <livewire:sentry-tile position="b1:b2" title="Issues" projectName="your-project" :showMeta="false" :refresh-interval-in-seconds="30" />
</x-dashboard>
```

You need to add the following config to your `dashboard.php` config file.
```php
'tiles' => [
    'sentry' => [
        'organization' => 'exampleorg',
        'token' => env('SENTRY_TILE_TOKEN'),
        'production_only' => env('SENTRY_TILE_PRODUCTION', false),
    ]
]
```

And to periodically sync the issues from sentry you need to add the following to your `Console/Kernel.php`:
```php
$schedule->command(ListenForSentryIssuesCommand::class)->everyThirtyMinutes();
```

Add the following code to your `Console/Kernel.php` to import the teams (and projects).
```php
 $schedule->command(SyncOrganizationTeams::class, [
     '--with-projects'
 ])->hourly();
 ```

## Commands

### # sentry:sync:organization:teams
Import the teams that belong to this organization

**Arguments**:
- organization - The organization name `Optional`

**Options**:
- with-projects - Import the projects that belong to the organization `Optional`

```bash
php artisan sentry:sync:organization:teams [<organization>] [--with-projects]
```

### # dashboard:fetch-data-from-sentry-api
Import the projects and issues for the imported projects

```bash
php artisan dashboard:fetch-data-from-sentry-api
```

## Testing

The current coverage for the package can be found [here](https://sentry-dashboard-tile.vercel.app).

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email wouter.van.marrum@protonmail.com instead of using the issue tracker.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
