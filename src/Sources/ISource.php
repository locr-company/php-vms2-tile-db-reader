<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader\Sources;

use Locr\Lib\Vms2TileDbReader\DataType;

interface ISource
{
    public function getRawData(
        int $x,
        int $y,
        int $z,
        string $key,
        string $value = '',
        DataType $type = DataType::Polygons
    ): string;
}
