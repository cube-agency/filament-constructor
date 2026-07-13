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

it('renders an active block', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    $html = $renderer->render([
        (object) ['type' => 'test_block', 'data' => ['heading' => 'Visible', 'is_active' => true]],
    ]);

    expect($html)->toContain('Visible');
});

it('renders a block that has no is_active key (backward compatible)', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    $html = $renderer->render([
        (object) ['type' => 'test_block', 'data' => ['heading' => 'Legacy']],
    ]);

    expect($html)->toContain('Legacy');
});

it('hides an inactive block', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    $html = $renderer->render([
        (object) ['type' => 'test_block', 'data' => ['heading' => 'Hidden', 'is_active' => false]],
    ]);

    expect($html)->not->toContain('Hidden');
});

it('hides a block before its start time and shows it at the boundary', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);
    $block = (object) ['type' => 'test_block', 'data' => ['heading' => 'Scheduled', 'starts_at' => '2026-07-10 12:00:00']];

    $this->travelTo('2026-07-10 11:59:59');
    expect($renderer->render([$block]))->not->toContain('Scheduled');

    $this->travelTo('2026-07-10 12:00:00');
    expect($renderer->render([$block]))->toContain('Scheduled');
});

it('hides a block after its end time and shows it at the boundary', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);
    $block = (object) ['type' => 'test_block', 'data' => ['heading' => 'Expiring', 'ends_at' => '2026-07-10 12:00:00']];

    $this->travelTo('2026-07-10 12:00:00');
    expect($renderer->render([$block]))->toContain('Expiring');

    $this->travelTo('2026-07-10 12:00:01');
    expect($renderer->render([$block]))->not->toContain('Expiring');
});

it('renders a block inside its window and with empty dates', function () {
    $renderer = new ConstructorBlockRenderer(['test_block' => TestBlock::class]);

    $this->travelTo('2026-07-10 12:00:00');

    $windowed = (object) ['type' => 'test_block', 'data' => ['heading' => 'InWindow', 'starts_at' => '2026-07-10 00:00:00', 'ends_at' => '2026-07-10 23:59:59']];
    $unbounded = (object) ['type' => 'test_block', 'data' => ['heading' => 'Unbounded', 'starts_at' => null, 'ends_at' => null]];

    expect($renderer->render([$windowed]))->toContain('InWindow')
        ->and($renderer->render([$unbounded]))->toContain('Unbounded');
});
