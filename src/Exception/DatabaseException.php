<?php
declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Throwable;

/**
 * Custom exception class for database-related errors
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class DatabaseException extends RuntimeException{
    /**
     * Constructor to initialize the database exception
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