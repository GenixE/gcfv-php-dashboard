<?php
include '../includes/session.php';
require_once '../models/Location.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Location;
use Faker\Factory as Faker;

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_location_id = $faker->numberBetween(1000, 10000);
$fake_street_address = $faker->streetAddress;
$fake_postal_code = $faker->postcode;
$fake_city = $faker->city;
$fake_state_province = $faker->state;
$fake_country_id = $faker->countryCode;

$error_message = '';

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $location_id = $_POST['location_id'];
        $street_address = $_POST['street_address'];
        $postal_code = $_POST['postal_code'];
        $city = $_POST['city'];
        $state_province = $_POST['state_province'];
        $country_id = $_POST['country_id'];

        // Create a new Location instance with form values
        $location = new Location(
            $location_id,
            convertToNull($street_address),
            convertToNull($postal_code),
            $city,
            convertToNull($state_province),
            $country_id
        );

        // Save the location to the database
        $location->save();  // INSERT / UPDATE

        // Redirect to locations.php with success status
        header("Location: ../html/locations.php?status=success");
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
    <title>New Location</title>
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
                <h1 class="mt-4">Add a new location</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/locations.php">Locations table</a></li>
                    <li class="breadcrumb-item active">Add location</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Location details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="location_id">Location ID:</label>
                                <input type="number" class="form-control" id="location_id" name="location_id" value="<?php echo htmlspecialchars($fake_location_id); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="street_address">Street Address:</label>
                                <input type="text" class="form-control" id="street_address" name="street_address" value="<?php echo htmlspecialchars($fake_street_address); ?>">
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code:</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($fake_postal_code); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($fake_city); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="state_province">State/Province:</label>
                                <input type="text" class="form-control" id="state_province" name="state_province" value="<?php echo htmlspecialchars($fake_state_province); ?>">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Country ID:</label>
                                <input type="text" class="form-control" id="country_id" name="country_id" value="<?php echo htmlspecialchars($fake_country_id); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Location</button>
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