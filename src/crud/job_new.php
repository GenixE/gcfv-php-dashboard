<?php
include '../includes/session.php';
require_once '../models/Job.php';
require_once '../../vendor/autoload.php'; // Include the Faker autoloader

use Models\Job;
use Faker\Factory as Faker;

function convertToNull($value)
{
    return $value === '' ? null : $value;
}

// Generate fake data using Faker
$faker = Faker::create();
$fake_job_title = $faker->jobTitle();
$fake_job_title_abbr = implode('', array_map(function($word) { return strtoupper($word[0]); }, explode(' ', $fake_job_title)));
$fake_min_salary = $faker->randomFloat(2, 30000, 50000);
$fake_max_salary = $faker->randomFloat(2, 50000, 100000);

$error_message = '';

try {
    // If the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $job_id = $_POST['job_id'];
        $job_title = $_POST['job_title'];
        $min_salary = $_POST['min_salary'];
        $max_salary = $_POST['max_salary'];

        // Create a new Job instance with form values
        $job = new Job(
            $job_id,
            $job_title,
            convertToNull($min_salary),
            convertToNull($max_salary)
        );

        // Save the job to the database
        $job->save();  // INSERT / UPDATE

        // Redirect to jobs.php with success status
        header("Location: ../html/jobs.php?status=success");
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
    <title>New Job</title>
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
                <h1 class="mt-4">Add a new job</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/jobs.php">Jobs table</a></li>
                    <li class="breadcrumb-item active">Add job</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Job details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="job_id">Job ID:</label>
                                <input type="text" class="form-control" id="job_id" name="job_id" value="<?php echo htmlspecialchars($fake_job_title_abbr); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="job_title">Job Title:</label>
                                <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo htmlspecialchars($fake_job_title); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="min_salary">Min Salary:</label>
                                <input type="number" class="form-control" id="min_salary" name="min_salary" step="0.01" value="<?php echo htmlspecialchars($fake_min_salary); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="max_salary">Max Salary:</label>
                                <input type="number" class="form-control" id="max_salary" name="max_salary" step="0.01" value="<?php echo htmlspecialchars($fake_max_salary); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Job</button>
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