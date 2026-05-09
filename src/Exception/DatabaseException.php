<?php
declare(strict_types=1);

namespace App\Exception;

use Override;
use RuntimeException;
use Throwable;

class DatabaseException extends RuntimeException{
    #[Override]
    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

?>