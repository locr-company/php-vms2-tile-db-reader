<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader;

enum DataType: int
{
    case Points = 0;
    case Lines = 1;
    case Polygons = 2;

    public static function tryFromString(string $input): ?self
    {
        return match (strtolower($input)) {
            'points' => self::Points,
            'lines' => self::Lines,
            'polygons' => self::Polygons,
            default => null
        };
    }
}
