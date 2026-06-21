<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['add_donation']))
{
    $user_id = $_SESSION['user_id'];

    $food_name      = $_POST['food_name'];
    $category       = $_POST['category'];
    $quantity       = $_POST['quantity'];
    $pickup_address = $_POST['pickup_address'];
    $contact_number = $_POST['contact_number'];
    $donation_date  = $_POST['donation_date'];
    $expiry_date    = $_POST['expiry_date'];
    $cooked_time    = $_POST['cooked_time'];

    // ── Priority Calculation ──────────────────────────────
    $cooked_timestamp = strtotime($cooked_time);
    $now              = time();
    $hours_ago        = ($now - $cooked_timestamp) / 3600;

    if($hours_ago < 2)
    {
        $priority = "Fresh";
    }
    elseif($hours_ago < 4)
    {
        $priority = "Urgent";
    }
    else
    {
        $priority = "Critical";
    }
    // ─────────────────────────────────────────────────────

    $sql = "INSERT INTO food_donations
    (user_id, food_name, category, quantity, pickup_address,
     contact_number, donation_date, expiry_date, cooked_time, priority)
    VALUES
    ('$user_id','$food_name','$category','$quantity','$pickup_address',
     '$contact_number','$donation_date','$expiry_date','$cooked_time','$priority')";

    if(mysqli_query($conn, $sql))
    {
        if($priority == "Fresh")
            $badge = "<span class='badge bg-success fs-6'>🟢 Fresh</span>";
        elseif($priority == "Urgent")
            $badge = "<span class='badge bg-warning text-dark fs-6'>🟡 Urgent</span>";
        else
            $badge = "<span class='badge bg-danger fs-6'>🔴 Critical</span>";

        $message = "
        <div class='alert alert-success'>
            ✅ Food Donation Added Successfully!<br>
            Priority Status: $badge
        </div>";
    }
    else
    {
        $message = "
        <div class='alert alert-danger'>
            ❌ Error adding donation. Please try again.
        </div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Donation - FoodShare 2.0</title>

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
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
}

.form-control{
    border-radius:12px;
}

.btn{
    border-radius:12px;
}

.priority-info{
    background:rgba(255,255,255,0.85);
    border-radius:14px;
    border-left:5px solid #198754;
    padding:14px 18px;
    margin-bottom:10px;
    font-size:0.93rem;
}

</style>

</head>

<body>

<div class="container py-5">

    <div class="card glass-card border-0 mb-4">
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

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card glass-card border-0">

                <div class="card-header bg-success text-white rounded-top">
                    <h3 class="mb-0">
                        <i class="bi bi-basket2-fill"></i>
                        Add Food Donation
                    </h3>
                </div>

                <div class="card-body">

                    <?php echo $message; ?>

                    <!-- Priority Info Box -->
                    <div class="priority-info mb-4">
                        <strong>⏱️ Food Priority System:</strong><br>
                        🟢 <strong>Fresh</strong> — Cooked less than 2 hours ago<br>
                        🟡 <strong>Urgent</strong> — Cooked 2 to 4 hours ago<br>
                        🔴 <strong>Critical</strong> — Cooked more than 4 hours ago<br>
                        <small class="text-muted">Priority is auto-calculated from your cooked time.</small>
                    </div>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Food Name</label>
                            <input
                                type="text"
                                name="food_name"
                                class="form-control"
                                placeholder="Example: Veg Biryani"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Food Category</label>
                            <select name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Cooked Food">Cooked Food</option>
                                <option value="Packed Food">Packed Food</option>
                                <option value="Fruits">Fruits</option>
                                <option value="Vegetables">Vegetables</option>
                                <option value="Bakery Items">Bakery Items</option>
                                <option value="Beverages">Beverages</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <input
                                type="text"
                                name="quantity"
                                class="form-control"
                                placeholder="Example: 25 Meals"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                When Was The Food Cooked?
                                <small class="text-muted fw-normal">
                                    (Used to calculate freshness priority)
                                </small>
                            </label>
                            <input
                                type="datetime-local"
                                name="cooked_time"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pickup Address</label>
                            <textarea
                                name="pickup_address"
                                class="form-control"
                                rows="3"
                                placeholder="Enter pickup location"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Contact Number</label>
                            <input
                                type="text"
                                name="contact_number"
                                class="form-control"
                                placeholder="Enter contact number"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Donation Date</label>
                            <input
                                type="date"
                                name="donation_date"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Expiry Date</label>
                            <input
                                type="date"
                                name="expiry_date"
                                class="form-control"
                                required>
                        </div>

                        <div class="d-flex gap-2 mt-4">

                            <button
                                type="submit"
                                name="add_donation"
                                class="btn btn-success">
                                <i class="bi bi-plus-circle"></i>
                                Add Donation
                            </button>

                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="bi bi-house-door"></i>
                                Dashboard
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