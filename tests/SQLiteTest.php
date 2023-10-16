<?php

declare(strict_types=1);

namespace UnitTests;

use Locr\Lib\Vms2TileDbReader\DataType;
use Locr\Lib\Vms2TileDbReader\Exceptions\SourceDbNotFoundException;
use Locr\Lib\Vms2TileDbReader\Sources\SQLite;
use PHPUnit\Framework\Attributes\{CoversClass, UsesClass};
use PHPUnit\Framework\TestCase;

#[CoversClass(SQLite::class)]
#[UsesClass(SourceDbNotFoundException::class)]
final class SQLiteTest extends TestCase
{
    public function testGetDataBuildingPolygons(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(
            x: 34686,
            y:  21566,
            z: 16,
            key: 'building',
            value: '*',
            type: DataType::Polygons
        );

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetDataCityPoints(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'place', value: 'city', type: DataType::Points);

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetLandData(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'land');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetTerrainData(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'terrain');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetBlueMarbleData(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'blue_marble');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testGetDataFromInternalMultiTileQuery(): void
    {
        $tileDb = new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'braunschweig.sqlite');
        $tileData = $tileDb->getRawData(x: 1083, y: 673, z: 12, key: 'land');

        $this->assertGreaterThanOrEqual(4, strlen($tileData));
    }

    public function testDbFileDoesNotExists(): void
    {
        $this->expectExceptionMessage(
            'Locr\Lib\Vms2TileDbReader\Sources\SQLite::__construct(string $filename) => $filename does not exists'
        );

        new SQLite(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'invalid.sqlite');
    }
}
