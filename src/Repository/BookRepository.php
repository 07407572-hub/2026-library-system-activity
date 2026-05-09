<?php
declare (strict_types = 1);

namespace App\Repository;

use App\Config\DatabaseConfig;

/**
 * Repository class for book-related database operations
 * Handles CRUD operations for books in the library system
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class BookRepository
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
     * Add a new book to the database
     *
     * @param String $title The book title
     * @param String $author The book author
     * @param String $year The publication year
     * @param String $genre The book genre
     * @return mixed The result of the query
     */
    function addBook(String $title, String $author, String $year, String $genre)
    {
        $sql = "INSERT INTO books(title, author, year, genre) VALUES('". $title ."','". $author ."',". $year .",'". $genre ."')";
        $result = $this->connection->query($sql);

        return $this->connection->$result;
    }

    /**
     * Retrieve a book by its ID
     *
     * @param int $id The book ID
     * @return mixed The book data
     */
    function getBook(int $id)
    {
        $sql = "SELECT * FROM books WHERE book_id = ?";
        $result = $this->connection->query($sql);

        return $result->fetch();
    }


    /**
     * Retrieve all books from the database
     *
     * @return array List of all books ordered by ID descending
     */
    public function listBooks(): array
    {
        $sql = "SELECT * FROM books ORDER BY book_id DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /**
     * Borrow a book (deprecated - use BorrowRepository instead)
     *
     * @param String $studentId The student ID
     * @param String $bookId The book ID
     * @param String $days Number of days to borrow
     * @return bool True if successful
     */
    function borrowBook(String $studentId, String $bookId, String $days)
    {
        $due = date('Y-m-d', strtotime('+'. $days .' days'));
        $sql = "INSERT INTO borrow_records(student_id, book_id, borrow_date, due_date, status)
            VALUES(". $studentId .",". $bookId .",'". date('Y-m-d')."','". $due ."','borrowed')";

        $this->connection->prepare($sql);
        return true;
    }

    /**
     * Return a book and calculate fine (deprecated - use BorrowRepository instead)
     *
     * @param String $recordId The borrow record ID
     * @return float The fine amount
     */
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