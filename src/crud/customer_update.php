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
$selected_customer = getSelectedEmployee($customer_id);

if (!$selected_customer) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedEmployee($customer_id)
{
    $customers = Customer::all();
    foreach ($customers as $customer) {
        if ($customer->customer_id == $customer_id) {
            return $customer;
        }
    }
    return null;
}

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $customer_id = $_POST['customer_id'];
        $cust_first_name = $_POST['cust_first_name'];
        $cust_last_name = $_POST['cust_last_name'];
        $cust_street_address = $_POST['cust_street_address'];
        $cust_postal_code = $_POST['cust_postal_code'];
        $cust_city = $_POST['cust_city'];
        $cust_state = $_POST['cust_state'];
        $cust_country = $_POST['cust_country'];
        $phone_numbers = $_POST['phone_numbers'];
        $nls_language = $_POST['nls_language'];
        $nls_territory = $_POST['nls_territory'];
        $credit_limit = $_POST['credit_limit'];
        $cust_email = $_POST['cust_email'];
        $account_mgr_id = $_POST['account_mgr_id'];
        $cust_geo_location = $_POST['cust_geo_location'];
        $date_of_birth = $_POST['date_of_birth'];
        $marital_status = $_POST['marital_status'];
        $gender = $_POST['gender'];
        $income_level = $_POST['income_level'];

        // Validate input lengths
        if (strlen($nls_language) > 3) {
            throw new Exception("NLS_LANGUAGE is too long");
        }

        // Create a new Customer instance with form values
        $customer = new Customer(
            $customer_id,
            $cust_first_name,
            $cust_last_name,
            convertToNull($cust_street_address),
            convertToNull($cust_postal_code),
            convertToNull($cust_city),
            convertToNull($cust_state),
            convertToNull($cust_country),
            convertToNull($phone_numbers),
            convertToNull($nls_language),
            convertToNull($nls_territory),
            convertToNull($credit_limit),
            $cust_email,
            convertToNull($account_mgr_id),
            convertToNull($cust_geo_location),
            convertToNull($date_of_birth),
            convertToNull($marital_status),
            convertToNull($gender),
            convertToNull($income_level)
        );

        // Save the customer to the database
        $customer->save();  // INSERT / UPDATE

        // Redirect to customers.php with success status
        header("Location: ../html/customers.php?status=success");
        exit;

    }
} catch (mysqli_sql_exception $e) {
    // Log the database error message
    error_log("Database error: " . $e->getMessage());

    // Display the database error message in the console
    echo "<script>console.error('Database error: " . addslashes($e->getMessage()) . "');</script>";

    // Redirect to customers.php with error status
    header("Location: ../html/customers.php?status=error");
    exit;
} catch (Exception $e) {
    // Log the exception message
    error_log("General error: " . $e->getMessage());

    // Display the error message in the console
    echo "<script>console.error('Error: " . addslashes($e->getMessage()) . "');</script>";

    // Redirect to customers.php with error status
    header("Location: ../html/customers.php?status=error");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Customer Update</title>
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
                <h1 class="mt-4">Update Customer <?php echo htmlspecialchars($customer_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/customers.php">Employees table</a></li>
                    <li class="breadcrumb-item active">Update Customer</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Customer Details
                    </div>
                    <div class="card-body">
                        <form action="customer_update.php?id=<?php echo htmlspecialchars($customer_id); ?>"
                              method="post">
                            <input type="hidden" name="customer_id"
                                   value="<?php echo htmlspecialchars($selected_customer->customer_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="cust_first_name">First name:</label>
                                <input type="text" class="form-control" id="cust_first_name" name="cust_first_name"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_first_name ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_last_name">Last name:</label>
                                <input type="text" class="form-control" id="cust_last_name" name="cust_last_name"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_last_name ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_email">Email:</label>
                                <input type="email" class="form-control" id="cust_email" name="cust_email"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_email ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone_numbers">Phone number:</label>
                                <input type="text" class="form-control" id="phone_numbers" name="phone_numbers"
                                       value="<?php echo htmlspecialchars($selected_customer->phone_numbers ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_street_address">Street Address:</label>
                                <input type="text" class="form-control" id="cust_street_address"
                                       name="cust_street_address"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_street_address ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_postal_code">Postal Code:</label>
                                <input type="text" class="form-control" id="cust_postal_code" name="cust_postal_code"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_postal_code ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_city">City:</label>
                                <input type="text" class="form-control" id="cust_city" name="cust_city"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_city ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_state">State:</label>
                                <input type="text" class="form-control" id="cust_state" name="cust_state"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_state ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cust_country">Country:</label>
                                <input type="text" class="form-control" id="cust_country" name="cust_country"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_country ?? ''); ?>"
                                       required oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="form-group">
                                <label for="nls_language">Language:</label>
                                <input type="text" class="form-control" id="nls_language" name="nls_language"
                                       value="<?php echo htmlspecialchars($selected_customer->nls_language ?? ''); ?>"
                                       required oninput="this.value = this.value.toLowerCase()">
                            </div>
                            <div class="form-group">
                                <label for="nls_territory">Territory:</label>
                                <input type="text" class="form-control" id="nls_territory" name="nls_territory"
                                       value="<?php echo htmlspecialchars($selected_customer->nls_territory ?? ''); ?>"
                                       required oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="form-group">
                                <label for="credit_limit">Credit Limit:</label>
                                <input type="number" class="form-control" id="credit_limit" name="credit_limit"
                                       value="<?php echo htmlspecialchars($selected_customer->credit_limit ?? ''); ?>"
                                       step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="account_mgr_id">Account Manager ID:</label>
                                <input type="number" class="form-control" id="account_mgr_id" name="account_mgr_id"
                                       value="<?php echo htmlspecialchars($selected_customer->account_mgr_id ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="cust_geo_location">Geo Location:</label>
                                <input type="text" class="form-control" id="cust_geo_location" name="cust_geo_location"
                                       value="<?php echo htmlspecialchars($selected_customer->cust_geo_location ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth:</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                       value="<?php echo htmlspecialchars($selected_customer->date_of_birth ?? ''); ?>"
                                       required>
                            </div>

                            <script>
                                document.getElementById('date_of_birth').max = new Date().toISOString().split('T')[0];
                            </script>
                            <div class="form-group">
                                <label for="marital_status">Marital Status:</label>
                                <select class="form-control" id="marital_status" name="marital_status" required>
                                    <option value="single" <?php echo ($selected_customer->marital_status == 'single') ? 'selected' : ''; ?>>
                                        Single
                                    </option>
                                    <option value="married" <?php echo ($selected_customer->marital_status == 'married') ? 'selected' : ''; ?>>
                                        Married
                                    </option>
                                    <option value="" <?php echo ($selected_customer->marital_status == '') ? 'selected' : ''; ?>>
                                        Prefer not to say
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="M" <?php echo ($selected_customer->gender == 'M') ? 'selected' : ''; ?>>
                                        Male
                                    </option>
                                    <option value="F" <?php echo ($selected_customer->gender == 'F') ? 'selected' : ''; ?>>
                                        Female
                                    </option>
                                    <option value="" <?php echo ($selected_customer->gender == '') ? 'selected' : ''; ?>>
                                        Prefer not to say
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="income_level">Income Level:</label>
                                <input type="text" class="form-control" id="income_level" name="income_level"
                                       value="<?php echo htmlspecialchars($selected_customer->income_level ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Customer</button>
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
