<?php

namespace CubeAgency\FilamentConstructor\Constructor;

use Carbon\Carbon;
use CubeAgency\FilamentConstructor\Constructor\Blocks\BlockRendererInterface;
use Illuminate\Support\Arr;

class ConstructorBlockRenderer
{
    public function __construct(protected array $blocks = []) {}

    public function render(?array $blocks = []): string
    {
        $html = '';

        foreach ($blocks as $block) {
            if (! $this->isBlockVisible($block)) {
                continue;
            }

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

    protected function isBlockVisible(object $block): bool
    {
        $data = (array) ($block->data ?? []);

        if (array_key_exists('is_active', $data) && ! $data['is_active']) {
            return false;
        }

        $now = now();

        if (filled($data['starts_at'] ?? null) && $now->lt(Carbon::parse($data['starts_at']))) {
            return false;
        }

        if (filled($data['ends_at'] ?? null) && $now->gt(Carbon::parse($data['ends_at']))) {
            return false;
        }

        return true;
    }

    protected function getBlockRendererClass(string $name): string
    {
        return Arr::get($this->blocks, $name);
    }
}
