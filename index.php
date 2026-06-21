<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>FoodShare 2.0 — Fighting Hunger Together</title>

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

/* ── Hero Section ── */
<img src="/foodshare-hero.png"
     alt="FoodShare Hero Image"
     class="img-fluid mb-4 rounded-4 shadow"
     style="max-width:500px;">

.hero-section{
    padding:80px 0 60px 0;
}

.hero-title{
    font-size:3rem;
    font-weight:800;
    line-height:1.2;
    color:#1a1a1a;
}

.hero-title span{
    color:#198754;
}

.hero-subtitle{
    font-size:1.15rem;
    color:#555;
    margin-top:16px;
    line-height:1.7;
}

/* ── Hero Illustration Box ── */
.hero-illustration{
    background:rgba(255,255,255,0.92);
    border-radius:30px;
    box-shadow:0 12px 40px rgba(0,0,0,0.12);
    padding:40px 30px;
    text-align:center;
    position:relative;
    overflow:hidden;
}

.hero-illustration::before{
    content:'';
    position:absolute;
    top:-40px;
    right:-40px;
    width:150px;
    height:150px;
    background:rgba(25,135,84,0.08);
    border-radius:50%;
}

.hero-illustration::after{
    content:'';
    position:absolute;
    bottom:-30px;
    left:-30px;
    width:120px;
    height:120px;
    background:rgba(255,193,7,0.10);
    border-radius:50%;
}

.food-emoji-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
    margin-bottom:20px;
}

.food-emoji-item{
    background:rgba(255,255,255,0.95);
    border-radius:16px;
    padding:16px 8px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    font-size:2.5rem;
    transition:0.3s;
}

.food-emoji-item:hover{
    transform:translateY(-5px);
    box-shadow:0 8px 20px rgba(0,0,0,0.12);
}

.food-emoji-item p{
    font-size:0.75rem;
    color:#555;
    margin:6px 0 0 0;
    font-weight:600;
}

.hero-stats{
    display:flex;
    justify-content:center;
    gap:24px;
    flex-wrap:wrap;
    margin-top:16px;
}

.hero-stat{
    text-align:center;
}

.hero-stat h4{
    color:#198754;
    font-weight:800;
    margin:0;
    font-size:1.5rem;
}

.hero-stat p{
    color:#777;
    font-size:0.78rem;
    margin:0;
}

/* ── How It Works ── */
.how-section{
    padding:60px 0;
}

.section-title{
    font-size:2rem;
    font-weight:800;
    color:#1a1a1a;
}

.step-card{
    background:rgba(255,255,255,0.92);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.10);
    transition:0.3s;
    height:100%;
}

.step-card:hover{
    transform:translateY(-8px);
    box-shadow:0 12px 35px rgba(0,0,0,0.15);
}

.icon-circle{
    width:75px;
    height:75px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 16px auto;
    font-size:2rem;
}

.step-number{
    background:#198754;
    color:white;
    width:28px;
    height:28px;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:0.85rem;
    font-weight:700;
    margin-bottom:12px;
}

/* ── Features Section ── */
.features-section{
    padding:60px 0;
    background:rgba(255,255,255,0.60);
}

.feature-item{
    background:rgba(255,255,255,0.92);
    border-radius:16px;
    padding:24px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    transition:0.3s;
    height:100%;
}

.feature-item:hover{
    transform:translateY(-5px);
}

.feature-icon{
    font-size:2.5rem;
    margin-bottom:12px;
}

/* ── SDG Section ── */
.sdg-section{
    padding:50px 0;
}

.sdg-badge{
    background:rgba(255,255,255,0.92);
    border-radius:16px;
    padding:20px;
    text-align:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    transition:0.3s;
}

.sdg-badge:hover{
    transform:translateY(-4px);
}

