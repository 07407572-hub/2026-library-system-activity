<?php
declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;
use Throwable;
use Override;

class ValidationException extends InvalidArgumentException
{
    #[Override]
    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
?>