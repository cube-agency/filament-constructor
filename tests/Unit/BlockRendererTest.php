<?php

use CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks\TestBlock;

it('exposes the block state and the block instance to the view', function () {
    $block = (object) ['data' => ['heading' => 'Hi']];

    $view = (new TestBlock)->view($block);

    expect($view->getData()['heading'])->toBe('Hi')
        ->and($view->getData()['block'])->toBe($block);
});

it('lets additional data override block state of the same name', function () {
    $block = (object) ['data' => ['heading' => 'Original']];

    $view = (new TestBlock)->view($block, ['heading' => 'Overridden']);

    expect($view->getData()['heading'])->toBe('Overridden');
});
