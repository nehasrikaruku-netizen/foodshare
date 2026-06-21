<?php

include("db.php");

$message = "";

if(isset($_POST['register']))
{
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    $check = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email'");

    if($check && mysqli_num_rows($check) > 0)
    {
        $message = "
        <div class='alert alert-danger'>
            ❌ Email already exists! Please login instead.
        </div>";
    }
    else
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
        (fullname, email, password, role)
        VALUES
        ('$fullname','$email','$password','$role')";

        if(mysqli_query($conn, $sql))
        {
            $message = "
            <div class='alert alert-success'>
                ✅ Registration Successful!
                <a href='login.php' class='fw-bold'>
                    Login Now →
                </a>
            </div>";
        }
        else
        {
            $message = "
            <div class='alert alert-danger'>
                ❌ Registration Failed: " . mysqli_error($conn) . "
            </div>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register - FoodShare 2.0</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:
    linear-gradient(
        rgba(255,255,255,0.75),
        rgba(255,255,255,0.75)
    ),
    url('foodshare-bg.png');
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    min-height:100vh;
}

.register-card{
    background:rgba(255,255,255,0.95);
    backdrop-filter:blur(8px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 30px rgba(0,0,0,0.15);
}

.brand-box{
    background:rgba(255,255,255,0.95);
    backdrop-filter:blur(8px);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.10);
    padding:20px;
    text-align:center;
    margin-bottom:20px;
}

.form-control,
.form-select{
    border-radius:12px;
    padding:10px 14px;
}

.btn{
    border-radius:12px;
    padding:10px;
}

.role-info{
    background:#f0fff4;
    border-radius:12px;
    border-left:4px solid #198754;
    padding:10px 14px;
    font-size:0.88rem;
    margin-top:6px;
}

</style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <!-- Brand Box -->
            <div class="brand-box">
                <h2 class="text-success fw-bold mb-1">
                    <i class="bi bi-box2-heart-fill"></i>
                    FoodShare 2.0
                </h2>
                <p class="text-muted mb-0">
                    Join us in fighting hunger and
                    reducing food waste in our community. 🙏
                </p>
            </div>

            <!-- Register Card -->
            <div class="card register-card">

                <div class="card-header bg-success text-white text-center"
                     style="border-radius:20px 20px 0 0;">
                    <h3 class="mb-0">
                        <i class="bi bi-person-plus-fill"></i>
                        Create Account
                    </h3>
                </div>

                <div class="card-body p-4">

                    <?php echo $message; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person"></i>
                                Full Name
                            </label>
                            <input
                                type="text"
                                name="fullname"
                                class="form-control"
                                placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope"></i>
                                Email Address
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-lock"></i>
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Create a password"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-people"></i>
                                Select Your Role
                            </label>
                            <select
                                name="role"
                                class="form-select"
                                required>
                                <option value="">
                                    -- Select Role --
                                </option>
                                <option value="donor">
                                    🍱 Donor — I want to donate food
                                </option>
                                <option value="volunteer">
                                    🚚 Volunteer — I want to deliver food
                                </option>
                            </select>

                            <!-- Role Info Box -->
                            <div class="role-info mt-2">
                                🍱 <strong>Donor</strong> —
                                Add food donations, track status<br>
                                🚚 <strong>Volunteer</strong> —
                                Accept pickups, deliver to needy people,
                                report emergency hunger requests
                            </div>
                        </div>

                        <button
                            type="submit"
                            name="register"
                            class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-person-check"></i>
                            Create Account
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Already have an account?
                            <a href="login.php"
                               class="text-success fw-bold">
                                Login Here
                            </a>
                        </p>
                    </div>

                </div>

            </div>

            <!-- SDG Note -->
            <div class="text-center mt-3 text-muted small">
                🌍 Aligned with
                <strong>SDG 2 — Zero Hunger</strong><br>
                Community Service Project
            </div>

        </div>

    </div>

</div>

</body>
</html>