<?php

include '../includes/session.php';

require_once '../models/Customer.php';
require_once '../config/Database.php';

use models\Customer;

if (!isset($_GET['id'])) {
    header("Location: ../html/customers.php");
    exit;
}

$customer_id = $_GET['id'];
$selected_customer = getSelectedCustomer($customer_id);

if (!$selected_customer) {
    header("Location: ../error/404.php");
    exit;
}

$customer_id = htmlspecialchars($selected_customer->customer_id ?? 'N/A');
$cust_first_name = htmlspecialchars($selected_customer->cust_first_name ?? 'N/A');
$cust_last_name = htmlspecialchars($selected_customer->cust_last_name ?? 'N/A');
$cust_street_address = htmlspecialchars($selected_customer->cust_street_address ?? 'N/A');
$cust_postal_code = htmlspecialchars($selected_customer->cust_postal_code ?? 'N/A');
$cust_city = htmlspecialchars($selected_customer->cust_city ?? 'N/A');
$cust_state = htmlspecialchars($selected_customer->cust_state ?? 'N/A');
$cust_country = htmlspecialchars($selected_customer->cust_country ?? 'N/A');
$phone_numbers = htmlspecialchars($selected_customer->phone_numbers ?? 'N/A');
$nls_language = htmlspecialchars($selected_customer->nls_language ?? 'N/A');
$nls_territory = htmlspecialchars($selected_customer->nls_territory ?? 'N/A');
$credit_limit = htmlspecialchars($selected_customer->credit_limit ?? 'N/A');
$cust_email = htmlspecialchars($selected_customer->cust_email ?? 'N/A');
$account_mgr_id = htmlspecialchars($selected_customer->account_mgr_id ?? 'N/A');
$cust_geo_location = htmlspecialchars($selected_customer->cust_geo_location ?? 'N/A');
$date_of_birth = htmlspecialchars($selected_customer->date_of_birth ?? 'N/A');
$marital_status = htmlspecialchars($selected_customer->marital_status ?? 'N/A');
$gender = htmlspecialchars($selected_customer->gender ?? 'N/A');
$income_level = htmlspecialchars($selected_customer->income_level ?? 'N/A');

function getSelectedCustomer($customer_id)
{
    $customers = Customer::all();
    foreach ($customers as $customers) {
        if ($customers->customer_id == $customer_id) {
            return $customers;
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
    <title>Customer details</title>
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
                <h1 class="mt-4">Customer <?php echo htmlspecialchars($customer_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/customers.php">Customers table</a></li>
                    <li class="breadcrumb-item active">Customer details</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table id="datatablesSimple" class="display">
                                <tbody>
                                <tr>
                                    <th>Customer ID</th>
                                    <td><?php echo $customer_id; ?></td>
                                </tr>
                                <tr>
                                    <th>First Name</th>
                                    <td><?php echo $cust_first_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td><?php echo $cust_last_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Street Address</th>
                                    <td><?php echo $cust_street_address; ?></td>
                                </tr>
                                <tr>
                                    <th>Postal Code</th>
                                    <td><?php echo $cust_postal_code; ?></td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td><?php echo $cust_city; ?></td>
                                </tr>
                                <tr>
                                    <th>Province/State</th>
                                    <td><?php echo $cust_state; ?></td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td><?php echo $cust_country; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone Numbers</th>
                                    <td><?php echo $phone_numbers; ?></td>
                                </tr>
                                <tr>
                                    <th>Language</th>
                                    <td><?php echo $nls_language; ?></td>
                                </tr>
                                <tr>
                                    <th>Territory</th>
                                    <td><?php echo $nls_territory; ?></td>
                                </tr>
                                <tr>
                                    <th>Credit Limit</th>
                                    <td><?php echo $credit_limit; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $cust_email; ?></td>
                                </tr>
                                <tr>
                                    <th>Account Manager ID</th>
                                    <td><?php echo $account_mgr_id; ?></td>
                                </tr>
                                <tr>
                                    <th>Geo Location</th>
                                    <td><?php echo $cust_geo_location; ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td><?php echo $date_of_birth; ?></td>
                                </tr>
                                <tr>
                                    <th>Marital Status</th>
                                    <td><?php echo $marital_status; ?></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td><?php echo $gender; ?></td>
                                </tr>
                                <tr>
                                    <th>Income Level</th>
                                    <td><?php echo $income_level; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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