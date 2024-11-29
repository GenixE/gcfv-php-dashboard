<?php
include '../includes/session.php';
require_once '../models/Country.php';
require_once '../config/Database.php';

use models\Country;

$error_message = '';

if (!isset($_GET['id'])) {
    header("Location: ../html/countries.php");
    exit;
}

$country_id = $_GET['id'];
$selected_country = getSelectedCountry($country_id);

if (!$selected_country) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedCountry($country_id)
{
    $countries = Country::all();
    foreach ($countries as $country) {
        if ($country->country_id == $country_id) {
            return $country;
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
        $country_id = $_POST['country_id'];
        $country_name = $_POST['country_name'];
        $region_id = $_POST['region_id'];

        $country = new Country(
            $country_id,
            $country_name,
            convertToNull($region_id)
        );

        $country->save();

        header("Location: ../html/countries.php?status=success");
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
    <title>Country Update</title>
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
                <h1 class="mt-4">Update Country <?php echo htmlspecialchars($country_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/countries.php">Countries table</a></li>
                    <li class="breadcrumb-item active">Update Country</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Country Details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="country_update.php?id=<?php echo htmlspecialchars($country_id); ?>" method="post">
                            <input type="hidden" name="country_id" value="<?php echo htmlspecialchars($selected_country->country_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="country_name">Country Name:</label>
                                <input type="text" class="form-control" id="country_name" name="country_name" value="<?php echo htmlspecialchars($selected_country->country_name ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="region_id">Region ID:</label>
                                <input type="number" class="form-control" id="region_id" name="region_id" value="<?php echo htmlspecialchars($selected_country->region_id ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Country</button>
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