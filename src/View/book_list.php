<?php
declare(strict_types=1);
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Repository\BookRepository;
use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;

$database = DatabaseConfig::getInstance();
$bookrepo = new BookRepository($database);

$bookList = [];

try{
    $bookList = $bookrepo->listBooks();
}catch(DatabaseException $error){
    $error_message = $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
    crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Book List</h2>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Genre</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($bookList)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No books found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($bookList as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['book_id'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($book['title'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($book['author'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($book['year'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($book['genre'] ?? ''); ?></td>
                                <td>
                                    <span class="badge <?php echo ($book['status'] ?? '') === 'available' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($book['status'] ?? 'Unknown'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <a href="../../public/index.php" class="btn btn-primary mt-3">Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>