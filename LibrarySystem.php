<?php
declare(strict_types = 1);

namespace App\LibrarySystem;

use RuntimeException;
use InvalidArgumentException;
use PDO;

class LibrarySystem{

    private ?PDO $pdo = null;

    public $host = "localhost"; 
    public $username = "root"; 
    public $password = ""; 
    public $dbname = "library_db";
    public $dailyFineRate=5;


function connect()
{
    $this->pdo = new \PDO($this->host, $this->username, $this->password, $this->dbname);

    if($this->pdo->connect_error){
        throw new RuntimeException(
            'Database connection Failed: ' . $this->pdo->connect_error
        );
    }
}

function addBook(String $title, String $author, String $year, String $genre)
{
    $sql = "INSERT INTO books(title, author, year, genre) VALUES('".$title."','".$author."',".$year.",'".$genre."')";
    $this->pdo->query($sql); 

    return $this->pdo->insert_id;
}

function getBook(int $id)
{
    $sql = "SELECT * FROM books WHERE book_id = ?";
    $result = $this->pdo->query($sql); 
    
    return $result->fetch();
}


function borrowBook(String $studentId, String $bookId, String $days)
{
    $due = date('Y-m-d',strtotime('+'. $days .' days'));
    $sql = "INSERT INTO borrow_records(student_id, book_id, borrow_date, due_date, status) 
        VALUES(". $studentId .",". $bookId .",'".date('Y-m-d')."','". $due ."','borrowed')";

    $this->pdo->prepare($sql); 
    return true;
}

function returnBook(String $recordId)
{
    $sql = "SELECT * FROM borrow_records WHERE record_id=" . $recordId;
    $result = $this->pdo->prepare($sql)->fetch();
    $due = strtotime($result['due_date']); 
    $today = strtotime(date('Y-m-d'));
    $diff = ($today-$due)/(60*60*24); 
    $fine = 0;

    if($diff>0)
    {
        $fine = $diff*$this->dailyFineRate;
    }

    $sql2 = "UPDATE borrow_records SET return_date='".date('Y-m-d')."', 
    fine_amount=". $fine .", status='returned' WHERE record_id=".$recordId;
    $this->pdo->prepare($sql2); 

    return $fine;
}

function listBooks()
{
    $sql = "SELECT * FROM books";
    $result = $this->pdo->prepare($sql);
    echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Author</th><th>Year</th><th>Genre</th></tr>";
    while($row = $result->fetch())
    {
        echo "<tr><td>".$row['book_id']."</td><td>".$row['title']."</td><td>".$row['author']."</td><td>".$row['year']."</td><td>".$row['genre']."</td></tr>";
    }
    echo "</table>";
}


function searchBooks($kw)
{
    $sql = "SELECT * FROM books WHERE title LIKE '%".$kw."%' OR author LIKE '%".$kw."%'";
    $result = $this->pdo->prepare($sql); 
    $books = array();

    while($row=$result->fetch())
    {
        $books[]=$row;
    } 
    return $books;       
}

function getOverdueBooks()
{
    $sql="SELECT br.*, b.title, s.name FROM borrow_records br 
    JOIN books b ON br.book_id=b.book_id 
    JOIN students s ON br.student_id=s.student_id 
    WHERE br.due_date<'".date('Y-m-d')."' AND br.status='borrowed'";

    $result=$this->pdo->prepare($sql); 
    $list=array();

    while($row=$result->fetch())
    {
        $list[]=$row;
    }
    return $list;
}

function generateReport()
{
    $totalBooks = $this->pdo->query("SELECT COUNT(*) as c FROM books")->fetch()['c'];

    $totalBorrowed = $this->pdo->query("SELECT COUNT(*) as c FROM borrow_records 
    WHERE status='borrowed'")->fetch()['c'];

    $totalReturned = $this->pdo->query("SELECT COUNT(*) as c FROM borrow_records 
    WHERE status='returned'")->fetch()['c'];

    $totalFines = $this->pdo->query("SELECT SUM(fine_amount) as s FROM borrow_records 
    WHERE fine_amount>0")->fetch()['s'];

    echo "<h2>Library Report</h2>";
    echo "<p>Total Books: ". $totalBooks ."</p>";
    echo "<p>Borrowed: ". $totalBorrowed ."</p>";
    echo "<p>Returned: ". $totalReturned ."</p>";
    echo "<p>Total Fines Collected: $". $totalFines ."</p>";

}
}

$library = new LibrarySystem();
$library->connect();

    if(isset($_GET['act']))
    {
        if($_GET['act']=='add')
        {
            $library->addBook($_POST['t'],$_POST['a'],$_POST['y'],$_POST['g']);
        }
        elseif($_GET['act']=='list')
        {
            $library->listBooks();
        }
    elseif($_GET['act']=='report')
    {
        $library->generateReport();
    }
}
?>