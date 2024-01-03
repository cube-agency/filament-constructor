# Block constructor for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cube-agency/filament-constructor.svg?style=flat-square)](https://packagist.org/packages/cube-agency/filament-constructor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/cube-agency/filament-constructor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cube-agency/filament-constructor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/cube-agency/filament-constructor/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/cube-agency/filament-constructor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/cube-agency/filament-constructor.svg?style=flat-square)](https://packagist.org/packages/cube-agency/filament-constructor)

Filament plugin for creating multiple field groups

## Installation

You can install the package via composer:

```bash
composer require cube-agency/filament-constructor
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-constructor-config"
```

## Usage

Create your constructor block by using console command:

```php
php artisan filament-constructor:create-block DoubleText
```

this will create a new class in Filament\Constructor\Blocks
```php
namespace App\Filament\Constructor\Blocks;

use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRenderer;
use Filament\Forms\Components\Textarea;

class DoubleTextBlock extends BlockRenderer
{
    public function name(): string
    {
        return 'double_text';
    }

    public function title(): string
    {
        return __('DoubleText');
    }

    public function schema(): array
    {
        return [
            Textarea::make('first_text'),
            Textarea::make('second_text'),
        ];
    }
}
```
Add it to config
```php
return [
    'blocks' => [
        'double_text' => \App\Filament\Constructor\Blocks\DoubleTextBlock::class,
    ],
];
```
Then add this field to your form
```php
use CubeAgency\FilamentConstructor\Filament\Forms\Components\Constructor;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            // ...
            Constructor::make('blocks'),
            // ...
        ]);
}
```

Or you can create multiple block groups and use different blocks in each resource

```php
return [
    'blocks' => [
        'double_text' => \App\Filament\Constructor\Blocks\DoubleTextBlock::class,
    ],
    
    'image_blocks' => [
        'double_image' => \App\Filament\Constructor\Blocks\DoubleImageBlock::class,
    ],
];
```

```php
use CubeAgency\FilamentConstructor\Filament\Forms\Components\Constructor;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            // ...
            Constructor::make('blocks')->use(config('filament-constructor.image_blocks')),
            // ...
        ]);
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dmitrijs Mihailovs](https://github.com/dmitrijs.mihailovs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
