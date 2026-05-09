<?php
declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * Custom exception class for validation errors
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class ValidationException extends InvalidArgumentException
{
    /**
     * Constructor to initialize the validation exception
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Throwable|null $previous The previous throwable
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}