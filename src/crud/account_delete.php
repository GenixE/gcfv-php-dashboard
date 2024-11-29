<?php

include '../includes/session.php';

require_once '../models/Account.php';
require_once '../config/Database.php';

use Models\Account;

// Retrieve the current logged-in user's information
$current_user = Account::findByUsername($_SESSION['username']);

// Check if the user is an admin
if (!$current_user->is_admin) {
    header("Location: ../error/403.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../html/accounts.php");
    exit;
}

$acc_id = $_GET['id'];
$selected_account = getSelectedAccount($acc_id);

if (!$selected_account) {
    header("Location: ../error/404.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $account = new Account($acc_id);
        $account->destroy();
        header("Location: ../html/accounts.php?status=success");
        exit;
    } catch (Exception $e) {
        echo "An error occurred: " . "<br>" . $e->getMessage();
    }
}

function getSelectedAccount($acc_id)
{
    $accounts = Account::all();
    foreach ($accounts as $account) {
        if ($account->acc_id == $acc_id) { // Make sure Account model uses acc_id
            return $account;
        }
    }
    return null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="sb-nav-fixed">
<?php include '../includes/topnav.php'; ?>
<div id="layoutSidenav">
    <?php include '../includes/sidenav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Remove Account <?php echo htmlspecialchars($acc_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/accounts.php">Accounts table</a></li>
                    <li class="breadcrumb-item active">Remove Account</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Remove Account
                    </div>
                    <div class="card-body">
                        <p>Are you sure you want to delete
                            account <?php echo htmlspecialchars($selected_account->username); ?>
                            ?</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal">Delete
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="post" action="account_delete.php?id=<?php echo htmlspecialchars($acc_id); ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/scripts.php'; ?>
</body>
</html>