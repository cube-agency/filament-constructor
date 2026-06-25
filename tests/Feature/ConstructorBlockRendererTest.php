<?php

use CubeAgency\FilamentConstructor\Constructor\ConstructorBlockRenderer;
use CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks\TestBlock;

it('renders block html from the saved state', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    $html = $renderer->render([
        (object) ['type' => 'test_block', 'data' => ['heading' => 'Hello World']],
    ]);

    expect($html)->toContain('Hello World');
});

it('concatenates the html of every block in order', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    $html = $renderer->render([
        (object) ['type' => 'test_block', 'data' => ['heading' => 'First']],
        (object) ['type' => 'test_block', 'data' => ['heading' => 'Second']],
    ]);

    expect(strpos($html, 'First'))->toBeLessThan(strpos($html, 'Second'));
});

it('returns an empty string when there are no blocks', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    expect($renderer->render([]))->toBe('');
});
