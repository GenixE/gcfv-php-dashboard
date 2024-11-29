<?php
include '../includes/session.php';
require_once '../models/Country.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Country;
use Faker\Factory as Faker;

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_country_name = $faker->country;
$fake_country_id = substr($fake_country_name, 0, 2);
$fake_region_id = $faker->numberBetween(1, 4);
$error_message = '';

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $country_id = $_POST['country_id'];
        $country_name = $_POST['country_name'];
        $region_id = $_POST['region_id'];

        // Create a new Country instance with form values
        $country = new Country(
            $country_id,
            $country_name,
            convertToNull($region_id)
        );

        // Save the country to the database
        $country->save();  // INSERT / UPDATE

        // Redirect to countries.php with success status
        header("Location: ../html/countries.php?status=success");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    // Log the database error message
    error_log("Database error: " . $e->getMessage());

    // Store the error message
    $error_message = "Database error: " . $e->getMessage();
} catch (\Exception $e) {
    // Store the error message
    $error_message = "An error occurred: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Country</title>
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
                <h1 class="mt-4">Add a new country</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/countries.php">Countries table</a></li>
                    <li class="breadcrumb-item active">Add country</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Country details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="country_id">Country ID:</label>
                                <input type="text" class="form-control" id="country_id" name="country_id" value="<?php echo htmlspecialchars(strtoupper($fake_country_id)); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="country_name">Country Name:</label>
                                <input type="text" class="form-control" id="country_name" name="country_name" value="<?php echo htmlspecialchars($fake_country_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="region_id">Region ID:</label>
                                <input type="number" class="form-control" id="region_id" name="region_id" value="<?php echo htmlspecialchars($fake_region_id); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Country</button>
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