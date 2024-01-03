<?php

namespace CubeAgency\FilamentConstructor\Filament\Forms\Components;

use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRenderer;
use Filament\Forms\Components\Builder;

class Constructor extends Builder
{
    protected array $blocks = [];

    public function setUp(): void
    {
        $this->setBlocks(config('filament-constructor.blocks', []));

        parent::setUp();
    }

    public function use(array $blocks): static
    {
        $this->setBlocks($blocks);

        return $this;
    }

    protected function setBlocks(array $blocks): void
    {
        $blockFields = [];

        foreach ($blocks as $block) {
            /**
             * @var BlockRenderer $blockRenderer
             */
            $blockRenderer = resolve($block);
            $blockFields[] = Builder\Block::make($blockRenderer->name())
                ->label($blockRenderer->title())
                ->schema($blockRenderer->schema());
        }

        $this->blocks($blockFields);
    }
}
