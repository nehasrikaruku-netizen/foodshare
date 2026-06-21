<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$total = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations")
)['total'];

$distributed = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) as total FROM food_donations WHERE status='Distributed'")
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

$meals_served = $distributed * 20;

?>

<!DOCTYPE html>
<html>

<head>

<title>FoodShare Reports</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
}

.stats-card:hover{
    transform:translateY(-8px);
}

</style>

</head>

<body>

<div class="container py-5">

<div class="card glass-card mb-5">

<div class="card-body text-center">

<h2 class="text-success fw-bold">
📊 FoodShare 2.0 Reports
</h2>

<p class="mb-0">
Impact and donation statistics
</p>

</div>

</div>

<div class="row g-4">

<div class="col-md-4">
<div class="card stats-card">
<div class="card-body text-center">
<h5>Total Donations</h5>
<h2><?php echo $total; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card stats-card">
<div class="card-body text-center">
<h5>Available Donations</h5>
<h2><?php echo $available; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card stats-card">
<div class="card-body text-center">
<h5>Accepted Donations</h5>
<h2><?php echo $accepted; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card stats-card">
<div class="card-body text-center">
<h5>Picked Up</h5>
<h2><?php echo $picked; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card stats-card">
<div class="card-body text-center">
<h5>Distributed</h5>
<h2><?php echo $distributed; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card stats-card">
<div class="card-body text-center">
<h5>Meals Served</h5>
<h2><?php echo $meals_served; ?></h2>
</div>
</div>
</div>

</div>

<div class="text-center mt-5">

<a href="dashboard.php"
class="btn btn-dark btn-lg">

⬅ Back to Dashboard

</a>

</div>

</div>

</body>
</html>