<?php

use CubeAgency\FilamentConstructor\Filament\Forms\Components\Constructor;
use CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks\SecondaryBlock;
use CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks\TestBlock;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;

/**
 * @return array<Block>
 */
function constructorBlocks(Constructor $constructor): array
{
    return $constructor->getDefaultChildComponents();
}

/**
 * @return array<string>
 */
function constructorBlockNames(Constructor $constructor): array
{
    return array_map(fn (Block $block) => $block->getName(), constructorBlocks($constructor));
}

it('is a filament builder', function () {
    config()->set('filament-constructor.blocks', ['test_block' => TestBlock::class]);

    expect(Constructor::make('content'))->toBeInstanceOf(Builder::class);
});

it('registers blocks from the package config', function () {
    config()->set('filament-constructor.blocks', ['test_block' => TestBlock::class]);

    expect(constructorBlockNames(Constructor::make('content')))->toBe(['test_block']);
});

it('overrides the config blocks with the use() method', function () {
    config()->set('filament-constructor.blocks', ['test_block' => TestBlock::class]);

    $constructor = Constructor::make('content')
        ->use(['secondary_block' => SecondaryBlock::class]);

    expect(constructorBlockNames($constructor))->toBe(['secondary_block']);
});

it('applies the block options defined on the renderer', function () {
    config()->set('filament-constructor.blocks', ['test_block' => TestBlock::class]);

    $block = constructorBlocks(Constructor::make('content'))[0];

    expect($block->getName())->toBe('test_block')
        ->and($block->getLabel())->toBe('Test Block')
        ->and($block->getIcon())->toBe('heroicon-o-square-3-stack-3d')
        ->and($block->getMaxItems())->toBe(3)
        ->and($block->getColumns('lg'))->toBe(2);
});
