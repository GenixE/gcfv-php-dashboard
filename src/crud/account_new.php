<?php

include '../includes/session.php';
require_once '../models/Account.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Account;
use Faker\Factory as Faker;

$errorMessage = '';

// Retrieve the current logged-in user's information
$current_user = Account::findByUsername($_SESSION['username']);

// Check if the user is an admin
if (!$current_user->is_admin) {
    header("Location: ../error/401.php");
    exit();
}

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $acc_id = $_POST['acc_id'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $is_admin = ($_POST['is_admin']) ? 1 : 0;

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create a new Account instance with form values
        $account = new Account(
            $acc_id,
            $username,
            $hashed_password,
            $is_admin,
            account_created: date('Y-m-d H:i:s')
        );

        // Save the account to the database
        $account->save();  // INSERT / UPDATE

        // Redirect to accounts.php with success status
        header("Location: ../html/accounts.php?status=success");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    // Log the database error message
    error_log("Database error: " . $e->getMessage());
    $errorMessage = "Database error: " . $e->getMessage();
} catch (\Exception $e) {
    $errorMessage = "An error occurred: " . $e->getMessage();
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_username = $faker->userName;
$fake_password = $faker->password;
$fake_acc_id = $faker->randomNumber();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Account</title>
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
                <h1 class="mt-4">Add a new account</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/accounts.php">Account details</a></li>
                    <li class="breadcrumb-item active">Add account</li>
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
                                <label for="acc_id">Account ID:</label>
                                <input type="number" class="form-control" id="acc_id" name="acc_id" value="<?php echo htmlspecialchars($fake_acc_id); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($fake_username); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($fake_password); ?>" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePassword()">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                            <div class="form-check">
                                <input type="hidden" name="is_admin" id="is_admin" value="0">
                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1">
                                <label class="form-check-label" for="is_admin">Make admin</label>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Add Account</button>
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

<script>
    function togglePassword() {
        const passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>