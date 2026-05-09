<?php
declare (strict_types = 1);

namespace App\Entity;

use DateTime;
use DateInterval;

/**
 * Entity class representing a book borrowing record
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class BorrowRecord{

    private int $recordId;
    private int $bookId;
    private string $status;
    private float $fineAmount;
    private DateTime $borrow_date;
    private DateTime $return_date;

/**
 * Constructor to initialize a BorrowRecord entity
 *
 * @param int $recordId The borrow record ID
 * @param int $bookId The book ID
 * @param string $status The borrow status
 * @param float $fineAmount The fine amount
 * @param DateTime $borrow_date The borrow date
 * @param DateTime $return_date The return date
 */
public function __construct(
    int $recordId, int $bookId,  
    string $status, float $fineAmount, 
    DateTime $borrow_date, DateTime $return_date)
{
    $this->recordId = $recordId;
    $this->bookId = $bookId;
    $this->status = $status;
    $this->fineAmount = $fineAmount;
    $this->borrow_date = $borrow_date;
    $this->return_date = $return_date;
}

/**
 * Get the record ID
 *
 * @return int The record ID
 */
public function getRecordId() : int
{
    return $this->recordId;
}

/**
 * Get the book ID
 *
 * @return int The book ID
 */
public function getBookId() : int
{
    return $this->bookId;
}

/**
 * Get the borrow status
 *
 * @return String The borrow status
 */
public function getStatus() : String
{
    return $this->status;
}

/**
 * Get the fine amount
 *
 * @return float The fine amount
 */
public function getFineAmount() : float
{
    return $this->fineAmount;
}

/**
 * Get the borrow date
 *
 * @return DateTime The borrow date
 */
public function getBorrowDate() : DateTime
{
    return $this->borrow_date;
}

/**
 * Get the return date
 *
 * @return DateTime The return date
 */
public function getReturnDate() : DateTime
{
    return $this->return_date;
}


/**
 * Set the record ID
 *
 * @param int $recordId The record ID
 */
public function setRecordId(int $recordId)
{
    $this->recordId = $recordId;
}

/**
 * Set the book ID
 *
 * @param int $bookId The book ID
 */
public function setBookId(int $bookId)
{
    $this->bookId = $bookId;
}

/**
 * Set the borrow status
 *
 * @param String $status The borrow status
 */
public function setStatus(String $status)
{
    $this->status = $status;
}

/**
 * Set the fine amount
 *
 * @param float $fineAmount The fine amount
 */
public function setFineAmount(float $fineAmount)
{
    $this->fineAmount = $fineAmount;
}

/**
 * Set the borrow date
 *
 * @param DateTime $borrow_date The borrow date
 */
public function setBorrowDate(DateTime $borrow_date)
{
    $this->borrow_date = $borrow_date;
}

/**
 * Set the return date
 *
 * @param DateTime $return_date The return date
 */
public function setReturnDate(DateTime $return_date)
{
    $this->return_date = $return_date;
}

}

?>