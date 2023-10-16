<?php

declare(strict_types=1);

namespace UnitTests;

use Locr\Lib\Vms2TileDbReader\Exceptions\SourceDbNotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SourceDbNotFoundException::class)]
final class SourceDbNotFoundExceptionTest extends TestCase
{
    public function testSourceDbNotFoundException(): void
    {
        $this->expectExceptionMessage('my_db.sql filename not found');

        $exception = new SourceDbNotFoundException('my_db.sql', 'my_db.sql filename not found.');
        $this->assertEquals('my_db.sql', $exception->getDatabase());

        throw $exception;
    }
}
