<?php
include '../includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Orders</title>
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
                <h1 class="mt-4">Orders</h1>
                <ol class="breadcrumb mb=4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Order table</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        List of the orders
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Customer ID</th>
                                <th>Order Total</th>
                                <th>Actions
                                    <a href='../crud/product_new.php' class='mr-2' title='New File'
                                       data-toggle='tooltip'>
                                        <span class='fa fa-user-plus' style='margin-left: 10px;'></span>
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            require_once '../models/Order.php';

                            use models\Order;

                            $orders = Order::all();

                            foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order->order_id; ?></td>
                                    <td><?php echo $order->order_date; ?></td>
                                    <td><?php echo $order->customer_id; ?></td>
                                    <td><?php echo $order->order_total; ?></td>
                                    <td>
                                        <a href='../crud/order_view.php?id=<?php echo $order->order_id; ?>'
                                           class='mr-2' title='View File' data-toggle='tooltip'>
                                            <span class='fa fa-circle-info' style='margin-right: 10px;'></span>
                                        </a>
                                        <a href='../crud/order_update.php?id=<?php echo $order->order_id; ?>'
                                           class='mr-2' title='Update File' data-toggle='tooltip'>
                                            <span class='fa fa-pen-to-square' style='margin-right: 10px;'></span>
                                        </a>
                                        <a href='../crud/order_delete.php?id=<?php echo $order->order_id; ?>'
                                           class='mr-2' title='Delete File' data-toggle='tooltip'>
                                            <span class='fa fa-trash' style='margin-right: 10px;'></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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