<?php

declare(strict_types=1);

namespace UnitTests;

use Locr\Lib\Vms2TileDbReader\DataType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DataType::class)]
final class DataTypeTest extends TestCase
{
    public function testTryFromString(): void
    {
        $this->assertEquals(DataType::Points, DataType::tryFromString('Points'));
        $this->assertEquals(DataType::Lines, DataType::tryFromString('Lines'));
        $this->assertEquals(DataType::Polygons, DataType::tryFromString('Polygons'));

        $this->assertNull(DataType::tryFromString(''));
    }
}
