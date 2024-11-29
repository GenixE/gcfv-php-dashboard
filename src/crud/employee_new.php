<?php
include '../includes/session.php';
require_once '../models/Employee.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Employee;
use Faker\Factory as Faker;

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_employee_id = $faker->randomNumber();
$fake_first_name = $faker->firstName;
$fake_last_name = $faker->lastName;
$fake_email = $faker->email;
$fake_phone_number = $faker->phoneNumber;
$fake_hire_date = $faker->date('Y-m-d');
$fake_job_id = $faker->randomNumber();
$fake_salary = $faker->randomFloat(2, 30000, 100000);
$fake_commission_pct = $faker->randomFloat(2, 0, 1);
$fake_manager_id = $faker->randomNumber();
$fake_department_id = $faker->randomNumber();

$error_message = '';

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $employee_id = $_POST['employee_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $hire_date = $_POST['hire_date'];
        $job_id = $_POST['job_id'];
        $salary = $_POST['salary'];
        $commission_pct = $_POST['commission_pct'];
        $manager_id = $_POST['manager_id'];
        $department_id = $_POST['department_id'];

        // Create a new Employee instance with form values
        $employee = new Employee(
            $employee_id,
            $first_name,
            $last_name,
            convertToNull($email),
            convertToNull($phone_number),
            convertToNull($hire_date),
            $job_id,
            convertToNull($salary),
            convertToNull($commission_pct),
            convertToNull($manager_id),
            convertToNull($department_id));

        // Save the employee to the database
        $employee->save();  // INSERT / UPDATE

        // Redirect to employees.php with success status
        header("Location: ../html/employees.php?status=success");
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
    <title>New Employee</title>
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
                <h1 class="mt-4">Add a new employee</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/employees.php">Employees table</a></li>
                    <li class="breadcrumb-item active">Add employee</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Employee details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="employee_id">Employee ID:</label>
                                <input type="number" class="form-control" id="employee_id" name="employee_id" value="<?php echo htmlspecialchars($fake_employee_id); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($fake_first_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($fake_last_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($fake_email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone number:</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($fake_phone_number); ?>">
                            </div>
                            <div class="form-group">
                                <label for="hire_date">Hire date:</label>
                                <input type="date" class="form-control" id="hire_date" name="hire_date" value="<?php echo htmlspecialchars($fake_hire_date); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="job_id">Job ID:</label>
                                <input type="text" class="form-control" id="job_id" name="job_id" value="<?php echo htmlspecialchars($fake_job_id); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="salary">Salary:</label>
                                <input type="number" class="form-control" id="salary" name="salary" step="0.01" value="<?php echo htmlspecialchars($fake_salary); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="commission_pct">Commission:</label>
                                <input type="number" class="form-control" id="commission_pct" name="commission_pct" step="0.01" value="<?php echo htmlspecialchars($fake_commission_pct); ?>">
                            </div>
                            <div class="form-group">
                                <label for="manager_id">Manager ID:</label>
                                <input type="number" class="form-control" id="manager_id" name="manager_id" value="<?php echo htmlspecialchars($fake_manager_id); ?>">
                            </div>
                            <div class="form-group">
                                <label for="department_id">Department ID:</label>
                                <input type="number" class="form-control" id="department_id" name="department_id" value="<?php echo htmlspecialchars($fake_department_id); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Employee</button>
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