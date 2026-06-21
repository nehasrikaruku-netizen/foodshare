<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";

// Handle New Request Submission
if(isset($_POST['submit_request']))
{
    $submitted_by = $_SESSION['user_id'];
    $location     = mysqli_real_escape_string($conn, $_POST['location']);
    $people_count = (int)$_POST['people_count'];
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $request_type = mysqli_real_escape_string($conn, $_POST['request_type']);

    $sql = "INSERT INTO emergency_requests
    (submitted_by, location, people_count, description, request_type)
    VALUES
    ('$submitted_by','$location','$people_count','$description','$request_type')";

    if(mysqli_query($conn, $sql))
    {
        if($request_type == 'Emergency')
        {
            $message = "
            <div class='alert alert-danger'>
                🚨 Emergency Request Submitted Successfully!
            </div>";
        }
        else
        {
            $message = "
            <div class='alert alert-success'>
                ✅ Request Submitted Successfully!
            </div>";
        }
    }
    else
    {
        $message = "
        <div class='alert alert-danger'>
            ❌ Error: ".mysqli_error($conn)."
        </div>";
    }
}

// Handle Mark Fulfilled
if(isset($_GET['fulfill']) && is_numeric($_GET['fulfill']))
{
    $fid = (int)$_GET['fulfill'];

    mysqli_query(
        $conn,
        "UPDATE emergency_requests
         SET status='Fulfilled'
         WHERE id='$fid'"
    );

    header("Location: emergency_request.php");
    exit();
}

// Fetch Requests
$requests = mysqli_query(
    $conn,
    "SELECT er.*, u.fullname
     FROM emergency_requests er
     LEFT JOIN users u
     ON er.submitted_by = u.id
     ORDER BY
     CASE er.request_type
         WHEN 'Emergency' THEN 1
         ELSE 2
     END,
     CASE er.status
         WHEN 'Pending' THEN 1
         ELSE 2
     END,
     er.created_at DESC"
);

if(!$requests)
{
    die("Query Error: ".mysqli_error($conn));
}

// Statistics
$emergency_pending = 0;
$normal_pending = 0;
$fulfilled_total = 0;
$total_people = 0;

$result = mysqli_query(
$conn,
"SELECT COUNT(*) AS total
 FROM emergency_requests
 WHERE request_type='Emergency'
 AND status='Pending'"
);

if($result)
{
    $row = mysqli_fetch_assoc($result);
    $emergency_pending = $row['total'];
}

$result = mysqli_query(
$conn,
"SELECT COUNT(*) AS total
 FROM emergency_requests
 WHERE request_type='Normal'
 AND status='Pending'"
);

if($result)
{
    $row = mysqli_fetch_assoc($result);
    $normal_pending = $row['total'];
}

$result = mysqli_query(
$conn,
"SELECT COUNT(*) AS total
 FROM emergency_requests
 WHERE status='Fulfilled'"
);

if($result)
{
    $row = mysqli_fetch_assoc($result);
    $fulfilled_total = $row['total'];
}

$result = mysqli_query(
$conn,
"SELECT SUM(people_count) AS total
 FROM emergency_requests
 WHERE status='Pending'"
);

if($result)
{
    $row = mysqli_fetch_assoc($result);
    $total_people = $row['total'] ?? 0;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Emergency Requests - FoodShare 2.0</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:
    linear-gradient(
        rgba(255,255,255,0.80),
        rgba(255,255,255,0.80)
    ),
    url('foodshare-bg.png');
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    min-height:100vh;
}

