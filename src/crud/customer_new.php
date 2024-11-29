<?php

include '../includes/session.php';

require_once '../models/Customer.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Customer;
use Faker\Factory as Faker;

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_customer_id = $faker->randomNumber();
$fake_first_name = $faker->firstName;
$fake_last_name = $faker->lastName;
$fake_street_address = $faker->streetAddress;
$fake_postal_code = $faker->postcode;
$fake_city = $faker->city;
$fake_state = $faker->state;
$fake_country = $faker->countryCode;
$fake_phone_numbers = $faker->phoneNumber;
$fake_email = $faker->email;
$fake_nls_language = $faker->languageCode;
$fake_nls_territory = $faker->countryCode;
$fake_credit_limit = $faker->randomFloat(2, 0, 5000); // Adjusted to respect the constraint
$fake_account_mgr_id = $faker->randomNumber();
$fake_geo_location = '[' . $faker->latitude . ', ' . $faker->longitude . ', null]';
$fake_date_of_birth = $faker->date('Y-m-d');
$fake_marital_status = $faker->randomElement(['single', 'married']); // Adjusted to respect the constraint
$fake_gender = $faker->randomElement(['M', 'F']);
$fake_income_level = $faker->randomElement(['low', 'medium', 'high']);

$errors = [];

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
            $errors[] = "NLS_LANGUAGE is too long";
        }

        // Validate credit limit
        if ($credit_limit > 5000) {
            $errors[] = "CREDIT_LIMIT must be less than or equal to 5000";
        }

        // Validate marital status
        if (!in_array($marital_status, ['single', 'married'])) {
            $errors[] = "MARITAL_STATUS must be either 'single' or 'married'";
        }

        if (empty($errors)) {
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
    }
} catch (mysqli_sql_exception $e) {
    // Log the database error message
    error_log("Database error: " . $e->getMessage());

    // Display the database error message in the console
    echo "<script>console.error('Database error: " . addslashes($e->getMessage()) . "');</script>";

    $errors[] = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    // Log the exception message
    error_log("General error: " . $e->getMessage());

    // Display the error message in the console
    echo "<script>console.error('Error: " . addslashes($e->getMessage()) . "');</script>";

    $errors[] = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Customer</title>
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
                <h1 class="mt-4">Add a new customer</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/customers.php">Customers table</a></li>
                    <li class="breadcrumb-item active">Add customer</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Customer details
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id">Customer ID:</label>
                                        <input type="number" class="form-control" id="customer_id" name="customer_id"
                                               value="<?php echo htmlspecialchars($fake_customer_id); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_first_name">First name:</label>
                                        <input type="text" class="form-control" id="cust_first_name"
                                               name="cust_first_name" maxlength="20" value="<?php echo htmlspecialchars($fake_first_name); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_last_name">Last name:</label>
                                        <input type="text" class="form-control" id="cust_last_name"
                                               name="cust_last_name" maxlength="20" value="<?php echo htmlspecialchars($fake_last_name); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_street_address">Street Address:</label>
                                        <input type="text" class="form-control" id="cust_street_address"
                                               name="cust_street_address" maxlength="100" value="<?php echo htmlspecialchars($fake_street_address); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_postal_code">Postal Code:</label>
                                        <input type="text" class="form-control" id="cust_postal_code"
                                               name="cust_postal_code" maxlength="20" value="<?php echo htmlspecialchars($fake_postal_code); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_city">City:</label>
                                        <input type="text" class="form-control" id="cust_city" name="cust_city"
                                               maxlength="20" value="<?php echo htmlspecialchars($fake_city); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_state">State:</label>
                                        <input type="text" class="form-control" id="cust_state" name="cust_state"
                                               maxlength="20" value="<?php echo htmlspecialchars($fake_state); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_country">Country:</label>
                                        <input type="text" class="form-control" id="cust_country" name="cust_country"
                                               maxlength="20" value="<?php echo htmlspecialchars($fake_country); ?>" required oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_numbers">Phone Number:</label>
                                        <input type="text" class="form-control" id="phone_numbers" name="phone_numbers"
                                               maxlength="100" value="<?php echo htmlspecialchars($fake_phone_numbers); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_email">Email:</label>
                                        <input type="email" class="form-control" id="cust_email" name="cust_email"
                                               maxlength="30" value="<?php echo htmlspecialchars($fake_email); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nls_language">Language:</label>
                                        <input type="text" class="form-control" id="nls_language" name="nls_language"
                                               maxlength="3" value="<?php echo htmlspecialchars($fake_nls_language); ?>" required oninput="this.value = this.value.toLowerCase()">
                                    </div>
                                    <div class="form-group">
                                        <label for="nls_territory">Territory:</label>
                                        <input type="text" class="form-control" id="nls_territory" name="nls_territory"
                                               maxlength="30" value="<?php echo htmlspecialchars($fake_nls_territory); ?>" required oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class="form-group">
                                        <label for="credit_limit">Credit Limit:</label>
                                        <input type="number" class="form-control" id="credit_limit" name="credit_limit"
                                               step="0.01" min="0" max="5000" maxlength="12" value="<?php echo htmlspecialchars($fake_credit_limit); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_mgr_id">Account Manager ID:</label>
                                        <input type="number" class="form-control" id="account_mgr_id"
                                               name="account_mgr_id" value="<?php echo htmlspecialchars($fake_account_mgr_id); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="cust_geo_location">Geo Location:</label>
                                        <input type="text" class="form-control" id="cust_geo_location"
                                               name="cust_geo_location" value="<?php echo htmlspecialchars($fake_geo_location); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth:</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                               value="<?php echo htmlspecialchars($fake_date_of_birth); ?>" required>
                                    </div>

                                    <script>
                                        document.getElementById('date_of_birth').max = new Date().toISOString().split('T')[0];
                                    </script>
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status:</label>
                                        <select class="form-control" id="marital_status" name="marital_status" required>
                                            <option value="single" <?php echo $fake_marital_status == 'single' ? 'selected' : ''; ?>>Single</option>
                                            <option value="married" <?php echo $fake_marital_status == 'married' ? 'selected' : ''; ?>>Married</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender:</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="M" <?php echo $fake_gender == 'M' ? 'selected' : ''; ?>>Male</option>
                                            <option value="F" <?php echo $fake_gender == 'F' ? 'selected' : ''; ?>>Female</option>
                                            <option value="">Prefer not to say</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="income_level">Income Level:</label>
                                        <input type="text" class="form-control" id="income_level" name="income_level" value="<?php echo htmlspecialchars($fake_income_level); ?>">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Customer</button>
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
