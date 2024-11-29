<?php
include '../includes/session.php';
require_once '../models/Location.php';
require_once '../config/Database.php';

use models\Location;

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

$location_id = htmlspecialchars($selected_location->location_id ?? 'N/A');
$street_address = htmlspecialchars($selected_location->street_address ?? 'N/A');
$postal_code = htmlspecialchars($selected_location->postal_code ?? 'N/A');
$city = htmlspecialchars($selected_location->city ?? 'N/A');
$state_province = htmlspecialchars($selected_location->state_province ?? 'N/A');
$country_id = htmlspecialchars($selected_location->country_id ?? 'N/A');
$country_name = htmlspecialchars($selected_location->getCountryName() ?? 'N/A');
$region_name = htmlspecialchars($selected_location->getRegionName() ?? 'N/A');

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Location details</title>
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
                <h1 class="mt-4">Location <?php echo htmlspecialchars($location_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/locations.php">Locations table</a></li>
                    <li class="breadcrumb-item active">Location details</li>
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
                                <th>Location ID</th>
                                <td><?php echo $location_id; ?></td>
                            </tr>
                            <tr>
                                <th>Street Address</th>
                                <td><?php echo $street_address; ?></td>
                            </tr>
                            <tr>
                                <th>Postal Code</th>
                                <td><?php echo $postal_code; ?></td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td><?php echo $city; ?></td>
                            </tr>
                            <tr>
                                <th>State Province</th>
                                <td><?php echo $state_province; ?></td>
                            </tr>
                            <tr>
                                <th>Country ID</th>
                                <td><?php echo $country_id; ?></td>
                            </tr>
                            <tr>
                                <th>Country Name</th>
                                <td><?php echo $country_name; ?></td>
                            </tr>
                            <tr>
                                <th>Region Name</th>
                                <td><?php echo $region_name; ?></td>
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