.glass-card{
    background:rgba(255,255,255,0.92);
    backdrop-filter:blur(6px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
}

.request-card{
    background:rgba(255,255,255,0.95);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
}

.request-card:hover{
    transform:translateY(-5px);
}

.request-card.emergency-card{
    border-left:6px solid #dc3545 !important;
}

.request-card.normal-card{
    border-left:6px solid #198754 !important;
}

.request-card.fulfilled-card{
    border-left:6px solid #6c757d !important;
    opacity:0.75;
}

.emergency-alert{
    background:linear-gradient(135deg,#dc3545,#c82333);
    color:white;
    border-radius:16px;
    padding:18px 22px;
    box-shadow:0 4px 20px rgba(220,53,69,0.40);
    animation:pulse 1.5s ease-in-out infinite;
}

@keyframes pulse{
    0%  { box-shadow:0 4px 20px rgba(220,53,69,0.40); }
    50% { box-shadow:0 4px 40px rgba(220,53,69,0.80); }
    100%{ box-shadow:0 4px 20px rgba(220,53,69,0.40); }
}

.stat-card{
    background:rgba(255,255,255,0.95);
    border:none;
    border-radius:16px;
    box-shadow:0 4px 15px rgba(0,0,0,0.10);
    transition:0.3s;
    text-align:center;
    padding:16px;
}

.stat-card:hover{
    transform:translateY(-4px);
}

.form-control,
.form-select{
    border-radius:12px;
}

.btn{
    border-radius:12px;
}

.who-box{
    background:#fff3cd;
    border-radius:14px;
    padding:14px;
    border-left:4px solid #ffc107;
    font-size:0.90rem;
}

</style>

</head>

<body>

<div class="container py-5">

    <!-- Header -->
    <div class="card glass-card mb-4">
        <div class="card-body text-center">
            <h2 class="text-success fw-bold">
                <i class="bi bi-box2-heart-fill"></i>
                FoodShare 2.0
            </h2>
            <p class="mb-0 text-muted">
                Emergency Hunger Request System
            </p>
        </div>
    </div>

    <!-- Emergency Alert Banner -->
    <?php if($emergency_pending > 0): ?>
    <div class="emergency-alert mb-4 text-center">
        🚨 <strong>
            <?php echo $emergency_pending; ?>
            EMERGENCY REQUEST<?php echo ($emergency_pending>1)?'S':''; ?>
            PENDING!
        </strong>
        &nbsp;—&nbsp;
        <strong><?php echo $total_people ?? 0; ?></strong>
        hungry people waiting for food right now!
    </div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="row g-3 mb-4 justify-content-center">

        <div class="col-md-3 col-6">
            <div class="stat-card"
                 style="border-top:4px solid #dc3545;">
                <div style="font-size:36px;">🚨</div>
                <h5 class="mt-1 mb-0">Emergency</h5>
                <h2 class="text-danger fw-bold">
                    <?php echo $emergency_pending; ?>
                </h2>
                <small class="text-muted">Pending urgent</small>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="stat-card"
                 style="border-top:4px solid #198754;">
                <div style="font-size:36px;">📋</div>
                <h5 class="mt-1 mb-0">Normal</h5>
                <h2 class="text-success fw-bold">
                    <?php echo $normal_pending; ?>
                </h2>
                <small class="text-muted">Pending normal</small>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="stat-card"
                 style="border-top:4px solid #0d6efd;">
                <div style="font-size:36px;">✅</div>
                <h5 class="mt-1 mb-0">Fulfilled</h5>
                <h2 class="text-primary fw-bold">
                    <?php echo $fulfilled_total; ?>
                </h2>
                <small class="text-muted">Completed</small>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="stat-card"
                 style="border-top:4px solid #fd7e14;">
                <div style="font-size:36px;">🧍</div>
                <h5 class="mt-1 mb-0">Waiting</h5>
                <h2 class="text-warning fw-bold">
                    <?php echo $total_people ?? 0; ?>
                </h2>
                <small class="text-muted">Need food now</small>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <!-- Submit Form -->
        <div class="col-lg-5">

            <div class="card glass-card">

                <div class="card-header bg-danger text-white"
                     style="border-radius:20px 20px 0 0;">
                    <h4 class="mb-0">
                        🚨 Submit Food Request
                    </h4>
                </div>

                <div class="card-body p-4">

                    <?php echo $message; ?>

                    <div class="who-box mb-4">
                        <strong>👥 Who Can Submit?</strong><br>
                        Any volunteer, NGO worker, teacher
                        or community member who spots
                        hungry people — roadside beggars,
                        children without food, flood victims.
                    </div>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                📍 Location of Hungry People
                            </label>
                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                placeholder="Example: Near Railway Station, MVP Colony"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                🧍 Number of People Needing Food
                            </label>
                            <input
                                type="number"
                                name="people_count"
                                class="form-control"
                                placeholder="Example: 25"
                                min="1"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                📝 Description
                            </label>
                            <textarea
                                name="description"
                                class="form-control"
                                rows="3"
                                placeholder="Example: Children near bus stand have not eaten since morning."
                                required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                🚦 Request Type
                            </label>
                            <select
                                name="request_type"
                                class="form-select"
                                required>
                                <option value="Normal">
                                    📋 Normal Request
                                </option>
                                <option value="Emergency">
                                    🚨 Emergency — Urgent!
                                </option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            name="submit_request"
                            class="btn btn-danger w-100 btn-lg">
                            🚨 Submit Request
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Requests List -->
        <div class="col-lg-7">

            <h4 class="fw-bold mb-3">
                📋 All Requests
                <small class="text-muted fs-6">
                    (Emergency shown first)
                </small>
            </h4>

            <?php

            if($requests && mysqli_num_rows($requests) > 0)
            {

            while($req = mysqli_fetch_assoc($requests))
            {

            $is_emergency = ($req['request_type'] == 'Emergency');
            $is_fulfilled = ($req['status'] == 'Fulfilled');

            $card_class = $is_fulfilled
                ? 'fulfilled-card'
                : ($is_emergency ? 'emergency-card' : 'normal-card');

            $diff     = time() - strtotime($req['created_at']);
            $time_ago = "";

            if($diff < 3600)
                $time_ago = round($diff/60) . " mins ago";
            elseif($diff < 86400)
                $time_ago = round($diff/3600) . " hours ago";
            else
                $time_ago = round($diff/86400) . " days ago";

            ?>

            <div class="card request-card <?php echo $card_class; ?> mb-3">
                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-start flex-wrap gap-2 mb-2">
                        <div>
                        <?php if($is_emergency && !$is_fulfilled): ?>
                            <span class="badge bg-danger fs-6">
                                🚨 EMERGENCY
                            </span>
                        <?php elseif(!$is_fulfilled): ?>
                            <span class="badge bg-success fs-6">
                                📋 Normal
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-6">
                                ✅ Fulfilled
                            </span>
                        <?php endif; ?>
                        </div>
                        <small class="text-muted">
                            🕐 <?php echo $time_ago; ?>
                        </small>
                    </div>

                    <h5 class="fw-bold">
                        📍 <?php echo $req['location']; ?>
                    </h5>

                    <p class="mb-1">
                        <?php echo $req['description']; ?>
                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-2">
                        <span>
                            🧍 <strong>
                                <?php echo $req['people_count']; ?>
                            </strong> people need food
                        </span>
                        <span>
                            👤 By:
                            <strong>
                                <?php echo $req['fullname']; ?>
                            </strong>
                        </span>
                    </div>

                    <?php if(!$is_fulfilled): ?>
                    <div class="mt-3 d-flex gap-2 flex-wrap">

                        <a href="available_donations.php"
                           class="btn btn-<?php echo $is_emergency?'danger':'success'; ?>">
                            🚚 Find Food to Deliver
                        </a>

                        <a href="emergency_requests.php?fulfill=<?php echo $req['id']; ?>"
                           class="btn btn-outline-secondary"
                           onclick="return confirm('Mark this request as fulfilled?')">
                            ✅ Mark Fulfilled
                        </a>

                    </div>
                    <?php else: ?>
                    <div class="mt-2 text-muted">
                        ✅ Food delivered successfully.
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php
            }
            }
            else
            {
            ?>
            <div class="alert alert-info text-center">
                No requests submitted yet.<br>
                <small class="text-muted">
                    Use the form to report hungry people.
                </small>
            </div>
            <?php } ?>

        </div>

    </div>

    <!-- Navigation -->
    <div class="text-center mt-5 d-flex justify-content-center gap-3 flex-wrap">
        <a href="volunteer_dashboard.php"
           class="btn btn-dark btn-lg">
            <i class="bi bi-arrow-left-circle"></i>
            Volunteer Dashboard
        </a>
        <a href="dashboard.php"
           class="btn btn-secondary btn-lg">
            <i class="bi bi-house-door"></i>
            Main Dashboard
        </a>
    </div>

</div>

</body>
</html>