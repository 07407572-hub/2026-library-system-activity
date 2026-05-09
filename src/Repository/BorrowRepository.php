<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\DatabaseConfig;

class BorrowRepository
{
    private $connection;
    private $dailyFineRate = 5;
    
    public function __construct(DatabaseConfig $database)
    {
        $this->connection = $database->getConnection();
    }

    public function borrowBook(string $studentId, int $bookId, int $days): bool
    {
        $due = date('Y-m-d', strtotime('+' . $days . 'days'));

        $sql = "SELECT id FROM students WHERE student_id = :student_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':student_id' => $studentId]);
        $student = $stmt->fetch();

        if (!$student) {
            throw new \Exception("Student with ID {$studentId} not found in database");
        }

        $actualStudentId = $student['id'];

        $sql = "INSERT INTO borrow_records(student_id, book_id, borrow_date, due_date, status)
                VALUES(:student_id, :book_id, :borrow_date, :due_date, 'borrowed')";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':student_id' => $actualStudentId,
            ':book_id' => $bookId,
            ':borrow_date' => date('Y-m-d'),
            ':due_date' => $due
        ]);

        $sql2 = "UPDATE books SET status = 'borrowed' WHERE book_id = :book_id";
        $stmt2 = $this->connection->prepare($sql2);
        $stmt2->execute([':book_id' => $bookId]);

        return $stmt->rowCount() > 0;
    }

    public function returnBook(int $recordId): float
    {
        $sql = "SELECT * FROM borrow_records WHERE record_id = :record_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':record_id' => $recordId]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return 0;
        }
        
        $due = strtotime($result['due_date']);
        $today = strtotime(date('Y-m-d'));
        $diff = ($today - $due) / (60 * 60 * 24);
        $fine = 0;

        if ($diff > 0) {
            $fine = $diff * $this->dailyFineRate;
        }

        $sql2 = "UPDATE borrow_records 
                 SET return_date = :return_date, fine_amount = :fine_amount, status = 'returned'
                 WHERE record_id = :record_id";
        $stmt2 = $this->connection->prepare($sql2);
        $stmt2->execute([
            ':return_date' => date('Y-m-d'),
            ':fine_amount' => $fine,
            ':record_id' => $recordId
        ]);

        $sql3 = "UPDATE books SET status = 'available' WHERE book_id = :book_id";
        $stmt3 = $this->connection->prepare($sql3);
        $stmt3->execute([':book_id' => $result['book_id']]);

        return $fine;
    }

    public function getBorrowRecords(): array
    {
        $sql = "SELECT * FROM borrow_records ORDER BY borrow_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActiveBorrows(): array
    {
        $sql = "SELECT * FROM borrow_records WHERE status = 'borrowed' ORDER BY borrow_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}