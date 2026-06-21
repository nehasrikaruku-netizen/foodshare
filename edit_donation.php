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

if(isset($_POST['update']))
{
    $food_name = $_POST['food_name'];
    $quantity = $_POST['quantity'];
    $pickup_address = $_POST['pickup_address'];
    $contact_number = $_POST['contact_number'];
    $donation_date = $_POST['donation_date'];

    mysqli_query($conn,
    "UPDATE food_donations SET
    food_name='$food_name',
    quantity='$quantity',
    pickup_address='$pickup_address',
    contact_number='$contact_number',
    donation_date='$donation_date'
    WHERE id='$id'");

    header("Location:view_donations.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Donation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:
    linear-gradient(
        rgba(255,255,255,0.55),
        rgba(255,255,255,0.55)
    ),
    url('foodshare-bg.png');

    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.glass-card{
    background:rgba(255,255,255,0.90);
    backdrop-filter:blur(8px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
}

.form-control{
    border-radius:10px;
}

</style>

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card glass-card">

<div class="card-header bg-primary text-white text-center rounded-top">

<h3 class="mb-0">
✏️ Edit Donation
</h3>

</div>

<div class="card-body p-4">

<form method="POST">

<div class="mb-3">

<label class="form-label fw-bold">
Food Name
</label>

<input
type="text"
name="food_name"
class="form-control"
value="<?php echo $row['food_name']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label fw-bold">
Quantity
</label>

<input
type="text"
name="quantity"
class="form-control"
value="<?php echo $row['quantity']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label fw-bold">
Pickup Address
</label>

<textarea
name="pickup_address"
class="form-control"
rows="3"
required><?php echo $row['pickup_address']; ?></textarea>

</div>

<div class="mb-3">

<label class="form-label fw-bold">
Contact Number
</label>

<input
type="text"
name="contact_number"
class="form-control"
value="<?php echo $row['contact_number']; ?>"
required>

</div>

<div class="mb-4">

<label class="form-label fw-bold">
Donation Date
</label>

<input
type="date"
name="donation_date"
class="form-control"
value="<?php echo $row['donation_date']; ?>"
required>

</div>

<div class="text-center">

<button
type="submit"
name="update"
class="btn btn-primary px-4">

💾 Update Donation

</button>

<a href="view_donations.php"
class="btn btn-secondary px-4">

⬅ Back

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>