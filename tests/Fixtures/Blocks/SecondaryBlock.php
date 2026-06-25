<?php

namespace CubeAgency\FilamentConstructor\Tests\Fixtures\Blocks;

use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRenderer;
use Filament\Forms\Components\TextInput;

class SecondaryBlock extends BlockRenderer
{
    public function name(): string
    {
        return 'secondary_block';
    }

    public function title(): string
    {
        return 'Secondary Block';
    }

    public function schema(): array
    {
        return [
            TextInput::make('subheading'),
        ];
    }
}
