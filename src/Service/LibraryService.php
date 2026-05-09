<?php
declare(strict_types=1);

namespace App\Service;

use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;

/**
 * Service class for library business logic
 * Orchestrates operations between repositories and handles high-level library operations
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class LibraryService
{
    private $connection;
    private $bookRepository;
    private $borrowRepository;

    /**
     * Constructor to initialize the service with database connection and repositories
     *
     * @param DatabaseConfig $database The database configuration instance
     */
    public function __construct(DatabaseConfig $database)
    {
        $this->connection = $database->getConnection();
        $this->bookRepository = new BookRepository($database);
        $this->borrowRepository = new BorrowRepository($database);
    }

    /**
     * Add a new book to the library
     *
     * @param string $title The book title
     * @param string $author The book author
     * @param string $isbn The book ISBN
     * @return bool True if successful
     * @throws DatabaseException If database operation fails
     */
    public function addBook(string $title, string $author, string $isbn): bool
    {
        try {
            $sql = "INSERT INTO books(title, author, isbn, status) VALUES(:title, :author, :isbn, 'available')";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':isbn' => $isbn
            ]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $error) {
            throw new DatabaseException("Failed to add book: " . $error->getMessage());
        }
    }

    /**
     * Register a new student in the library system
     *
     * @param string $name The student name
     * @param string $studentId The student ID
     * @return bool True if successful
     * @throws DatabaseException If database operation fails
     */
    public function registerStudent(string $name, string $studentId): bool
    {
        try {
            $sql = "INSERT INTO students(name, student_id) VALUES(:name, :student_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':student_id' => $studentId
            ]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $error) {
            throw new DatabaseException("Failed to register student: " . $error->getMessage());
        }
    }

    /**
     * Process a book borrowing transaction
     *
     * @param int $studentId The student ID
     * @param int $bookId The book ID
     * @param int $days Number of days to borrow
     * @return bool True if successful
     * @throws DatabaseException If database operation fails
     */
    public function borrowBook(int $studentId, int $bookId, int $days): bool
    {
        try {
            return $this->borrowRepository->borrowBook($studentId, $bookId, $days);
        } catch (\PDOException $error) {
            throw new DatabaseException("Failed to borrow book: " . $error->getMessage());
        }
    }

    /**
     * Process a book return transaction
     *
     * @param int $recordId The borrow record ID
     * @return float The calculated fine amount
     * @throws DatabaseException If database operation fails
     */
    public function returnBook(int $recordId): float
    {
        try {
            return $this->borrowRepository->returnBook($recordId);
        } catch (\PDOException $error) {
            throw new DatabaseException("Failed to return book: " . $error->getMessage());
        }
    }

    /**
     * Generate a library statistics report
     *
     * @return array Report containing total books, borrowed books, returned books, and total fines
     * @throws DatabaseException If database operation fails
     */
    public function generateReport(): array
    {
        try {
            $report = [];

            // Count total books
            $sql1 = 'SELECT COUNT(*) FROM books';
            $stmt1 = $this->connection->prepare($sql1);
            $stmt1->execute();
            $report['totalBooks'] = $stmt1->fetchColumn();

            // Count currently borrowed books
            $sql2 = "SELECT COUNT(*) FROM borrow_records WHERE status='borrowed'";
            $stmt2 = $this->connection->prepare($sql2);
            $stmt2->execute();
            $report['totalBorrowed'] = $stmt2->fetchColumn();

            // Count returned books
            $sql3 = "SELECT COUNT(*) FROM borrow_records WHERE status='returned'";
            $stmt3 = $this->connection->prepare($sql3);
            $stmt3->execute();
            $report['totalReturned'] = $stmt3->fetchColumn();

            // Calculate total fines collected
            $sql4 = "SELECT SUM(fine_amount) FROM borrow_records WHERE fine_amount > 0";
            $stmt4 = $this->connection->prepare($sql4);
            $stmt4->execute();
            $report['totalFines'] = $stmt4->fetchColumn() ?? 0;

            return $report;
        } catch (\PDOException $error) {
            throw new DatabaseException("Failed to Generate Report: " . $error->getMessage());
        }
    }
}