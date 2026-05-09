<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\DatabaseConfig;

/**
 * Repository class for borrow record operations
 * Handles borrowing, returning, and tracking books in the library system
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class BorrowRepository
{
    private $connection;
    private $dailyFineRate = 5;

    /**
     * Constructor to initialize the repository with database connection
     *
     * @param DatabaseConfig $database The database configuration instance
     */
    public function __construct(DatabaseConfig $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * Record a book borrowing transaction
     *
     * @param int $studentId The student ID
     * @param int $bookId The book ID
     * @param int $days Number of days to borrow
     * @return bool True if successful
     */
    public function borrowBook(int $studentId, int $bookId, int $days): bool
    {
        $due = date('Y-m-d', strtotime('+' . $days . ' days'));
        $sql = "INSERT INTO borrow_records(student_id, book_id, borrow_date, due_date, status)
                VALUES(:student_id, :book_id, :borrow_date, :due_date, 'borrowed')";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':student_id' => $studentId,
            ':book_id' => $bookId,
            ':borrow_date' => date('Y-m-d'),
            ':due_date' => $due
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Process a book return and calculate any applicable fines
     *
     * @param int $recordId The borrow record ID
     * @return float The calculated fine amount
     */
    public function returnBook(int $recordId): float
    {
        $sql = "SELECT * FROM borrow_records WHERE record_id = :record_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':record_id' => $recordId]);
        $result = $stmt->fetch();

        if (!$result) {
            return 0;
        }

        // Calculate fine based on overdue days
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

        return $fine;
    }

    /**
     * Retrieve all borrow records
     *
     * @return array List of all borrow records ordered by date descending
     */
    public function getBorrowRecords(): array
    {
        $sql = "SELECT * FROM borrow_records ORDER BY borrow_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieve all currently borrowed books
     *
     * @return array List of active borrow records
     */
    public function getActiveBorrows(): array
    {
        $sql = "SELECT * FROM borrow_records WHERE status = 'borrowed' ORDER BY borrow_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}