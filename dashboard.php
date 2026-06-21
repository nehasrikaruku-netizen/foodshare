<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

// ── Original Stats ────────────────────────────────────────────
$total = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations")
)['total'];

$available = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations WHERE status='Available'")
)['total'];

$accepted = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations WHERE status='Accepted'")
)['total'];

$picked = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations WHERE status='Picked Up'")
)['total'];

$distributed = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations WHERE status='Distributed'")
)['total'];

$volunteers = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM users WHERE role='volunteer'")
)['total'];

$today = date("Y-m-d");

$expiring_today = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM food_donations
     WHERE expiry_date='$today'")
)['total'];

$fresh_food = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM food_donations
     WHERE expiry_date > '$today'")
)['total'];

// ── Feature 1: Priority Stats ─────────────────────────────────
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

$fresh_priority_count = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM food_donations
     WHERE status='Available' AND priority='Fresh'")
)['total'];

// ── Feature 3: Emergency Stats ────────────────────────────────
$emergency_pending = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total FROM emergency_requests
     WHERE request_type='Emergency' AND status='Pending'")
)['total'];

$total_people_waiting = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT SUM(people_count) as total FROM emergency_requests
     WHERE status='Pending'")
)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>FoodShare 2.0 Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:
    linear-gradient(
        rgba(255,255,255,0.55),
        rgba(255,255,255,0.55)
    ),
    url('foodshare-bg.png');
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    min-height:100vh;
}

