<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query(
$conn,
"SELECT * FROM food_donations
WHERE user_id='$user_id'
ORDER BY
    CASE priority
        WHEN 'Critical' THEN 1
        WHEN 'Urgent'   THEN 2
        WHEN 'Fresh'    THEN 3
        ELSE 4
    END ASC,
id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Donations - FoodShare 2.0</title>

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

.donation-card{
    background:rgba(255,255,255,0.92);
    backdrop-filter:blur(6px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
}

.donation-card:hover{
    transform:translateY(-6px);
}

.donation-card.priority-critical{
    border-left:5px solid #dc3545 !important;
}

.donation-card.priority-urgent{
    border-left:5px solid #ffc107 !important;
}

.donation-card.priority-fresh{
    border-left:5px solid #198754 !important;
}

.btn{
    border-radius:12px;
}

</style>

</head>

<body>

<div class="container py-4">

    <div class="card glass-card mb-4">
        <div class="card-body text-center">
            <h2 class="text-success fw-bold">
                <i class="bi bi-box2-heart-fill"></i>
                FoodShare 2.0
            </h2>
            <p class="mb-0 text-muted">
                Connecting surplus food with people who need it.
                Together we can reduce food waste and fight hunger.
            </p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <h2 class="fw-bold">
            <i class="bi bi-basket2-fill text-success"></i>
            My Donations
        </h2>

        <div class="d-flex gap-2">
            <a href="add_donation.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Add Donation
            </a>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
        </div>

    </div>

    <!-- Priority Legend -->
    <div class="card glass-card mb-4 p-3">
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <span>
                <span class="badge bg-danger fs-6">🔴 Critical</span>
                &nbsp;Cooked 4+ hours ago — Distribute immediately
            </span>
            <span>
                <span class="badge bg-warning text-dark fs-6">🟡 Urgent</span>
                &nbsp;Cooked 2–4 hours ago — Distribute soon
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

// ── Donation Status Badge ─────────────────────────
$status = $row['status'];
$badge  = "success";

if($status == "Accepted")
    $badge = "primary";
elseif($status == "Picked Up")
    $badge = "warning";
elseif($status == "Distributed")
    $badge = "danger";

// ── Expiry Date Status ────────────────────────────
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

// ── Cooked Time Priority ──────────────────────────
$priority       = "Fresh";
$priority_badge = "success";
$priority_icon  = "🟢";
$card_class     = "priority-fresh";
$cooked_display = "Not entered";

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
        $priority       = "Critical";
        $priority_badge = "danger";
        $priority_icon  = "🔴";
        $card_class     = "priority-critical";
    }

    $cooked_display = date("d M Y, h:i A", strtotime($row['cooked_time']));
}

?>

        <div class="col-md-6 mb-4">

            <div class="card donation-card <?php echo $card_class; ?> h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="text-success mb-0">
                            🍱 <?php echo $row['food_name']; ?>
                        </h4>
                        <span class="badge bg-<?php echo $priority_badge; ?> fs-6">
                            <?php echo $priority_icon . " " . $priority; ?>
                        </span>
                    </div>

                    <hr>

                    <p><strong>Quantity:</strong> <?php echo $row['quantity']; ?></p>

                    <p>
                        <strong>Category:</strong>
                        <span class="badge bg-info">
                            <?php echo $row['category']; ?>
                        </span>
                    </p>

                    <p><strong>Contact:</strong> <?php echo $row['contact_number']; ?></p>

                    <p><strong>Donation Date:</strong> <?php echo $row['donation_date']; ?></p>

                    <p><strong>Expiry Date:</strong> <?php echo $row['expiry_date']; ?></p>

                    <p><strong>⏱️ Cooked At:</strong> <?php echo $cooked_display; ?></p>

                    <p>
                        <strong>Food Freshness (Expiry):</strong>
                        <span class="badge bg-<?php echo $expiry_badge; ?> fs-6">
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

                    <p>
                        <strong>Donation Status:</strong>
                        <span class="badge bg-<?php echo $badge; ?> fs-6">
                            <?php echo $status; ?>
                        </span>
                    </p>

                    <div class="d-flex gap-2 mt-3">

                        <a href="edit_donation.php?id=<?php echo $row['id']; ?>"
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </a>

                        <a href="delete_donation.php?id=<?php echo $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this donation?')">
                            <i class="bi bi-trash"></i>
                            Delete
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
            <div class="alert alert-info text-center">
                No donations found.
                <a href="add_donation.php">Add your first donation!</a>
            </div>
        </div>

<?php
}
?>

    </div>

</div>

</body>
</html>