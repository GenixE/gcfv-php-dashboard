<?php
include '../includes/session.php';
require_once '../models/Account.php';
require_once '../config/Database.php';

use models\Account;

if (!isset($_GET['id'])) {
    header("Location: ../html/accounts.php");
    exit;
}

$acc_id = $_GET['id'];
$selected_acc = getSelectedAccount($acc_id);

if (!$selected_acc) {
    header("Location: ../error/404.php");
    exit;
}

$acc_id = htmlspecialchars($selected_acc->acc_id ?? 'N/A');
$username = htmlspecialchars($selected_acc->username ?? 'N/A');
$is_admin = htmlspecialchars($selected_acc->is_admin ?? 'N/A');
$last_login = htmlspecialchars($selected_acc->last_login ?? 'N/A');
$last_logout = htmlspecialchars($selected_acc->last_logout ?? 'N/A');
$account_created = htmlspecialchars($selected_acc->account_created ?? 'N/A');


function getSelectedAccount($acc_id)
{
    $accounts = Account::all();
    foreach ($accounts as $account) {
        if ($account->acc_id == $acc_id) {
            return $account;
        }
    }
    return null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Account details</title>
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
                <h1 class="mt-4">Account <?php echo htmlspecialchars($acc_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/accounts.php">Accounts table</a></li>
                    <li class="breadcrumb-item active">Account details</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Details
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <tbody>
                            <tr>
                                <th>Account ID</th>
                                <td><?php echo $acc_id; ?></td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td><?php echo $username; ?></td>
                            </tr>
                            <tr>
                                <th>Admin Status</th>
                                <td><?php echo $is_admin ? 'Yes' : 'No'; ?></td>
                            </tr>
                            <tr>
                                <th>Last Login</th>
                                <td><?php echo $last_login; ?></td>
                            </tr>
                            <tr>
                                <th>Last Logout</th>
                                <td><?php echo $last_logout; ?></td>
                            </tr>
                            <tr>
                                <th>Account Creation</th>
                                <td><?php echo $account_created; ?></td>
                            </tr>
                            </tbody>
                        </table>
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