.glass-card{
    background:rgba(255,255,255,0.90);
    backdrop-filter:blur(8px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
}

.stats-card{
    background:rgba(255,255,255,0.90);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
    text-align:center;
}

.stats-card:hover{
    transform:translateY(-8px);
}

.stats-icon{
    font-size:45px;
}

.priority-card{
    background:rgba(255,255,255,0.90);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
    text-align:center;
    padding:16px;
}

.priority-card:hover{
    transform:translateY(-6px);
}

.emergency-alert{
    background:linear-gradient(135deg,#dc3545,#c82333);
    color:white;
    border-radius:16px;
    padding:16px 22px;
    box-shadow:0 4px 20px rgba(220,53,69,0.40);
    animation:pulse 1.5s ease-in-out infinite;
}

@keyframes pulse{
    0%  { box-shadow:0 4px 20px rgba(220,53,69,0.40); }
    50% { box-shadow:0 4px 40px rgba(220,53,69,0.80); }
    100%{ box-shadow:0 4px 20px rgba(220,53,69,0.40); }
}

.feature-box{
    background:rgba(255,255,255,0.80);
    border-radius:14px;
    padding:18px;
    text-align:center;
    height:100%;
    transition:0.3s;
}

.feature-box:hover{
    transform:translateY(-4px);
    background:rgba(255,255,255,0.95);
}

.feature-icon{
    font-size:38px;
}

.btn{
    border-radius:12px;
}

</style>

</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            🍽 FoodShare 2.0
        </a>
        <a href="logout.php" class="btn btn-light">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>
    </div>
</nav>

<div class="container py-5">

    <!-- Welcome Card -->
    <div class="card glass-card mb-4">
        <div class="card-body text-center">
            <h2 class="fw-bold">
                Welcome, <?php echo $_SESSION['fullname']; ?> 👋
            </h2>
            <p class="mb-0">
                Role:
                <strong>
                    <?php echo !empty($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Donor'; ?>
                </strong>
                &nbsp;|&nbsp;
                <small class="text-muted">
                    <?php echo date("D, d M Y"); ?>
                </small>
            </p>
        </div>
    </div>

    <!-- 🚨 Emergency Alert (only if emergencies pending) -->
    <?php if($emergency_pending > 0): ?>
    <div class="emergency-alert mb-4 text-center">
        🚨 <strong>
            <?php echo $emergency_pending; ?>
            EMERGENCY REQUEST<?php echo ($emergency_pending>1)?'S':''; ?>
            PENDING!
        </strong>
        &nbsp;—&nbsp;
        <strong><?php echo $total_people_waiting ?? 0; ?></strong>
        hungry people are waiting for food right now!
        &nbsp;
        <a href="emergency_requests.php"
           class="btn btn-light btn-sm fw-bold ms-2">
            🚨 Respond Now
        </a>
    </div>
    <?php endif; ?>

    <!-- Original Stats Row -->
    <h5 class="fw-bold mb-3 text-muted">
        📊 Donation Overview
    </h5>

    <div class="row g-3 justify-content-center mb-4">

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🍱</div>
                    <h6>Total Donations</h6>
                    <h2><?php echo $total; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🥗</div>
                    <h6>Available</h6>
                    <h2><?php echo $available; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🤝</div>
                    <h6>Accepted</h6>
                    <h2><?php echo $accepted; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🚚</div>
                    <h6>Picked Up</h6>
                    <h2><?php echo $picked; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">❤️</div>
                    <h6>Distributed</h6>
                    <h2><?php echo $distributed; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🙋</div>
                    <h6>Volunteers</h6>
                    <h2><?php echo $volunteers; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🔴</div>
                    <h6>Expiring Today</h6>
                    <h2><?php echo $expiring_today; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">🟢</div>
                    <h6>Fresh Food</h6>
                    <h2><?php echo $fresh_food; ?></h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Feature 1: Priority Stats Row -->
    <h5 class="fw-bold mb-3 text-muted">
        ⏱️ Food Priority Status
    </h5>

    <div class="row g-3 justify-content-center mb-5">

        <div class="col-md-4 col-6">
            <div class="priority-card"
                 style="border-left:5px solid #dc3545;">
                <div style="font-size:36px;">🔴</div>
                <h5 class="mt-1 mb-0">Critical</h5>
                <h2 class="text-danger fw-bold">
                    <?php echo $critical_count; ?>
                </h2>
                <small class="text-muted">
                    Cooked 4+ hours ago
                </small>
            </div>
        </div>

        <div class="col-md-4 col-6">
            <div class="priority-card"
                 style="border-left:5px solid #ffc107;">
                <div style="font-size:36px;">🟡</div>
                <h5 class="mt-1 mb-0">Urgent</h5>
                <h2 class="text-warning fw-bold">
                    <?php echo $urgent_count; ?>
                </h2>
                <small class="text-muted">
                    Cooked 2–4 hours ago
                </small>
            </div>
        </div>

        <div class="col-md-4 col-6">
            <div class="priority-card"
                 style="border-left:5px solid #198754;">
                <div style="font-size:36px;">🟢</div>
                <h5 class="mt-1 mb-0">Fresh</h5>
                <h2 class="text-success fw-bold">
                    <?php echo $fresh_priority_count; ?>
                </h2>
                <small class="text-muted">
                    Cooked under 2 hours ago
                </small>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card glass-card">
                <div class="card-body text-center">

                    <h4 class="mb-4">⚡ Quick Actions</h4>

                    <a href="add_donation.php"
                       class="btn btn-success btn-lg m-2">
                        ➕ Add Donation
                    </a>

                    <a href="view_donations.php"
                       class="btn btn-primary btn-lg m-2">
                        📋 View Donations
                    </a>

                    <a href="reports.php"
                       class="btn btn-info btn-lg m-2">
                        📊 Reports
                    </a>

                    <a href="smart_matching.php"
                       class="btn btn-warning btn-lg m-2">
                        📍 Smart Matching
                    </a>

                    <a href="emergency_requests.php"
                       class="btn btn-danger btn-lg m-2">
                        🚨 Emergency Requests
                        <?php if($emergency_pending > 0): ?>
                        <span class="badge bg-light text-danger ms-1">
                            <?php echo $emergency_pending; ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <?php if($_SESSION['role'] == "volunteer"): ?>

                    <a href="available_donations.php"
                       class="btn btn-secondary btn-lg m-2">
                        🚚 Available Donations
                    </a>

                    <a href="my_tasks.php"
                       class="btn btn-dark btn-lg m-2">
                        ✅ My Tasks
                    </a>

                    <a href="volunteer_dashboard.php"
                       class="btn btn-outline-success btn-lg m-2">
                        <i class="bi bi-person-hearts"></i>
                        Volunteer Dashboard
                    </a>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Smart Features Section -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card glass-card">
                <div class="card-body">

                    <h3 class="text-success text-center mb-4">
                        🧠 Smart Food Redistribution Features
                    </h3>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="feature-box">
                                <div class="feature-icon">⏱️</div>
                                <h5 class="mt-2">
                                    Food Priority Tracking
                                </h5>
                                <p class="text-muted small">
                                    Food is auto-categorized as
                                    🔴 Critical, 🟡 Urgent, or 🟢 Fresh
                                    based on cooking time.
                                    Critical food is distributed first
                                    to prevent wastage.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-box">
                                <div class="feature-icon">📍</div>
                                <h5 class="mt-2">
                                    Smart Food Matching
                                </h5>
                                <p class="text-muted small">
                                    System auto-matches food donations
                                    with organizations based on
                                    location, quantity needed,
                                    and food priority score.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-box">
                                <div class="feature-icon">🚨</div>
                                <h5 class="mt-2">
                                    Emergency Request System
                                </h5>
                                <p class="text-muted small">
                                    Volunteers and NGO workers can
                                    report hungry people — roadside
                                    beggars, children, flood victims —
                                    for immediate food delivery.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-box">
                                <div class="feature-icon">📅</div>
                                <h5 class="mt-2">
                                    Expiry Tracking
                                </h5>
                                <p class="text-muted small">
                                    Food nearing expiry is highlighted
                                    for faster distribution to
                                    minimize food wastage.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-box">
                                <div class="feature-icon">🍱</div>
                                <h5 class="mt-2">
                                    Food Categorization
                                </h5>
                                <p class="text-muted small">
                                    Donations are grouped by category —
                                    Cooked Food, Fruits, Vegetables,
                                    Packed Food — for better
                                    organization matching.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-box">
                                <div class="feature-icon">🚚</div>
                                <h5 class="mt-2">
                                    Volunteer Tracking
                                </h5>
                                <p class="text-muted small">
                                    Full donation lifecycle tracked —
                                    Donated → Accepted → Picked Up
                                    → Distributed — with volunteer
                                    accountability at every step.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- SDG Footer -->
    <div class="text-center mt-4 text-muted small">
        <p>
            🌍 Aligned with
            <strong>SDG 2 — Zero Hunger</strong> |
            <strong>SDG 1 — No Poverty</strong> |
            <strong>SDG 10 — Reduced Inequalities</strong>
            <br>
            FoodShare 2.0 — Community Service Project
        </p>
    </div>

</div>

</body>
</html>