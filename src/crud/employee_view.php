<?php
include '../includes/session.php';
require_once '../models/Employee.php';
require_once '../config/Database.php';

use models\Employee;

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

$employee_id = htmlspecialchars($selected_employee->employee_id ?? 'N/A');
$first_name = htmlspecialchars($selected_employee->first_name ?? 'N/A');
$last_name = htmlspecialchars($selected_employee->last_name ?? 'N/A');
$email = htmlspecialchars($selected_employee->email ?? 'N/A');
$phone_number = htmlspecialchars($selected_employee->phone_number ?? 'N/A');
$hire_date = htmlspecialchars($selected_employee->hire_date ?? 'N/A');
$job_id = htmlspecialchars($selected_employee->job_id ?? 'N/A');
$salary = htmlspecialchars($selected_employee->salary ?? 'N/A');
$commission_pct = htmlspecialchars($selected_employee->commission_pct ?? 'N/A');
$manager_id = htmlspecialchars($selected_employee->manager_id ?? 'N/A');
$department_id = htmlspecialchars($selected_employee->department_id ?? 'N/A');

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Employee details</title>
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
                <h1 class="mt-4">Employee <?php echo htmlspecialchars($employee_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/employees.php">Employees table</a></li>
                    <li class="breadcrumb-item active">Employee details</li>
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
                                <th>Employee ID</th>
                                <td><?php echo $employee_id; ?></td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td><?php echo $first_name; ?></td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td><?php echo $last_name; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $email; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td><?php echo $phone_number; ?></td>
                            </tr>
                            <tr>
                                <th>Hire Date</th>
                                <td><?php echo $hire_date; ?></td>
                            </tr>
                            <tr>
                                <th>Job ID</th>
                                <td><?php echo $job_id; ?></td>
                            </tr>
                            <tr>
                                <th>Salary</th>
                                <td><?php echo $salary; ?></td>
                            </tr>
                            <tr>
                                <th>Commission Pct</th>
                                <td><?php echo $commission_pct; ?></td>
                            </tr>
                            <tr>
                                <th>Manager ID</th>
                                <td><?php echo $manager_id; ?></td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td><?php echo $department_id; ?></td>
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