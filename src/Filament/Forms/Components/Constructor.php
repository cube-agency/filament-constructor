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
            $block = Builder\Block::make($blockRenderer->name())
                ->label($blockRenderer->title())
                ->schema($blockRenderer->schema());

            $icon = $blockRenderer->icon();
            if ($icon) {
                $block->icon($icon);
            }

            $limit = $blockRenderer->maxItems();
            if ($limit) {
                $block->maxItems($limit);
            }

            $columns = $blockRenderer->columns();
            if ($columns) {
                $block->columns($columns);
            }

            $blockFields[] = $block;
        }

        $this->blocks($blockFields);
        $this->blockIcons();
    }
}
