<?php
include '../includes/session.php';
require_once '../models/Job.php';
require_once '../config/Database.php';

use models\Job;

$error_message = '';

if (!isset($_GET['id'])) {
    header("Location: ../html/jobs.php");
    exit;
}

$job_id = $_GET['id'];
$selected_job = getSelectedJob($job_id);

if (!$selected_job) {
    header("Location: ../error/404.php");
    exit;
}

function getSelectedJob($job_id)
{
    $jobs = Job::all();
    foreach ($jobs as $job) {
        if ($job->job_id == $job_id) {
            return $job;
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
        $job_id = $_POST['job_id'];
        $job_title = $_POST['job_title'];
        $min_salary = $_POST['min_salary'];
        $max_salary = $_POST['max_salary'];

        $job = new Job(
            $job_id,
            $job_title,
            convertToNull($min_salary),
            convertToNull($max_salary)
        );

        $job->save();

        header("Location: ../html/jobs.php?status=success");
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
    <title>Job Update</title>
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
                <h1 class="mt-4">Update Job <?php echo htmlspecialchars($job_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/jobs.php">Jobs table</a></li>
                    <li class="breadcrumb-item active">Update Job</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Update Job Details
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="job_update.php?id=<?php echo htmlspecialchars($job_id); ?>" method="post">
                            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($selected_job->job_id ?? ''); ?>">
                            <div class="form-group">
                                <label for="job_title">Job Title:</label>
                                <input type="text" class="form-control" id="job_title" name="job_title"
                                       value="<?php echo htmlspecialchars($selected_job->job_title ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="min_salary">Min Salary:</label>
                                <input type="number" class="form-control" id="min_salary" name="min_salary"
                                       value="<?php echo htmlspecialchars($selected_job->min_salary ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="max_salary">Max Salary:</label>
                                <input type="number" class="form-control" id="max_salary" name="max_salary"
                                       value="<?php echo htmlspecialchars($selected_job->max_salary ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Job</button>
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
