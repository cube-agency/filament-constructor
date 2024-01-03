<?php

namespace CubeAgency\FilamentConstructor\Constructor;

use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRendererInterface;
use Illuminate\Support\Arr;

class ConstructorBlockRenderer
{
    public function __construct(protected array $blocks = [])
    {
    }

    public function render(?array $blocks = []): string
    {
        $html = '';

        foreach ($blocks as $block) {
            $html .= $this->getConstructorBlockHtml($block);
        }

        return $html;
    }

    protected function getConstructorBlockHtml(object $block): string
    {
        $name = $block->type;
        $blockRenderer = resolve($this->getBlockRendererClass($name));

        if ($blockRenderer instanceof BlockRendererInterface) {
            return $blockRenderer->html($block);
        }

        return '';
    }

    protected function getBlockRendererClass(string $name): string
    {
        return Arr::get($this->blocks, $name);
    }
}
