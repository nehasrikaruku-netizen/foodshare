<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

// ── Fetch all available donations ─────────────────────────────
$donations_query = mysqli_query(
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

// ── Fetch all organizations ───────────────────────────────────
$organizations_query = mysqli_query(
$conn,
"SELECT * FROM organizations
ORDER BY meals_needed DESC"
);

$donations_list = [];
while($d = mysqli_fetch_assoc($donations_query))
{
    $donations_list[] = $d;
}

$org_list = [];
while($o = mysqli_fetch_assoc($organizations_query))
{
    $org_list[] = $o;
}

// ── Smart Matching Logic ──────────────────────────────────────
function calculate_match_score($donation, $org)
{
    $score = 0;
    $reasons = [];

    // ── 1. Priority Score (max 40 points) ────────────────────
    $priority = isset($donation['priority']) ? $donation['priority'] : 'Fresh';
    if($priority == 'Critical')
    {
        $score += 40;
        $reasons[] = "🔴 Critical food needs immediate delivery (+40)";
    }
    elseif($priority == 'Urgent')
    {
        $score += 25;
        $reasons[] = "🟡 Urgent food should be delivered soon (+25)";
    }
    else
    {
        $score += 10;
        $reasons[] = "🟢 Fresh food available (+10)";
    }

    // ── 2. Location Match Score (max 35 points) ───────────────
    $org_location     = strtolower(trim($org['location']));
    $donation_address = strtolower(trim($donation['pickup_address']));

    // Split org location into individual words and check each
    $location_words   = preg_split('/[\s,]+/', $org_location);
    $location_matched = false;

    foreach($location_words as $word)
    {
        if(strlen($word) > 3 && strpos($donation_address, $word) !== false)
        {
            $score += 35;
            $reasons[] = "📍 Location match: '$word' found in pickup address (+35)";
            $location_matched = true;
            break;
        }
    }

    if(!$location_matched)
    {
        // Partial score if same city area keywords found
        $city_keywords = ['visakhapatnam', 'vizag', 'vsp'];
        foreach($city_keywords as $city)
        {
            if(strpos($donation_address, $city) !== false)
            {
                $score += 10;
                $reasons[] = "📍 Same city area (+10)";
                break;
            }
        }
    }

    // ── 3. Quantity Match Score (max 25 points) ───────────────
    preg_match('/\d+/', $donation['quantity'], $qty_match);
    $donation_qty = isset($qty_match[0]) ? (int)$qty_match[0] : 0;
    $needed       = (int)$org['meals_needed'];

    if($donation_qty >= $needed)
    {
        $score += 25;
        $reasons[] = "📦 Fully covers requirement: $donation_qty meals ≥ $needed needed (+25)";
    }
    elseif($donation_qty >= ($needed * 0.75))
    {
        $score += 18;
        $reasons[] = "📦 Covers 75%+ of requirement: $donation_qty of $needed meals (+18)";
    }
    elseif($donation_qty >= ($needed * 0.5))
    {
        $score += 10;
        $reasons[] = "📦 Covers 50%+ of requirement: $donation_qty of $needed meals (+10)";
    }
    else
    {
        $score += 3;
        $reasons[] = "📦 Partial coverage: $donation_qty of $needed meals needed (+3)";
    }

    return [
        'score'   => $score,
        'reasons' => $reasons
    ];
}

// ── Build matches ─────────────────────────────────────────────
$matches = [];

foreach($org_list as $org)
{
    $best_match   = null;
    $best_score   = -1;
    $best_reasons = [];

    foreach($donations_list as $donation)
    {
        $result = calculate_match_score($donation, $org);

        if($result['score'] > $best_score)
        {
            $best_score   = $result['score'];
            $best_match   = $donation;
            $best_reasons = $result['reasons'];
        }
    }

    $matches[] = [
        'org'     => $org,
        'donation'=> $best_match,
        'score'   => $best_score,
        'reasons' => $best_reasons
    ];
}

// Sort matches — highest score first
usort($matches, function($a, $b) {
    return $b['score'] - $a['score'];
});

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Smart Food Matching - FoodShare 2.0</title>

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

.match-card{
    background:rgba(255,255,255,0.95);
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
    transition:0.3s;
}

.match-card:hover{
    transform:translateY(-6px);
}

.match-card.priority-critical{
    border-left:6px solid #dc3545 !important;
}

.match-card.priority-urgent{
    border-left:6px solid #ffc107 !important;
}

.match-card.priority-fresh{
    border-left:6px solid #198754 !important;
}

.org-box{
    background:#f0fff4;
    border-radius:14px;
    padding:14px;
    border:1px solid #c3e6cb;
}

.donation-box{
    background:#fff8f0;
    border-radius:14px;
    padding:14px;
    border:1px solid #ffd699;
}

.reason-box{
    background:#f8f9fa;
    border-radius:12px;
    padding:12px;
    font-size:0.85rem;
    margin-top:12px;
}

.score-bar-wrap{
    background:#e9ecef;
    border-radius:10px;
    height:12px;
    margin-top:6px;
}

.score-bar-fill{
    height:12px;
    border-radius:10px;
    transition:width 1.5s ease;
}

.score-high   { background: linear-gradient(90deg,#198754,#20c997); }
.score-medium { background: linear-gradient(90deg,#ffc107,#fd7e14); }
.score-low    { background: linear-gradient(90deg,#dc3545,#e83e8c); }

.btn{ border-radius:12px; }

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
                Smart Food Matching — Auto-connecting donations with organizations
            </p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="fw-bold">📍 Smart Food Matching</h3>
        <small class="text-muted">Scored by Priority + Location + Quantity</small>
    </div>

    <!-- Scoring Explanation -->
    <div class="card glass-card mb-4 p-3">
        <h5 class="fw-bold text-center mb-3">
            🧠 How Matching Works
        </h5>
        <div class="row text-center g-2">
            <div class="col-md-4">
                <div class="p-2 rounded"
                     style="background:#fff3cd;border:1px solid #ffc107;">
                    <strong>⏱️ Food Priority</strong><br>
                    <small>
                        🔴 Critical = 40 pts<br>
                        🟡 Urgent = 25 pts<br>
                        🟢 Fresh = 10 pts
                    </small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-2 rounded"
                     style="background:#d1ecf1;border:1px solid #bee5eb;">
                    <strong>📍 Location Match</strong><br>
                    <small>
                        Same area = 35 pts<br>
                        Same city = 10 pts<br>
                        No match = 0 pts
                    </small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-2 rounded"
                     style="background:#d4edda;border:1px solid #c3e6cb;">
                    <strong>📦 Quantity Match</strong><br>
                    <small>
                        Full cover = 25 pts<br>
                        75%+ = 18 pts<br>
                        50%+ = 10 pts
                    </small>
                </div>
            </div>
        </div>
        <p class="text-center text-muted mt-2 mb-0">
            <small>Maximum possible score = 100 points</small>
        </p>
    </div>

    <!-- Matches -->
    <h4 class="fw-bold mb-3">🤝 Best Matches Found</h4>

    <?php if(count($matches) == 0): ?>
        <div class="alert alert-info text-center">
            No organizations or donations found to match.
        </div>
    <?php else: ?>

    <div class="row g-4">

    <?php foreach($matches as $match):

        $org      = $match['org'];
        $donation = $match['donation'];
        $score    = $match['score'];
        $reasons  = $match['reasons'];

        // Score color
        $score_class = 'score-low';
        $score_label = 'Low Match';
        if($score >= 70)
        {
            $score_class = 'score-high';
            $score_label = 'Excellent Match';
        }
        elseif($score >= 40)
        {
            $score_class = 'score-medium';
            $score_label = 'Good Match';
        }

        if($donation)
        {
            $p          = $donation['priority'] ?? 'Fresh';
            $card_class = ($p=='Critical') ? 'priority-critical' :
                          (($p=='Urgent')  ? 'priority-urgent' : 'priority-fresh');
            $p_badge    = ($p=='Critical') ? 'danger' :
                          (($p=='Urgent')  ? 'warning text-dark' : 'success');
            $p_icon     = ($p=='Critical') ? '🔴' :
                          (($p=='Urgent')  ? '🟡' : '🟢');
        }
        else
        {
            $card_class = 'priority-fresh';
            $p_badge    = 'secondary';
            $p_icon     = '❓';
            $p          = 'Unknown';
        }

        $org_type_icon = '🏢';
        if(stripos($org['org_type'], 'Old Age') !== false)
            $org_type_icon = '👴';
        elseif(stripos($org['org_type'], 'Orphanage') !== false)
            $org_type_icon = '👶';
        elseif(stripos($org['org_type'], 'Shelter') !== false)
            $org_type_icon = '🏠';

    ?>

        <div class="col-lg-6">
            <div class="card match-card <?php echo $card_class; ?>">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0">
                            <?php echo $org_type_icon . " " . $org['org_name']; ?>
                        </h5>
                        <span class="badge bg-<?php
                            echo ($score>=70) ? 'success' :
                                 (($score>=40) ? 'warning text-dark' : 'danger'); ?>
                             fs-6">
                            <?php echo $score; ?>/100
                        </span>
                    </div>

                    <!-- Score Bar -->
                    <div class="score-bar-wrap mb-1">
                        <div class="score-bar-fill <?php echo $score_class; ?>"
                             style="width:<?php echo min(100,$score); ?>%">
                        </div>
                    </div>
                    <small class="text-muted"><?php echo $score_label; ?></small>

                    <hr>

                    <!-- Org + Donation Side by Side -->
                    <div class="row g-2 align-items-start">

                        <div class="col-5">
                            <div class="org-box">
                                <div class="fw-bold text-success mb-1">
                                    🏢 Needs
                                </div>
                                <strong><?php echo $org['org_name']; ?></strong><br>
                                <small class="text-muted">
                                    <?php echo $org['org_type']; ?>
                                </small><br>
                                <div class="mt-1">
                                    📍 <?php echo $org['location']; ?>
                                </div>
                                <div class="mt-1">
                                    🍽️ <strong>
                                        <?php echo $org['meals_needed']; ?> Meals
                                    </strong>
                                </div>
                                <div class="mt-1 small text-muted">
                                    📞 <?php echo $org['contact']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-2 text-center"
                             style="font-size:28px;padding-top:30px;">
                            ➡️
                        </div>

                        <div class="col-5">
                        <?php if($donation): ?>
                            <div class="donation-box">
                                <div class="fw-bold text-warning mb-1">
                                    🍱 Best Match
                                </div>
                                <strong>
                                    <?php echo $donation['food_name']; ?>
                                </strong><br>
                                <span class="badge bg-<?php echo $p_badge; ?> mt-1">
                                    <?php echo $p_icon . " " . $p; ?>
                                </span><br>
                                <div class="mt-1">
                                    📦 <?php echo $donation['quantity']; ?>
                                </div>
                                <div class="mt-1 small text-muted">
                                    📍 <?php echo $donation['pickup_address']; ?>
                                </div>
                                <div class="mt-1 small text-muted">
                                    📞 <?php echo $donation['contact_number']; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="donation-box text-center text-muted">
                                <div class="fs-3">😔</div>
                                No donation available
                            </div>
                        <?php endif; ?>
                        </div>

                    </div>

                    <!-- Why This Match -->
                    <?php if(count($reasons) > 0): ?>
                    <div class="reason-box mt-3">
                        <strong>🧠 Why This Match?</strong><br>
                        <?php foreach($reasons as $reason): ?>
                            <div class="mt-1">✔ <?php echo $reason; ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Accept Button -->
                    <?php if($donation): ?>
                    <div class="mt-3">
                        <a href="accept_donation.php?id=<?php echo $donation['id']; ?>"
                           class="btn btn-<?php echo ($p=='Critical')?'danger':'success'; ?> w-100">
                            <?php echo ($p=='Critical')
                                ? '🚨 Accept — Urgent Delivery to '.$org['org_name']
                                : '✅ Accept & Deliver to '.$org['org_name']; ?>
                        </a>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

    </div>
    <?php endif; ?>

    <!-- Navigation -->
    <div class="text-center mt-5 d-flex justify-content-center gap-3 flex-wrap">
        <a href="volunteer_dashboard.php" class="btn btn-dark btn-lg">
            <i class="bi bi-arrow-left-circle"></i>
            Volunteer Dashboard
        </a>
        <a href="dashboard.php" class="btn btn-secondary btn-lg">
            <i class="bi bi-house-door"></i>
            Main Dashboard
        </a>
    </div>

</div>

</body>
</html>