/* ── CTA Section ── */
.cta-section{
    background:linear-gradient(135deg,#198754,#20c997);
    padding:60px 0;
    text-align:center;
    color:white;
}

/* ── Footer ── */
footer{
    background:#198754;
    color:white;
    padding:20px 0;
    text-align:center;
}

.btn{
    border-radius:12px;
}

</style>

</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">

        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="#">
            <img src="foodshare-logo.png"
     alt="FoodShare 2.0 Logo"
     style="height:42px;width:42px;border-radius:50%;background:white;padding:2px;">
            FoodShare 2.0
        </a>

        <div class="d-flex gap-2">
            <a href="login.php" class="btn btn-light">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </a>
            <a href="register.php" class="btn btn-warning">
                <i class="bi bi-person-plus"></i>
                Register
            </a>
        </div>

    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">


        

            <!-- Left Text -->
            <div class="col-lg-6">

                <div class="mb-3">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        🌍 SDG 2 — Zero Hunger
                    </span>
                </div>

                <h1 class="hero-title">
                    Reduce Food Waste,<br>
                    <span>Feed More Lives</span>
                </h1>

                <p class="hero-subtitle">
                    FoodShare 2.0 connects food donors,
                    volunteers and needy communities —
                    roadside beggars, hungry children,
                    flood victims — to ensure surplus food
                    reaches those who need it most. 🙏
                </p>

                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="register.php"
                       class="btn btn-success btn-lg px-4">
                        <i class="bi bi-person-plus"></i>
                        Get Started — Free
                    </a>
                    <a href="login.php"
                       class="btn btn-outline-success btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="d-flex gap-4 mt-5 flex-wrap">
                    <div>
                        <h4 class="text-success fw-bold mb-0">🍱</h4>
                        <small class="text-muted">Food Donations</small>
                    </div>
                    <div>
                        <h4 class="text-success fw-bold mb-0">🚚</h4>
                        <small class="text-muted">Volunteers</small>
                    </div>
                    <div>
                        <h4 class="text-success fw-bold mb-0">❤️</h4>
                        <small class="text-muted">Lives Impacted</small>
                    </div>
                    <div>
                        <h4 class="text-success fw-bold mb-0">🏢</h4>
                        <small class="text-muted">Organizations</small>
                    </div>
                </div>

            </div>

            <!-- Right Illustration -->
            <div class="col-lg-6 text-center">

                <img src="foodshare-logo.png"
                     alt="FoodShare 2.0 Logo"
                     class="img-fluid mb-4"
                     style="max-width:320px;">

                <div class="hero-illustration">

                    <div class="food-emoji-grid">
                        <div class="food-emoji-item">
                            🍱
                            <p>Cooked Food</p>
                        </div>
                        <div class="food-emoji-item">
                            🥗
                            <p>Vegetables</p>
                        </div>
                        <div class="food-emoji-item">
                            🍎
                            <p>Fruits</p>
                        </div>
                        <div class="food-emoji-item">
                            🥖
                            <p>Bakery</p>
                        </div>
                        <div class="food-emoji-item">
                            📦
                            <p>Packed Food</p>
                        </div>
                        <div class="food-emoji-item">
                            🧃
                            <p>Beverages</p>
                        </div>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <h4>🔴</h4>
                            <p>Critical Priority</p>
                        </div>
                        <div class="hero-stat">
                            <h4>🟡</h4>
                            <p>Urgent Priority</p>
                        </div>
                        <div class="hero-stat">
                            <h4>🟢</h4>
                            <p>Fresh Priority</p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="badge bg-success px-3 py-2">
                            ⏱️ Smart Priority System
                        </span>
                        <span class="badge bg-warning text-dark px-3 py-2 ms-2">
                            📍 Smart Matching
                        </span>
                        <span class="badge bg-danger px-3 py-2 ms-2">
                            🚨 Emergency Alerts
                        </span>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-section">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-title">
                How FoodShare Works
            </h2>
            <p class="text-muted">
                Simple 3 step process to fight hunger
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card step-card">
                    <div class="card-body text-center p-4">
                        <div class="step-number">1</div>
                        <div class="icon-circle bg-success-subtle">
                            <i class="bi bi-basket2-fill text-success"></i>
                        </div>
                        <h5 class="fw-bold">Donate Food</h5>
                        <p class="text-muted">
                            Restaurants, hostels, and individuals
                            donate surplus food with cooked time
                            for priority tracking.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card step-card">
                    <div class="card-body text-center p-4">
                        <div class="step-number">2</div>
                        <div class="icon-circle bg-primary-subtle">
                            <i class="bi bi-person-hearts text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Volunteer Pickup</h5>
                        <p class="text-muted">
                            Volunteers accept donations,
                            pick up food and get matched
                            with nearby organizations.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card step-card">
                    <div class="card-body text-center p-4">
                        <div class="step-number">3</div>
                        <div class="icon-circle bg-danger-subtle">
                            <i class="bi bi-globe-asia-australia text-danger"></i>
                        </div>
                        <h5 class="fw-bold">Feed Communities</h5>
                        <p class="text-muted">
                            Food reaches roadside beggars,
                            hungry children, old age homes
                            and flood victims in need.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Smart Features -->
<section class="features-section">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-title">
                🧠 Smart Features
            </h2>
            <p class="text-muted">
                Technology that makes food distribution faster and smarter
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">⏱️</div>
                    <h5 class="fw-bold">Food Priority Tracking</h5>
                    <p class="text-muted">
                        Auto-categorizes food as
                        🔴 Critical, 🟡 Urgent, or 🟢 Fresh
                        based on cooking time.
                        Critical food is distributed first.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">📍</div>
                    <h5 class="fw-bold">Smart Food Matching</h5>
                    <p class="text-muted">
                        Automatically matches donations
                        with organizations based on
                        location, quantity and priority
                        for faster delivery.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">🚨</div>
                    <h5 class="fw-bold">Emergency Requests</h5>
                    <p class="text-muted">
                        Volunteers report hungry people
                        spotted on streets for immediate
                        food relief response from
                        donors and NGOs.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">📅</div>
                    <h5 class="fw-bold">Expiry Tracking</h5>
                    <p class="text-muted">
                        Food nearing expiry is highlighted
                        for faster distribution to
                        minimize food wastage.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">🏢</div>
                    <h5 class="fw-bold">Organization Matching</h5>
                    <p class="text-muted">
                        Old age homes, orphanages and
                        shelters get matched with the
                        right food donations based on
                        their requirements.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <h5 class="fw-bold">Live Dashboard</h5>
                    <p class="text-muted">
                        Real time stats on donations,
                        volunteers, priority food counts
                        and emergency requests all
                        in one place.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- SDG Goals -->
<section class="sdg-section">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-title">
                🌍 SDG Goals We Support
            </h2>
            <p class="text-muted">
                FoodShare 2.0 is aligned with
                United Nations Sustainable Development Goals
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-md-3 col-6">
                <div class="sdg-badge">
                    <div style="font-size:2.5rem;">🍽️</div>
                    <h6 class="fw-bold mt-2">SDG 2</h6>
                    <p class="text-muted small mb-0">Zero Hunger</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="sdg-badge">
                    <div style="font-size:2.5rem;">💰</div>
                    <h6 class="fw-bold mt-2">SDG 1</h6>
                    <p class="text-muted small mb-0">No Poverty</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="sdg-badge">
                    <div style="font-size:2.5rem;">⚖️</div>
                    <h6 class="fw-bold mt-2">SDG 10</h6>
                    <p class="text-muted small mb-0">Reduced Inequalities</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="sdg-badge">
                    <div style="font-size:2.5rem;">🤝</div>
                    <h6 class="fw-bold mt-2">SDG 17</h6>
                    <p class="text-muted small mb-0">Partnerships</p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="fw-bold mb-3">
            Ready to Fight Hunger?
        </h2>
        <p class="lead mb-4">
            Join FoodShare 2.0 today and help us
            ensure no food goes to waste while
            people go hungry.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="register.php"
               class="btn btn-light btn-lg px-5">
                <i class="bi bi-person-plus"></i>
                Join Now — Free
            </a>
            <a href="login.php"
               class="btn btn-outline-light btn-lg px-5">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <p class="mb-1">
            © 2026 FoodShare 2.0 — Community Service Project
        </p>
        <p class="mb-0 small">
            🌍 SDG 2 Zero Hunger |
            SDG 1 No Poverty |
            SDG 10 Reduced Inequalities
        </p>
    </div>
</footer>

</body>
</html>