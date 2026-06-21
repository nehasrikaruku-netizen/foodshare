<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

// ── Priority counts for alert banner ─────────────────────────
$critical_count = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM food_donations
     WHERE status='Available' AND priority='Critical'")
)['total'];

$urgent_count = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM food_donations
     WHERE status='Available' AND priority='Urgent'")
)['total'];

$fresh_count = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM food_donations
     WHERE status='Available' AND priority='Fresh'")
)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Volunteer Dashboard - FoodShare 2.0</title>

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

.action-card{
    background:rgba(255,255,255,0.92);
    backdrop-filter:blur(6px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
}

.action-card:hover{
    transform:translateY(-6px);
}

.priority-stat{
    background:rgba(255,255,255,0.92);
    border:none;
    border-radius:16px;
    box-shadow:0 4px 15px rgba(0,0,0,0.10);
    transition:0.3s;
}

.priority-stat:hover{
    transform:translateY(-4px);
}

.btn{
    border-radius:12px;
}

.icon{
    font-size:60px;
}

.stat-icon{
    font-size:36px;
}

.urgent-alert{
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 4px 15px rgba(220,53,69,0.35);
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse{
    0%  { box-shadow: 0 4px 15px rgba(220,53,69,0.35); }
    50% { box-shadow: 0 4px 30px rgba(220,53,69,0.70); }
    100%{ box-shadow: 0 4px 15px rgba(220,53,69,0.35); }
}

</style>

</head>

<body>

<div class="container py-5">

    <!-- Header -->
    <div class="card glass-card mb-4">
        <div class="card-body text-center">

            <h2 class="text-success fw-bold">
                <i class="bi bi-person-hearts"></i>
                Volunteer Dashboard
            </h2>

            <p class="mb-0">
                Welcome, <strong><?php echo $_SESSION['fullname']; ?></strong> 👋
            </p>

        </div>
    </div>

    <!-- 🚨 Urgent Alert Banner (only shown if critical food exists) -->
    <?php if($critical_count > 0): ?>
    <div class="urgent-alert mb-4 text-center">
        🚨 <strong>URGENT!</strong>
        There <?php echo ($critical_count == 1) ? "is" : "are"; ?>
        <strong><?php echo $critical_count; ?> Critical</strong>
        food donation<?php echo ($critical_count > 1) ? "s" : ""; ?>
        that must be picked up <strong>immediately</strong>
        before the food becomes unsafe!
        &nbsp;
        <a href="available_donations.php" class="btn btn-light btn-sm fw-bold">
            🚚 View Now
        </a>
    </div>
    <?php endif; ?>

    <!-- Priority Stats Row -->
    <div class="row g-3 mb-4 justify-content-center">

        <div class="col-md-4 col-6">
            <div class="card priority-stat text-center p-3"
                 style="border-left:5px solid #dc3545;">
                <div class="stat-icon">🔴</div>
                <h5 class="mt-1 mb-0">Critical</h5>
                <h2 class="text-danger fw-bold"><?php echo $critical_count; ?></h2>
                <small class="text-muted">Needs immediate pickup</small>
            </div>
        </div>

        <div class="col-md-4 col-6">
            <div class="card priority-stat text-center p-3"
                 style="border-left:5px solid #ffc107;">
                <div class="stat-icon">🟡</div>
                <h5 class="mt-1 mb-0">Urgent</h5>
                <h2 class="text-warning fw-bold"><?php echo $urgent_count; ?></h2>
                <small class="text-muted">Pick up soon</small>
            </div>
        </div>

        <div class="col-md-4 col-6">
            <div class="card priority-stat text-center p-3"
                 style="border-left:5px solid #198754;">
                <div class="stat-icon">🟢</div>
                <h5 class="mt-1 mb-0">Fresh</h5>
                <h2 class="text-success fw-bold"><?php echo $fresh_count; ?></h2>
                <small class="text-muted">Good condition</small>
            </div>
        </div>

    </div>

    <!-- Action Cards -->
    <div class="row justify-content-center g-4">

        <div class="col-md-5">
            <div class="card action-card">
                <div class="card-body text-center">

                    <div class="icon">🍱</div>

                    <h4 class="mt-3">
                        Available Donations
                    </h4>

                    <p class="text-muted">
                        View and accept food donations available for pickup.
                        Critical items shown first.
                    </p>

                    <a href="available_donations.php"
                       class="btn btn-success btn-lg">
                        View Donations
                        <?php if($critical_count > 0): ?>
                        <span class="badge bg-danger ms-1">
                            <?php echo $critical_count; ?> Urgent
                        </span>
                        <?php endif; ?>
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card action-card">
                <div class="card-body text-center">

                    <div class="icon">🚚</div>

                    <h4 class="mt-3">
                        My Tasks
                    </h4>

                    <p class="text-muted">
                        Track accepted, picked up and distributed donations.
                    </p>

                    <a href="my_tasks.php"
                       class="btn btn-primary btn-lg">
                        Open Tasks
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card action-card">
                <div class="card-body text-center">

                    <div class="icon">📍</div>

                    <h4 class="mt-3">
                        Smart Food Matching
                    </h4>

                    <p class="text-muted">
                        Auto-match available donations with nearby
                        organizations based on quantity and location.
                    </p>

                    <a href="smart_matching.php"
                       class="btn btn-warning btn-lg">
                        View Matches
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card action-card">
                <div class="card-body text-center">

                    <div class="icon">🚨</div>

                    <h4 class="mt-3">
                        Emergency Requests
                    </h4>

                    <p class="text-muted">
                        View urgent food requests from organizations
                        that need immediate attention.
                    </p>

                    <a href="emergency_requests.php"
                       class="btn btn-danger btn-lg">
                        View Emergencies
                    </a>

                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="dashboard.php" class="btn btn-dark btn-lg">
            <i class="bi bi-house-door"></i>
            Back to Dashboard
        </a>
    </div>

</div>

</body>
</html>