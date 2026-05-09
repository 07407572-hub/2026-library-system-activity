<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\LibraryService;
use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;

$database = DatabaseConfig::getInstance();
$libraryService = new LibraryService($database);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['signup_student'])) {
            $name = $_POST['student_name'] ?? '';
            $studentId = $_POST['student_id'] ?? '';
            
            if ($libraryService->registerStudent($name, $studentId)) {
                $message = 'Student registered successfully';
                $messageType = 'success';
            } else {
                $message = 'Failed to register student';
                $messageType = 'error';
            }
        }
        elseif (isset($_POST['add_book'])) {
            $title = $_POST['book_title'] ?? '';
            $author = $_POST['book_author'] ?? '';
            $isbn = $_POST['book_isbn'] ?? '';
            
            if ($libraryService->addBook($title, $author, $isbn)) {
                $message = 'Book added successfully';
                $messageType = 'success';
            } else {
                $message = 'Failed to add book';
                $messageType = 'error';
            }
        }
        elseif (isset($_POST['return_book'])) {
            $recordId = (int)($_POST['return_record_id'] ?? 0);
            
            $fine = $libraryService->returnBook($recordId);
            $message = 'Book returned successfully. Fine: $' . number_format($fine, 2);
            $messageType = 'success';
        }
    } catch (DatabaseException $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'error';
    } catch (\Exception $e) {
        $message = 'An unexpected error occurred';
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
    crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Library System</h1>
        
        <?php if($message): ?>
            <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Student Sign Up -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Student Sign Up</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" 
                        name="student_name" placeholder="Enter student name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text" class="form-control" 
                        name="student_id" placeholder="Enter student ID" required>
                    </div>
                    <button type="submit" name="signup_student" class="btn btn-primary">Sign Up</button>
                </form>
            </div>
        </div>

        <!-- Add Book -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Add Book</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Book Title</label>
                        <input type="text" class="form-control" 
                        name="book_title" placeholder="Enter book title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" class="form-control" 
                        name="book_author" placeholder="Enter author name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ISBN</label>
                        <input type="text" class="form-control" 
                        name="book_isbn" placeholder="Enter ISBN" required>
                    </div>
                    <button type="submit" name="add_book" class="btn btn-success">Add Book</button>
                </form>
            </div>
        </div>

        <!-- Return Book -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Return Book</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Borrow Record ID</label>
                        <input type="number" class="form-control" 
                        name="return_record_id" placeholder="Enter borrow record ID" required>
                    </div>
                    <button type="submit" name="return_book" class="btn btn-info">Return Book</button>
                </form>
            </div>
        </div>

        <div class="text-center">
            <a href="../src/View/Book_list.php" class="btn btn-secondary">View Book List</a>
            <a href="../src/View/Borrow_form.php" class="btn btn-secondary">Borrow Form</a>
            <a href="../src/View/report_view.php" class="btn btn-secondary">View Report</a>
        </div>
    </div>
    


</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</html>