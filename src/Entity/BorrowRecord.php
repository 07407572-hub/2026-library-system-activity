<?php
declare (strict_types = 1);

namespace App\Entity;

use DateTime;
use DateInterval;

class BorrowRecord{

    private int $book_id;
    private int $record_id;
    private string $status;
    private float $fineAmount;
    private DateTime $borrow_date;
    private DateTime $return_date;

public function __construct()
{

}

public function setBookId(int $book_id)
{
    $this->book_id = $book_id;
}

public function setRecordId(int $record_id)
{
    $this->record_id = $record_id;
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

public function getBookId() : int
{
    return $this->book_id;
}

public function getRecordId() : int
{
    return $this->record_id;
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


}

?>