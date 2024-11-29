<?php
include '../includes/session.php';
require_once '../models/Account.php';

use models\Account;

// Retrieve the current logged-in user's information
$current_user = Account::findByUsername($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Accounts</title>
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
                <h1 class="mt-4">Accounts</h1>
                <ol class="breadcrumb mb=4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Accounts</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user-plus me-1"></i>
                        Accounts registered
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <thead>
                            <tr>
                                <th>Account ID</th>
                                <th>Username</th>
                                <th>Admin</th>
                                <th>Actions
                                    <?php if ($current_user->is_admin): ?>
                                        <a href='../crud/account_new.php' class='mr-2' title='New File'
                                           data-toggle='tooltip'>
                                            <span class='fa fa-user-plus' style='margin-left: 10px;'></span>
                                        </a>
                                    <?php endif; ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            require_once '../models/Account.php';

                            if ($current_user->is_admin) {
                                $accounts = Account::all();
                            } else {
                                $accounts = [Account::findByUsername($_SESSION['username'])];
                            }

                            foreach ($accounts as $account): ?>
                                <tr>
                                    <td><?php echo $account->acc_id; ?></td>
                                    <td><?php echo $account->username; ?></td>
                                    <td><?php echo $account->is_admin ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <a href='../crud/account_view.php?id=<?php echo $account->acc_id; ?>'
                                           class='mr-2' title='View File' data-toggle='tooltip'>
                                            <span class='fa fa-circle-info' style='margin-right: 10px;'></span>
                                        </a>
                                        <a href='../crud/account_update.php?id=<?php echo $account->acc_id; ?>'
                                           class='mr-2' title='Update File' data-toggle='tooltip'>
                                            <span class='fa fa-pen-to-square' style='margin-right: 10px;'></span>
                                        </a>
                                        <?php if ($current_user->is_admin): ?>
                                            <a href='../crud/account_delete.php?id=<?php echo $account->acc_id; ?>'
                                               class='mr-2' title='Delete File' data-toggle='tooltip'>
                                                <span class='fa fa-trash' style='margin-right: 10px;'></span>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="layoutSidenav_footer">
        <footer class="py-4 bg-light"></footer>
    </div>
</div>
<?php include '../includes/scripts.php'; ?>
</body>
</html>