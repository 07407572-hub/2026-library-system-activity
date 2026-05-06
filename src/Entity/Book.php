<?php
declare (strict_types = 1);

namespace App\Entity;

use RuntimeException;
use InvalidArgumentException;
use DateTime;
use PDO;

class Book{

private ?PDO $pdo = null;

private int $bookId;
private String $title;
private String $author;
private DateTime $year;
private String $genre;

public function __construct(int $bookId, String $title, String $author, DateTime $year, String $genre)
{
    $this->bookId = $bookId;
    $this->title = $title;
    $this->author = $author;
    $this->year = $year;
    $this->genre = $genre;
}

public function getBookId() : int
{
    return $this->bookId;
}

public function getTitle() : String
{
    return $this->title;
}

public function getAuthor() : String
{
    return $this->author;
}

public function getYear() : DateTime
{
    return $this->year;
}

public function getGenre() : String
{
    return $this->genre;
}

public function setId(int $bookId)
{
    $this->bookId = $bookId;
}

public function setTitle(String $title)
{
    $this->title = $title;
}

public function setAuthor(String $author)
{
    $this->author = $author;
}

public function setYear(DateTime $year)
{
    $this->year = $year;
}

public function setGenre(String $genre)
{
    $this->genre = $genre;
}
}
?>