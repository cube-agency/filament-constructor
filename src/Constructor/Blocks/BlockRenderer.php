<?php

namespace CubeAgency\FilamentConstructor\Constructor\Blocks;

use Illuminate\Support\Str;
use Illuminate\View\View;

abstract class BlockRenderer implements BlockRendererInterface
{
    public function view(object $block, array $additionalData = []): View
    {
        $viewName = Str::kebab(Str::studly($this->name()));
        $data = (array)$block->data + $additionalData + ['block' => $block];

        return view('constructor.blocks.' . $viewName, $data);
    }

    public function html(object $block): string
    {
        return $this->view($block)->render();
    }

    public function maxItems(): ?int
    {
        return null;
    }

    public function columns(): ?int
    {
        return null;
    }

    public function icon(): ?string
    {
        return null;
    }
}
