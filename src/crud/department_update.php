<?php
include '../includes/session.php';
require_once '../models/Department.php';
require_once '../config/Database.php';

use models\Department;

$error_message = '';

if (!isset($_GET['id'])) {
    header("Location: ../html/departments.php");
    exit;
}

$department_id = $_GET['id'];
$selected_department = getSelectedDepartment($department_id);

if (!$selected_department) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedDepartment($department_id)
{
    $departments = Department::all();
    foreach ($departments as $department) {
        if ($department->department_id == $department_id) {
            return $department;
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
        $department_id = $_POST['department_id'];
        $department_name = $_POST['department_name'];
        $manager_id = $_POST['manager_id'];
        $location_id = $_POST['location_id'];

        $department = new Department(
            $department_id,
            $department_name,
            convertToNull($manager_id),
            convertToNull($location_id)
        );

        $department->save();

        header("Location: ../html/departments.php?status=success");
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
    <title>Department Update</title>
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
                <h1 class="mt-4">Update Department <?php echo htmlspecialchars($department_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/departments.php">Departments table</a></li>
                    <li class="breadcrumb-item active">Update Department</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Department Details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="department_update.php?id=<?php echo htmlspecialchars($department_id); ?>"
                              method="post">
                            <input type="hidden" name="department_id"
                                   value="<?php echo htmlspecialchars($selected_department->department_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="department_name">Department name:</label>
                                <input type="text" class="form-control" id="department_name" name="department_name"
                                       value="<?php echo htmlspecialchars($selected_department->department_name ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="manager_id">Manager ID:</label>
                                <input type="number" class="form-control" id="manager_id" name="manager_id"
                                       value="<?php echo htmlspecialchars($selected_department->manager_id ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="location_id">Location ID:</label>
                                <input type="number" class="form-control" id="location_id" name="location_id"
                                       value="<?php echo htmlspecialchars($selected_department->location_id ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Department</button>
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
</html><?php
