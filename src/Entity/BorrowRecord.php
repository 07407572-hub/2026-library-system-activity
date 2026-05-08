<?php
declare (strict_types = 1);

namespace App\Entity;

use DateTime;
use DateInterval;

class BorrowRecord{

    private int $recordId;
    private int $bookId;
    private string $status;
    private float $fineAmount;
    private DateTime $borrow_date;
    private DateTime $return_date;

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

public function getRecordId() : int
{
    return $this->recordId;
}

public function getBookId() : int
{
    return $this->bookId;
}

public function getStatus() : String
{
    return $this->status;
}

public function getFineAmount() : float
{
    return $this->fineAmount;
}

public function getBorrowDate() : DateTime
{
    return $this->borrow_date;
}

public function getReturnDate() : DateTime
{
    return $this->return_date;
}


public function setRecordId(int $recordId)
{
    $this->recordId = $recordId;
}

public function setBookId(int $bookId)
{
    $this->bookId = $bookId;
}

public function setStatus(String $status)
{
    $this->status = $status;
}

public function setFineAmount(float $fineAmount)
{
    $this->fineAmount = $fineAmount;
}

public function setBorrowDate(DateTime $borrow_date)
{
    $this->borrow_date = $borrow_date;
}

public function setReturnDate(DateTime $return_date)
{
    $this->return_date = $return_date;
}

}

?>