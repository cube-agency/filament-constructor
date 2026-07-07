<?php

use CubeAgency\FilamentConstructor\Filament\Forms\Components\Constructor;
use CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks\SecondaryBlock;
use CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks\TestBlock;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Field;
use Filament\Schemas\Schema;

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

/**
 * Recursively collect the names of every field within a component's schema.
 *
 * @return array<string>
 */
function blockFieldNames($component): array
{
    $names = [];

    $children = $component->getDefaultChildComponents();

    if ($children instanceof Schema) {
        $children = $children->getComponents();
    }

    foreach ($children as $child) {
        if ($child instanceof Field) {
            $names[] = $child->getName();
        }

        $names = array_merge($names, blockFieldNames($child));
    }

    return $names;
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

it('adds block name and anchor fields to every block', function () {
    config()->set('filament-constructor.blocks', ['test_block' => TestBlock::class]);

    $block = constructorBlocks(Constructor::make('content'))[0];

    expect(blockFieldNames($block))
        ->toContain('heading')      // the renderer's own field is preserved
        ->toContain('block_name')
        ->toContain('block_anchor');
});

it('uses the block name as the label and falls back to the title', function () {
    config()->set('filament-constructor.blocks', ['test_block' => TestBlock::class]);

    $block = constructorBlocks(Constructor::make('content'))[0];

    expect($block->getLabel(null))->toBe('Test Block')            // block picker
        ->and($block->getLabel([]))->toBe('Test Block')           // new, unnamed block
        ->and($block->getLabel(['block_name' => '']))->toBe('Test Block')
        ->and($block->getLabel(['block_name' => 'Hero header']))->toBe('Hero header');
});

it('slugifies the block anchor and nullifies empty values', function () {
    expect(Constructor::slugifyAnchor('Hello World'))->toBe('hello-world')
        ->and(Constructor::slugifyAnchor('Already-Slugged'))->toBe('already-slugged')
        ->and(Constructor::slugifyAnchor(''))->toBeNull()
        ->and(Constructor::slugifyAnchor(null))->toBeNull();
});

it('registers the block settings translations', function () {
    expect(__('filament-constructor::block-settings.title'))->toBe('Block settings')
        ->and(__('filament-constructor::block-settings.name'))->toBe('Name')
        ->and(__('filament-constructor::block-settings.anchor'))->toBe('Anchor');
});
