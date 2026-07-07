<?php

namespace CubeAgency\FilamentConstructor\Filament\Forms\Components;

use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRenderer;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

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

    public static function slugifyAnchor(?string $state): ?string
    {
        return filled($state) ? Str::slug($state) : null;
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
                ->label(fn (?array $state): string => filled($state['block_name'] ?? null)
                    ? $state['block_name']
                    : $blockRenderer->title())
                ->schema([
                    ...$blockRenderer->schema(),
                    $this->blockSettingsSection(),
                ]);

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

    protected function blockSettingsSection(): Section
    {
        return Section::make(__('filament-constructor::block-settings.title'))
            ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('block_name')
                            ->label(__('filament-constructor::block-settings.name')),
                        TextInput::make('block_anchor')
                            ->label(__('filament-constructor::block-settings.anchor'))
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (?string $state, Set $set) => $set('block_anchor', static::slugifyAnchor($state)))
                            ->dehydrateStateUsing(fn (?string $state) => static::slugifyAnchor($state))
                            ->rules(['regex:/^([a-z0-9]+(?:-[a-z0-9]+)*)?$/']),
                    ]),
            ])
            ->collapsible()
            ->collapsed();
    }
}
