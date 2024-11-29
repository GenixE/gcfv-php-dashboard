<?php

include '../includes/session.php';
require_once '../models/Account.php';
require_once '../config/Database.php';

use Models\Account;

if (!isset($_GET['id'])) {
    header("Location: ../html/customers.php");
    exit;
}

$acc_id = $_GET['id'];

// Retrieve the current logged-in user's information
$current_user = Account::findByUsername($_SESSION['username']);

// Check if the user is an admin or the owner of the account
if (!$current_user->is_admin && $current_user->acc_id != $acc_id) {
    header("Location: ../error/403.php");
    exit();
}

// Helper function to fetch account by ID
function getAccountById($acc_id)
{
    $accounts = Account::all(); // Assuming the Account class has a static method to get all accounts
    foreach ($accounts as $account) {
        if ($account->acc_id == $acc_id) {
            return $account;
        }
    }
    return null;
}

$selected_account = getAccountById($acc_id);
$errorMessage = '';

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $previous_password = $_POST['previous_password'];
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        // Verify previous password
        $current_account = getAccountById($acc_id);
        if (!$current_account || !password_verify($previous_password, $current_account->password)) {
            $errorMessage = "Previous password is incorrect.";
        } elseif (!empty($password) && $password !== $confirm_password) {
            // Check if new password and confirm password match
            $errorMessage = "New password and confirm password do not match.";
        } else {
            // Hash the new password if provided
            $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $current_account->password;

            // Create a new Account instance with form values
            $account = new Account(
                $acc_id,
                $username,
                $hashed_password,
                $is_admin
            );

            // Save the account to the database
            $account->save();  // INSERT / UPDATE

            // Destroy the session and redirect to login page
            session_destroy();
            header("Location: ../../index.php");
            exit;
        }
    }
} catch (mysqli_sql_exception $e) {
    // Log the database error message
    error_log("Database error: " . $e->getMessage());

    // Display the database error message in the console
    echo "<script>console.error('Database error: " . addslashes($e->getMessage()) . "');</script>";

    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: ../index.php");
    exit;
} catch (\Exception $e) {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Account</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet"/>
    <link href="../css/styles.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body class="sb-nav-fixed">
<?php include '../includes/topnav.php'; ?>
<div id="layoutSidenav">
    <?php include '../includes/sidenav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Update Account</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/accounts.php">Account details</a></li>
                    <li class="breadcrumb-item active">Update account</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Account details
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <?php if ($errorMessage): ?>
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <li><?php echo htmlspecialchars($errorMessage); ?></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?php echo htmlspecialchars($selected_account->username ?? ''); ?>"
                                       required>
                            </div>
                            <?php if ($current_user->is_admin): ?>
                                <div class="form-group">
                                    <label for="is_admin">Make Admin:</label>
                                    <input type="checkbox" id="is_admin"
                                           name="is_admin" <?php echo $selected_account->is_admin ? 'checked' : ''; ?>>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="password">New Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password:</label>
                                <input type="password" class="form-control" id="confirm_password"
                                       name="confirm_password">
                            </div>
                            <div class="form-group">
                                <label for="previous_password">Current Password:</label>
                                <input type="password" class="form-control" id="previous_password"
                                       name="previous_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>

<?php include '../includes/scripts.php'; ?>
</body>
</html>