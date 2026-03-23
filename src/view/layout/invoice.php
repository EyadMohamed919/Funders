<?php
require_once __DIR__ . "/../../controller/InvoiceController.php";

$invoiceController = new InvoiceController();
$invoices = $invoiceController->getInvoice();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoices</title>
    <link rel="stylesheet" href="../../../public/css/adminDashboardStyle.css">
</head>

<body>
<section class="ftco-section">
    <div class="container">
        <h4 class="text-center mb-4">Create New Invoice</h4>
        <form action="../../router/InvoiceRouter.php" method="post" class="mb-4">
            <input type="hidden" name="router" value="create">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="number" name="user_id" id="user_id" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" name="amount" id="amount" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" name="due_date" id="due_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Invoice</button>
        </form>

        <h4 class="text-center mb-4">Invoice Table</h4>

        <div class="table-wrap">
            <table class="table">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (is_array($invoices)): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($invoice['invoice_id']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['invoice_amount']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['invoice_status']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['invoice_created_at']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['invoice_due_date']); ?></td>
                            <td>
                                <?php if ($invoice['invoice_status'] !== 'paid'): ?>
                                    <a href="../../router/InvoiceRouter.php?router=update&invoice_id=<?php echo $invoice['invoice_id']; ?>&status=paid" class="btn btn-success">Mark as Paid</a>
                                <?php endif; ?>
                                <a href="../../router/InvoiceRouter.php?router=delete&invoice_id=<?php echo $invoice['invoice_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No invoices found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
</body>
</html>
