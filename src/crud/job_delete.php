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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $job = new Job($job_id);
        $job->destroy();
        header("Location: ../html/jobs.php?status=success");
        exit;
    } catch (\Exception $e) {
        $error_message = "An error occurred: " . $e->getMessage();
    }
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="sb-nav-fixed">
<?php include '../includes/topnav.php'; ?>
<div id="layoutSidenav">
    <?php include '../includes/sidenav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Remove Job <?php echo htmlspecialchars($job_id); ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../html/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../html/jobs.php">Jobs table</a></li>
                    <li class="breadcrumb-item active">Remove Job</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Remove Job
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <p>Are you sure you want to delete job <?php echo htmlspecialchars($selected_job->job_title); ?>?</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal">Delete
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="post" action="job_delete.php?id=<?php echo htmlspecialchars($job_id); ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/scripts.php'; ?>
</body>
</html>