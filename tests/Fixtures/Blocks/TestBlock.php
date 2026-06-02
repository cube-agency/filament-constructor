<?php

namespace CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks;

use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRenderer;
use Filament\Forms\Components\TextInput;

class TestBlock extends BlockRenderer
{
    public function name(): string
    {
        return 'test_block';
    }

    public function title(): string
    {
        return 'Test Block';
    }

    public function icon(): ?string
    {
        return 'heroicon-o-square-3-stack-3d';
    }

    public function maxItems(): ?int
    {
        return 3;
    }

    public function columns(): ?int
    {
        return 2;
    }

    public function schema(): array
    {
        return [
            TextInput::make('heading'),
        ];
    }
}
