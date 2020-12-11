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

## Usage

In your dashboard view you use the `livewire:sentry-title` component.

```html
<x-dashboard>
    <livewire:sentry-tile position="a1:a2" title="Issues" :showMeta="true" :refresh-interval-in-seconds="30" />
    <livewire:sentry-tile position="b1:b2" title="Issues" projectName="your-project" :showMeta="false" :refresh-interval-in-seconds="30" />
</x-dashboard>
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
