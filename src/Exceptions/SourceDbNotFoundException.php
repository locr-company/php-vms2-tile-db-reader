<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader\Exceptions;

class SourceDbNotFoundException extends \Exception
{
    public function __construct(
        private string $database,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getDatabase(): string
    {
        return $this->database;
    }
}
