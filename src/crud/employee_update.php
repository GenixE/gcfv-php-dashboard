<?php
include '../includes/session.php';
require_once '../models/Employee.php';
require_once '../config/Database.php';

use models\Employee;

$error_message = '';

if (!isset($_GET['id'])) {
    header("Location: ../html/employees.php");
    exit;
}

$employee_id = $_GET['id'];
$selected_employee = getSelectedEmployee($employee_id);

if (!$selected_employee) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedEmployee($employee_id)
{
    $employees = Employee::all();
    foreach ($employees as $employee) {
        if ($employee->employee_id == $employee_id) {
            return $employee;
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
            convertToNull($department_id)
        );

        $employee->save();

        header("Location: ../html/employees.php?status=success");
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
    <title>Employee Update</title>
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
                <h1 class="mt-4">Update Employee <?php echo htmlspecialchars($employee_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/employees.php">Employees table</a></li>
                    <li class="breadcrumb-item active">Update Employee</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Employee Details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="employee_update.php?id=<?php echo htmlspecialchars($employee_id); ?>"
                              method="post">
                            <input type="hidden" name="employee_id"
                                   value="<?php echo htmlspecialchars($selected_employee->employee_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="first_name">First name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="<?php echo htmlspecialchars($selected_employee->first_name ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       value="<?php echo htmlspecialchars($selected_employee->last_name ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo htmlspecialchars($selected_employee->email ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone number:</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                       value="<?php echo htmlspecialchars($selected_employee->phone_number ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="hire_date">Hire date:</label>
                                <input type="date" class="form-control" id="hire_date" name="hire_date"
                                       value="<?php echo htmlspecialchars($selected_employee->hire_date ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="job_id">Job ID:</label>
                                <input type="text" class="form-control" id="job_id" name="job_id"
                                       value="<?php echo htmlspecialchars($selected_employee->job_id ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="salary">Salary:</label>
                                <input type="number" class="form-control" id="salary" name="salary"
                                       value="<?php echo htmlspecialchars($selected_employee->salary ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="commission_pct">Commission:</label>
                                <input type="number" class="form-control" id="commission_pct" name="commission_pct"
                                       value="<?php echo htmlspecialchars($selected_employee->commission_pct ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="manager_id">Manager ID:</label>
                                <input type="number" class="form-control" id="manager_id" name="manager_id"
                                       value="<?php echo htmlspecialchars($selected_employee->manager_id ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="department_id">Department ID:</label>
                                <input type="number" class="form-control" id="department_id" name="department_id"
                                       value="<?php echo htmlspecialchars($selected_employee->department_id ?? ''); ?>"
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Employee</button>
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