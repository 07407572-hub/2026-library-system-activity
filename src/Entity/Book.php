<?php
declare (strict_types = 1);

namespace App\Entity;

use RuntimeException;
use InvalidArgumentException;
use DateTime;
use PDO;

/**
 * Entity class representing a book in the library system
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class Book{

private ?PDO $pdo = null;

private int $bookId;
private String $title;
private String $author;
private DateTime $year;
private String $genre;

/**
 * Constructor to initialize a Book entity
 *
 * @param int $bookId The book ID
 * @param String $title The book title
 * @param String $author The book author
 * @param DateTime $year The publication year
 * @param String $genre The book genre
 */
public function __construct(int $bookId, String $title, String $author, DateTime $year, String $genre)
{
    $this->bookId = $bookId;
    $this->title = $title;
    $this->author = $author;
    $this->year = $year;
    $this->genre = $genre;
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
 * Get the book title
 *
 * @return String The book title
 */
public function getTitle() : String
{
    return $this->title;
}

/**
 * Get the book author
 *
 * @return String The book author
 */
public function getAuthor() : String
{
    return $this->author;
}

/**
 * Get the publication year
 *
 * @return DateTime The publication year
 */
public function getYear() : DateTime
{
    return $this->year;
}

/**
 * Get the book genre
 *
 * @return String The book genre
 */
public function getGenre() : String
{
    return $this->genre;
}
}
?>