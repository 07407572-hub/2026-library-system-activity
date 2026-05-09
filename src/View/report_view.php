<?php
declare(strict_types=1);

require_once __DIR__ . '../../../vendor/autoload.php';

use App\Service\LibraryService;
use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;
use App\Repository\BorrowRepository;

$database = DatabaseConfig::getInstance();
$libraryService = new LibraryService($database);
$borrowRepository = new BorrowRepository($database);

$error_message = '';
$report = [];
$borrowRecords = [];

try {
    $report = $libraryService->generateReport();
    $borrowRecords = $borrowRepository->getBorrowRecords();
} catch (DatabaseException $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Library Report</h2>

        <?php if($error_message): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Books</h5>
                        <h3><?php echo htmlspecialchars((string)($report['totalBooks'] ?? 0)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h5 class="card-title">Currently Borrowed</h5>
                        <h3><?php echo htmlspecialchars((string)($report['totalBorrowed'] ?? 0)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Returned Books</h5>
                        <h3><?php echo htmlspecialchars((string)($report['totalReturned'] ?? 0)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Fines</h5>
                        <h3>$<?php echo htmlspecialchars((string)($report['totalFines'] ?? 0)); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrow Records Table -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Borrow Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Record ID</th>
                                <th>Student ID</th>
                                <th>Book ID</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Fine Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($borrowRecords)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No borrow records found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($borrowRecords as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars((string)($record['record_id'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars((string)($record['student_id'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars((string)($record['book_id'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars($record['borrow_date'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($record['due_date'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($record['return_date'] ?? '-'); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($record['status'] ?? '') === 'borrowed' ? 'bg-warning text-dark' : 'bg-success'; ?>">
                                                <?php echo htmlspecialchars($record['status'] ?? 'Unknown'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $record['fine_amount'] > 0 ? '$' . htmlspecialchars((string)$record['fine_amount']) : '-'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="../../public/index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>