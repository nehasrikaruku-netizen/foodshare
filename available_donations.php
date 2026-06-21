<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$result = mysqli_query(
$conn,
"SELECT * FROM food_donations
WHERE status='Available'
ORDER BY
    CASE priority
        WHEN 'Critical' THEN 1
        WHEN 'Urgent'   THEN 2
        WHEN 'Fresh'    THEN 3
        ELSE 4
    END ASC,
expiry_date ASC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Available Donations - FoodShare 2.0</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:
    linear-gradient(
        rgba(255,255,255,0.55),
        rgba(255,255,255,0.55)
    ),
    url('/foodshare-bg.png');
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

.donation-card{
    background:rgba(255,255,255,0.90);
    backdrop-filter:blur(8px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
}

.donation-card:hover{
    transform:translateY(-6px);
}

.donation-card.priority-critical{
    border-left:6px solid #dc3545 !important;
}

.donation-card.priority-urgent{
    border-left:6px solid #ffc107 !important;
}

.donation-card.priority-fresh{
    border-left:6px solid #198754 !important;
}

.urgent-banner{
    background:#dc3545;
    color:white;
    border-radius:10px;
    padding:6px 12px;
    font-weight:bold;
    font-size:0.85rem;
    margin-bottom:10px;
    display:inline-block;
    animation:blink 1.2s step-start infinite;
}

@keyframes blink{
    50%{ opacity:0.4; }
}

.btn{
    border-radius:12px;
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
                Connecting surplus food with people who need it.
            </p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="fw-bold">
            🍱 Available Donations
        </h3>
        <small class="text-muted">
            Sorted by food priority — Critical first
        </small>
    </div>

    <!-- Priority Legend -->
    <div class="card glass-card mb-4 p-3">
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <span>
                <span class="badge bg-danger fs-6">🔴 Critical</span>
                &nbsp;Cooked 4+ hours ago — Pick up immediately
            </span>
            <span>
                <span class="badge bg-warning text-dark fs-6">🟡 Urgent</span>
                &nbsp;Cooked 2–4 hours ago — Pick up soon
            </span>
            <span>
                <span class="badge bg-success fs-6">🟢 Fresh</span>
                &nbsp;Cooked under 2 hours ago — Good condition
            </span>
        </div>
    </div>

    <div class="row">

<?php

if(mysqli_num_rows($result) > 0)
{

while($row = mysqli_fetch_assoc($result))
{

// ── Expiry Date Status ────────────────────────────────
$today    = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime("+1 day"));

$expiry_status = "Fresh";
$expiry_badge  = "success";

if(!empty($row['expiry_date']))
{
    if($row['expiry_date'] < $today)
    {
        $expiry_status = "Expired";
        $expiry_badge  = "dark";
    }
    elseif($row['expiry_date'] == $today)
    {
        $expiry_status = "Expiring Today";
        $expiry_badge  = "danger";
    }
    elseif($row['expiry_date'] == $tomorrow)
    {
        $expiry_status = "Expiring Tomorrow";
        $expiry_badge  = "warning";
    }
}

// ── Cooked Time Priority ──────────────────────────────
$priority           = isset($row['priority']) ? $row['priority'] : "Fresh";
$priority_badge     = "success";
$priority_icon      = "🟢";
$card_class         = "priority-fresh";
$cooked_display     = "Not entered";
$show_urgent_banner = false;

if(!empty($row['cooked_time']))
{
    $hours_ago = (time() - strtotime($row['cooked_time'])) / 3600;

    if($hours_ago < 2)
    {
        $priority       = "Fresh";
        $priority_badge = "success";
        $priority_icon  = "🟢";
        $card_class     = "priority-fresh";
    }
    elseif($hours_ago < 4)
    {
        $priority       = "Urgent";
        $priority_badge = "warning";
        $priority_icon  = "🟡";
        $card_class     = "priority-urgent";
    }
    else
    {
        $priority            = "Critical";
        $priority_badge      = "danger";
        $priority_icon       = "🔴";
        $card_class          = "priority-critical";
        $show_urgent_banner  = true;
    }

    $cooked_display = date("d M Y, h:i A", strtotime($row['cooked_time']));
}

?>

        <div class="col-md-6 mb-4">

            <div class="card donation-card <?php echo $card_class; ?> h-100">

                <div class="card-body">

                    <?php if($show_urgent_banner): ?>
                    <div class="urgent-banner">
                        🚨 URGENT — Distribute This Food Immediately!
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-start mb-2">

                        <h4 class="text-success mb-0">
                            🍱 <?php echo $row['food_name']; ?>
                        </h4>

                        <span class="badge bg-<?php echo $priority_badge; ?> fs-6">
                            <?php echo $priority_icon . " " . $priority; ?>
                        </span>

                    </div>

                    <hr>

                    <p>
                        <strong>Quantity:</strong>
                        <?php echo $row['quantity']; ?>
                    </p>

                    <p>
                        <strong>Category:</strong>
                        <span class="badge bg-info">
                            <?php echo $row['category']; ?>
                        </span>
                    </p>

                    <p>
                        <strong>Contact:</strong>
                        <?php echo $row['contact_number']; ?>
                    </p>

                    <p>
                        <strong>Address:</strong>
                        <?php echo $row['pickup_address']; ?>
                    </p>

                    <p>
                        <strong>⏱️ Cooked At:</strong>
                        <?php echo $cooked_display; ?>
                    </p>

                    <p>
                        <strong>Expiry Date:</strong>
                        <?php echo $row['expiry_date']; ?>
                    </p>

                    <p>
                        <strong>Expiry Status:</strong>
                        <span class="badge bg-<?php echo $expiry_badge; ?>">
                        <?php
                        if($expiry_status == "Expiring Today")
                            echo "🔴 Expiring Today";
                        elseif($expiry_status == "Expiring Tomorrow")
                            echo "🟠 Expiring Tomorrow";
                        elseif($expiry_status == "Expired")
                            echo "⚫ Expired";
                        else
                            echo "🟢 Fresh";
                        ?>
                        </span>
                    </p>

                    <div class="mt-3">

                        <a href="accept_donation.php?id=<?php echo $row['id']; ?>"
                           class="btn btn-<?php echo ($priority == 'Critical') ? 'danger' : 'success'; ?> w-100">
                            <?php echo ($priority == 'Critical') ? '🚨 Accept — Urgent Pickup' : '✅ Accept Pickup'; ?>
                        </a>

                    </div>

                </div>

            </div>

        </div>

<?php
}
}
else
{
?>

        <div class="col-12">
            <div class="alert alert-warning text-center">
                No available donations found.
            </div>
        </div>

<?php
}
?>

    </div>

    <div class="text-center mt-4">
        <a href="volunteer_dashboard.php" class="btn btn-dark">
            <i class="bi bi-arrow-left-circle"></i>
            Back to Dashboard
        </a>
    </div>

</div>

</body>
</html>