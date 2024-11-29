<?php
include '../includes/session.php';
require_once '../models/Location.php';
require_once '../config/Database.php';

use Models\Location;

$error_message = '';

if (!isset($_GET['id'])) {
    header("Location: ../html/locations.php");
    exit;
}

$location_id = $_GET['id'];
$selected_location = getSelectedLocation($location_id);

if (!$selected_location) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedLocation($location_id)
{
    $locations = Location::all();
    foreach ($locations as $location) {
        if ($location->location_id == $location_id) {
            return $location;
        }
    }
    return null;
}

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $location_id = $_POST['location_id'];
        $street_address = $_POST['street_address'];
        $postal_code = $_POST['postal_code'];
        $city = $_POST['city'];
        $state_province = $_POST['state_province'];
        $country_id = $_POST['country_id'];

        $location = new Location(
            $location_id,
            convertToNull($street_address),
            convertToNull($postal_code),
            $city,
            convertToNull($state_province),
            $country_id
        );

        $location->save();

        header("Location: ../html/locations.php?status=success");
        exit;
    }
} catch (\Exception $e) {
    $error_message = "An error occurred: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Location Update</title>
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
                <h1 class="mt-4">Update Location <?php echo htmlspecialchars($location_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/locations.php">Locations table</a></li>
                    <li class="breadcrumb-item active">Update Location</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Location Details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="location_update.php?id=<?php echo htmlspecialchars($location_id); ?>" method="post">
                            <input type="hidden" name="location_id" value="<?php echo htmlspecialchars($selected_location->location_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="street_address">Street Address:</label>
                                <input type="text" class="form-control" id="street_address" name="street_address" value="<?php echo htmlspecialchars($selected_location->street_address ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code:</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($selected_location->postal_code ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($selected_location->city ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="state_province">State/Province:</label>
                                <input type="text" class="form-control" id="state_province" name="state_province" value="<?php echo htmlspecialchars($selected_location->state_province ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Country ID:</label>
                                <input type="text" class="form-control" id="country_id" name="country_id" value="<?php echo htmlspecialchars($selected_location->country_id ?? ''); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Location</button>
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