<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT * FROM food_donations WHERE id='$id'");

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update_status']))
{
    $status = $_POST['status'];

    mysqli_query($conn,
    "UPDATE food_donations
    SET status='$status'
    WHERE id='$id'");

    header("Location:view_donations.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Update Status</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f8f9fa;">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>Update Donation Status</h3>

</div>

<div class="card-body">

<form method="POST">

<label class="mb-2">
Status
</label>

<select
name="status"
class="form-select mb-3">

<option value="Available">
Available
</option>

<option value="Accepted">
Accepted
</option>

<option value="Picked Up">
Picked Up
</option>

<option value="Distributed">
Distributed
</option>

</select>

<button
type="submit"
name="update_status"
class="btn btn-warning">

Update Status

</button>

<a
href="view_donations.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>