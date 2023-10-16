<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader\Sources;

interface ISource
{
    public function getRawData(int $x, int $y, int $z, string $key, string $value, string $type): string;
}
