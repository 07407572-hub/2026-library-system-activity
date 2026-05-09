<?php
declare (strict_types = 1);

namespace App\Repository;

use App\Config\DatabaseConfig;

class BookRepository
{
    
    private $connection;

private $dailyFineRate = 5;
    
    public function __construct(DatabaseConfig $database)
    {
        $this->connection = $database->getConnection();
    }
    
    function addBook(String $title, String $author, String $year, String $genre)
    {
        $sql = "INSERT INTO books(title, author, year, genre) VALUES('". $title ."','". $author ."',". $year .",'". $genre ."')";
        $result = $this->connection->query($sql); 

        return $this->connection->$result;
    }

    function getBook(int $id)
    {
        $sql = "SELECT * FROM books WHERE book_id = ?";
        $result = $this->connection->query($sql); 
        
        return $result->fetch();
    }

    
    public function listBooks(): array
    {
        $sql = "SELECT b.*, 
                CASE 
                    WHEN br.record_id IS NOT NULL AND br.status = 'borrowed' THEN 'borrowed'
                    ELSE 'available'
                END as status
                FROM books b
                LEFT JOIN borrow_records br ON b.book_id = br.book_id AND br.status = 'borrowed'
                ORDER BY b.book_id DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    function borrowBook(String $studentId, String $bookId, String $days)
    {
        $due = date('Y-m-d', strtotime('+'. $days .' days'));
        $sql = "INSERT INTO borrow_records(student_id, book_id, borrow_date, due_date, status) 
            VALUES(". $studentId .",". $bookId .",'". date('Y-m-d')."','". $due ."','borrowed')";

        $this->connection->prepare($sql); 
        return true;
    }

    function returnBook(String $recordId)
    {
        $sql = "SELECT * FROM borrow_records WHERE record_id =" . $recordId;
        $result = $this->connection->prepare($sql)->fetch();
        $due = strtotime($result['due_date']); 
        $today = strtotime(date('Y-m-d'));
        $diff = ($today-$due) / (60*60*24); 
        $fine = 0;

        if($diff>0)
        {
            $fine = $diff * $this->dailyFineRate;
        }

            $sql2 = "UPDATE borrow_records SET return_date='" . date('Y-m-d')."', 
            fine_amount=". $fine .", status='returned' WHERE record_id=". $recordId;
            $this->connection->prepare($sql2); 

            return $fine;
    }

}
?>