<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$result = mysqli_query(
$conn,
"SELECT * FROM food_donations
WHERE status IN ('Accepted','Picked Up','Distributed')
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>My Tasks - FoodShare 2.0</title>

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

</style>

</head>

<body>

<div class="container py-5">

<div class="card glass-card mb-4">

<div class="card-body text-center">

<h2 class="text-primary fw-bold">
🚚 My Tasks
</h2>

<p class="mb-0">
Track accepted, picked up and distributed donations
</p>

</div>

</div>

<?php

if(mysqli_num_rows($result) > 0)
{

while($row = mysqli_fetch_assoc($result))
{
?>

<div class="card glass-card mb-4">

<div class="card-body">

<h4 class="text-success">
🍱 <?php echo $row['food_name']; ?>
</h4>

<hr>

<p>
<strong>Quantity:</strong>
<?php echo $row['quantity']; ?>
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
<strong>Status:</strong>

<?php if($row['status']=="Accepted"){ ?>

<span class="badge bg-warning text-dark">
Accepted
</span>

<?php } elseif($row['status']=="Picked Up"){ ?>

<span class="badge bg-primary">
Picked Up
</span>

<?php } else { ?>

<span class="badge bg-success">
Distributed
</span>

<?php } ?>

</p>

<?php if($row['status'] == 'Accepted') { ?>

<a
href="mark_picked.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning">

🚚 Mark Picked Up

</a>

<?php } elseif($row['status'] == 'Picked Up') { ?>

<a
href="mark_distributed.php?id=<?php echo $row['id']; ?>"
class="btn btn-success">

❤️ Mark Distributed

</a>

<?php } elseif($row['status'] == 'Distributed') { ?>

<span class="badge bg-success fs-6">
✅ Completed
</span>

<?php } ?>

</div>

</div>

<?php
}
}
else
{
?>

<div class="alert alert-warning text-center">

No tasks available.

</div>

<?php
}
?>

<div class="text-center mt-4">

<a
href="volunteer_dashboard.php"
class="btn btn-dark btn-lg">

⬅ Back to Volunteer Dashboard

</a>

</div>

</div>

</body>
</html>