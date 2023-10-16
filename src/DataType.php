<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader;

enum DataType: int
{
    case Points = 0;
    case Lines = 1;
    case Polygons = 2;
}
