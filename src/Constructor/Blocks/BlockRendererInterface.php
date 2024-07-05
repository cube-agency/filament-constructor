<?php

namespace CubeAgency\FilamentConstructor\Constructor\Blocks;

interface BlockRendererInterface
{
    public function title(): string;

    public function name(): string;

    public function icon(): ?string;

    public function maxItems(): ?int;

    public function columns(): ?int;

    public function schema(): array;

    public function html(object $block): string;
}
