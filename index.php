<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Login - HR & OE Admin</title>
    <link href="src/css/styles.css" rel="stylesheet"/>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
<?php
require_once 'src/models/Account.php';

use models\Account;

try {
    Account::initialize();
} catch (Exception $e) {
    // Handle any errors here, e.g., log them and notify the user
    echo '<p style="color:red; text-align:center;">An error occurred while setting up the database. Please contact the administrator.</p>';
    // Optionally log the error message
    error_log($e->getMessage());
}

session_start();
$errorMessage = $_SESSION['error'] ?? null;
if ($errorMessage) {
    unset($_SESSION['error']);
}

$savedUsername = isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : '';
$savedPassword = isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : '';
?>

<div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if ($errorMessage) echo htmlspecialchars($errorMessage); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        <?php if ($errorMessage): ?>
        $('#errorModal').modal('show');
        <?php endif; ?>
    });
</script>

<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                            <div class="card-body">
                                <form action="src/login.php" method="POST">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="username" id="username" type="text"
                                               placeholder="Username" value="<?php echo $savedUsername; ?>" required/>
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="password" id="password" type="password"
                                               placeholder="Password" value="<?php echo $savedPassword; ?>" required/>
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" id="inputRememberPassword" name="remember"
                                               type="checkbox"
                                               value="1" <?php if ($savedUsername) echo 'checked'; ?>/>
                                        <label class="form-check-label" for="inputRememberPassword">Remember
                                            Password</label>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <button class="btn btn-primary" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-3">
                                <div class="small"><p>Forgot your password? Ask the admin.</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include 'src/includes/footer.php'; ?>
</div>
</body>
</html>