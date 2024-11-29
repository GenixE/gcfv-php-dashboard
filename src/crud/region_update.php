<?php
include '../includes/session.php';
require_once '../models/Region.php';
require_once '../config/Database.php';

use models\Region;

$error_message = '';

if (!isset($_GET['id'])) {
    header("Location: ../html/regions.php");
    exit;
}

$region_id = $_GET['id'];
$selected_region = getSelectedRegion($region_id);

if (!$selected_region) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedRegion($region_id)
{
    $regions = Region::all();
    foreach ($regions as $region) {
        if ($region->region_id == $region_id) {
            return $region;
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
        $region_id = $_POST['region_id'];
        $region_name = $_POST['region_name'];

        $region = new Region(
            $region_id,
            $region_name
        );

        $region->save();

        header("Location: ../html/regions.php?status=success");
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
    <title>Region Update</title>
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
                <h1 class="mt-4">Update Region <?php echo htmlspecialchars($region_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/regions.php">Regions table</a></li>
                    <li class="breadcrumb-item active">Update Region</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Region Details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="region_update.php?id=<?php echo htmlspecialchars($region_id); ?>" method="post">
                            <input type="hidden" name="region_id" value="<?php echo htmlspecialchars($selected_region->region_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="region_name">Region Name:</label>
                                <input type="text" class="form-control" id="region_name" name="region_name" value="<?php echo htmlspecialchars($selected_region->region_name ?? ''); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Region</button>
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