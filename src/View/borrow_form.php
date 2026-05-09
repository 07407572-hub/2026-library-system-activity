<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Service\LibraryService;
use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;

$database = DatabaseConfig::getInstance();
$libraryService = new LibraryService($database);

$message = '';
$messageType = '';

if(isset($_POST['borrowBook']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $studentId = (int)$_POST['student_id'];
        $bookId = (int)$_POST['book_id'];
        $borrowDays = (int)$_POST['borrow_days'];

        $result = $libraryService->borrowBook($studentId, $bookId, $borrowDays);

        if($result){
            $_SESSION['message'] = 'Book borrowed successfully';
            $_SESSION['messageType'] = 'success';
        }else{
            $_SESSION['message'] = 'Failed to borrow book';
            $_SESSION['messageType'] = 'error';     
        }
    }catch(DatabaseException $e){
        $_SESSION['message'] = 'Error: ' . $e->getMessage();
        $_SESSION['messageType'] = 'error';   
    }catch(\Exception $e){
        $_SESSION['message'] = 'An unexpected error occurred';
        $_SESSION['messageType'] = 'error';   
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    $messageType = $_SESSION['messageType'];
    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
    crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Borrow Book</h4>
                    </div>
                    <div class="card-body">
                        <?php if($message): ?>
                            <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="number" class="form-control" 
                                name="student_id" id="student_id" required>
                            </div>

                            <div class="mb-3">
                                <label for="book_id" class="form-label">Book ID</label>
                                <input type="number" class="form-control" 
                                name="book_id" id="book_id" required>
                            </div>

                            <div class="mb-3">
                                <label for="borrow_days" class="form-label">Days to Borrow</label>
                                <input type="number" class="form-control" 
                                name="borrow_days" id="borrow_days" min="1" value="7" required>
                            </div>

                            <button type="submit" name="borrowBook" class="btn btn-warning w-100">Borrow Book</button>
                        </form>
                    </div>
                </div>

                <a href="../../public/index.php" class="btn btn-primary mt-3 w-100">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>