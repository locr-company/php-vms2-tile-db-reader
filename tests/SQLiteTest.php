<?php

declare(strict_types=1);

namespace UnitTests;

use Locr\Lib\Vms2TileDbReader\Exceptions\{InvalidTypeException, SourceDbNotFoundException};
use Locr\Lib\Vms2TileDbReader\Sources\SQLite;
use PHPUnit\Framework\Attributes\{CoversClass, UsesClass};
use PHPUnit\Framework\TestCase;

#[CoversClass(SQLite::class)]
#[UsesClass(SourceDbNotFoundException::class)]
#[UsesClass(InvalidTypeException::class)]
final class SQLiteTest extends TestCase
{
    public function testGetDataBuildingPolygons(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(x: 34686, y:  21566, z: 16, key: 'building', value: '*', type: 'Polygons');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetDataCityPoints(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(34686, 21566, 16, 'place', 'city', 'Points');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetLandData(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(34686, 21566, 16, 'land', '', '');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetTerrainData(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(34686, 21566, 16, 'terrain', '', '');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetBlueMarbleData(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(34686, 21566, 16, 'blue_marble', '', '');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetDataFromInternalMultiTileQuery(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(1083, 673, 12, 'land', '', '');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testDbFileDoesNotExists(): void
    {
        $this->expectExceptionMessage(
            'Locr\Lib\Vms2TileDbReader\Sources\SQLite::__construct(string $filename) => $filename does not exists'
        );

        new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'invalid.sqlite');
    }

    public function testInvalidType(): void
    {
        $this->expectExceptionMessage(
            'Locr\Lib\Vms2TileDbReader\Sources\SQLite::' .
                'getRawData(int $x, int $y, int $z, string $key, string $value, string $type): string' .
                ' => Invalid $type value (Invalid). Allowed values are: "Points", "Lines" or "Polygons"'
        );

        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileDb->getRawData(x: 34686, y:  21566, z: 16, key: 'building', value: '*', type: 'Invalid');
    }
}
