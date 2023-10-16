<?php

declare(strict_types=1);

namespace Locr\Lib\Vms2TileDbReader\Exceptions;

class InvalidTypeException extends \Exception
{
    public function __construct(
        private string $type,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
