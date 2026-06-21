<?php

session_start();
include("db.php");

$message = "";

if(isset($_POST['login']))
{
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $sql    = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password']))
        {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role']     = $row['role'];

            header("Location: dashboard.php");
            exit();
        }
        else
        {
            $message = "
            <div class='alert alert-danger'>
                ❌ Invalid Password! Please try again.
            </div>";
        }
    }
    else
    {
        $message = "
        <div class='alert alert-danger'>
            ❌ User Not Found! Please register first.
        </div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - FoodShare 2.0</title>

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

.login-card{
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

.form-control{
    border-radius:12px;
    padding:10px 14px;
}

.btn{
    border-radius:12px;
    padding:10px;
}

</style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <!-- Brand Box -->
            <div class="brand-box">
                <h2 class="text-success fw-bold mb-1">
                    <i class="bi bi-box2-heart-fill"></i>
                    FoodShare 2.0
                </h2>
                <p class="text-muted mb-0">
                    Connecting surplus food with people who need it.
                    Together we fight hunger. 🙏
                </p>
            </div>

            <!-- Login Card -->
            <div class="card login-card">

                <div class="card-header bg-success text-white text-center"
                     style="border-radius:20px 20px 0 0;">
                    <h3 class="mb-0">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login
                    </h3>
                </div>

                <div class="card-body p-4">

                    <?php echo $message; ?>

                    <form method="POST">

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

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-lock"></i>
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required>
                        </div>

                        <button
                            type="submit"
                            name="login"
                            class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Don't have an account?
                            <a href="register.php"
                               class="text-success fw-bold">
                                Register Here
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