<?php
include '../includes/session.php';
require_once '../models/Department.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Department;
use Faker\Factory as Faker;

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_department_id = $faker->randomNumber();
$fake_department_name = $faker->company;
$fake_manager_id = $faker->randomNumber();
$fake_location_id = $faker->randomNumber();

$error_message = '';

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $department_id = $_POST['department_id'];
        $department_name = $_POST['department_name'];
        $manager_id = $_POST['manager_id'];
        $location_id = $_POST['location_id'];

        // Create a new Department instance with form values
        $department = new Department(
            $department_id,
            $department_name,
            convertToNull($manager_id),
            convertToNull($location_id)
        );

        // Save the department to the database
        $department->save();  // INSERT / UPDATE

        // Redirect to departments.php with success status
        header("Location: ../html/departments.php?status=success");
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
    <title>New Department</title>
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
                <h1 class="mt-4">Add a new department</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/departments.php">Departments table</a></li>
                    <li class="breadcrumb-item active">Add department</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Department details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="department_id">Department ID:</label>
                                <input type="number" class="form-control" id="department_id" name="department_id" value="<?php echo htmlspecialchars($fake_department_id); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="department_name">Department Name:</label>
                                <input type="text" class="form-control" id="department_name" name="department_name" value="<?php echo htmlspecialchars($fake_department_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="manager_id">Manager ID:</label>
                                <input type="number" class="form-control" id="manager_id" name="manager_id" value="<?php echo htmlspecialchars($fake_manager_id); ?>">
                            </div>
                            <div class="form-group">
                                <label for="location_id">Location ID:</label>
                                <input type="number" class="form-control" id="location_id" name="location_id" value="<?php echo htmlspecialchars($fake_location_id); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Department</button>
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