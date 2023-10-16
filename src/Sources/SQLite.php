<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader\Sources;

class SQLite implements ISource
{
    private \PDO $db;

    /**
     * Open the database for reading data from it. \
     * The constructor will throw an exception, if the file does not exists!
     *
     * ```php
     * <?php
     *
     * use Locr\Lib\Vms2TileDbReader\Sources\SQLite;
     *
     * $tileDb = new SQLite('germany.sqlite');
     * // $tileDb->getRawData(...);
     * ```
     */
    public function __construct(string $filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception(__METHOD__ . '(string $filename) => $filename does not exists.', 404);
        }

        $this->db = new \PDO("sqlite:{$filename}");
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Get the specified data from the database. \
     * This method can throw an exception, if the $type-parameter is invalid or if the internal database query failed.
     *
     * ```php
     * <?php
     *
     * use Locr\Lib\Vms2TileDbReader\Sources\SQLite;
     *
     * $tileDb = new SQLite('germany.sqlite');
     * $tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'building', value: '*', type: 'Polygons');
     *
     * header('Content-Type: application/octet-stream'); // The Content-Type is required for the Web-App.
     * print $tileData;
     * ```
     */
    public function getRawData(int $x, int $y, int $z, string $key, string $value, string $type): string
    {
        switch ($key) {
            case 'land':
            case 'terrain':
            case 'blue_marble':
            case 'elevation':
            case 'bathymetry':
            case 'depth':
                $value = $key;
                $key = 'locr';
                $type = 'Polygons';
                break;
        }

        $type = match ($type) {
            'Points' => 0,
            'Lines' => 1,
            'Polygons' => 2,
            default => throw new \Exception(
                __METHOD__ . '(int $x, int $y, int $z, string $key, string $value, string $type): ?string' .
                    ' => Invalid $type value (' . $type . '). Allowed values are: "Points", "Lines" or "Polygons".',
                500
            )
        };

        $detailZooms = [0, 0, 2, 2, 4, 4, 6, 6, 8, 8, 10, 10, 12, 12, 14];
        switch ($value) {
            case 'terrain':
            case 'depth':
                $detailZooms = [0, 0, 2, 2, 4, 4, 6, 6, 8, 8, 10, 10, 12, 12, 12];
                break;

            case 'bathymetry':
            case 'blue_marble':
            case 'elevation':
                $detailZooms = [0, 0, 2, 2, 4, 4, 6, 6, 8, 8, 10, 10, 10, 10, 10];
                break;
        }
        $detailZoom = $detailZooms[max(min($z, 14), 0)];

        if ($type === 0) {
            $detailZoom = 14;
        }

        $maxTileZoom = 16;
        $data = '';
        $numberOfTiles = 0;
        $tileWeight = 0;

        $singleTileQuery = 'SELECT x, y, z, data' .
            ' FROM tiles' .
            ' WHERE detail_zoom = :detail_zoom AND object_type = :object_type AND osm_key = :osm_key' .
            ' AND osm_value = :osm_value AND x = :x AND y = :y AND z = :z';
        $multiTileQuery = 'SELECT x, y, z, data' .
            ' FROM tiles' .
            ' WHERE detail_zoom = :detail_zoom AND object_type = :object_type AND osm_key = :osm_key' .
            ' AND osm_value = :osm_value AND x >= :x_min AND x < :x_max  AND y >= :y_min AND y < :y_max AND z = :z';

        for ($queryZ = 0; $queryZ <= $maxTileZoom; $queryZ++) {
            $rows = null;

            if ($queryZ <= $z) {
                $queryX = $x >> ($z - $queryZ);
                $queryY = $y >> ($z - $queryZ);

                $singleTileQueryParams = [
                    'detail_zoom' => $detailZoom,
                    'object_type' => $type,
                    'osm_key' => $key,
                    'osm_value' => $value,
                    'x' => $queryX,
                    'y' => $queryY,
                    'z' => $queryZ
                ];

                $statement = $this->db->prepare($singleTileQuery);
                $statement->setFetchMode(\PDO::FETCH_ASSOC);
                $statement->execute($singleTileQueryParams);

                $rows = $statement->fetchAll();
            } else {
                $queryLeftX = $x << ($queryZ - $z);
                $queryTopY = $y << ($queryZ - $z);

                $queryRightX = $queryLeftX + (1 << ($queryZ - $z));
                $queryBottomY = $queryTopY + (1 << ($queryZ - $z));

                $multiTileQueryParams = [
                    'detail_zoom' => $detailZoom,
                    'object_type' => $type,
                    'osm_key' => $key,
                    'osm_value' => $value,
                    'x_min' => $queryLeftX,
                    'x_max' => $queryRightX,
                    'y_min' => $queryTopY,
                    'y_max' => $queryBottomY,
                    'z' => $queryZ
                ];

                $statement = $this->db->prepare($multiTileQuery);
                $statement->setFetchMode(\PDO::FETCH_ASSOC);
                $statement->execute($multiTileQueryParams);

                $rows = $statement->fetchAll();
            }

            if (count($rows) > 0) {
                $tileWeight += pow(4, $maxTileZoom - $queryZ);

                foreach ($rows as $row) {
                    $dataLength = (isset($row['data']) && is_string($row['data'])) ? strlen($row['data']) : 0;

                    $data .= pack("L", $row['x']);
                    $data .= pack("L", $row['y']);
                    $data .= pack("L", $row['z']);
                    $data .= pack("L", $detailZoom);
                    $data .= pack("L", $dataLength);

                    if ($dataLength > 0) {
                        $data .= $row['data'];
                    }

                    $numberOfTiles++;
                }
            }

            if ($tileWeight >= pow(4, $maxTileZoom - $z)) {
                break;
            }
        }

        return pack("L", $numberOfTiles) . $data;
    }
}
