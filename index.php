<?php
require_once 'pdo.php';

$search = trim((string)($_GET['search'] ?? ''));
$filter = trim((string)($_GET['filter'] ?? ''));
$where = 'WHERE 1';
$params = [];

if ($search !== '') {
    $where .= ' AND fullname LIKE :search';
    $params['search'] = "%{$search}%";
}

if ($filter !== '') {
    $where .= ' AND job_title = :filter';
    $params['filter'] = $filter;
}

$query = "SELECT * FROM profiles $where ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$profiles = $stmt->fetchAll();

$jobTitles = $pdo->query('SELECT DISTINCT job_title FROM profiles ORDER BY job_title')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Portal | Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-loader" id="pageLoader">
    <div class="loader-ring"></div>
</div>
<?php include 'header.php'; ?>
<main class="page-content">
    <section class="hero-section">
        <div class="hero-copy">
            <span class="eyebrow">CV Portal</span>
            <h1>Modern CV Profile</h1>
            <p>Browse multiple candidates, search by name, filter by job title, and view full CV pages with clean responsive design.</p>
        </div>
        <div class="hero-actions">
            <form class="search-panel" method="get" action="index.php">
                <label class="sr-only" for="search">Search profiles</label>
                <input id="search" type="search" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                <label class="sr-only" for="filter">Filter by job title</label>
                <select id="filter" name="filter">
                    <option value="">All job titles</option>
                    <?php foreach ($jobTitles as $job): ?>
                        <option value="<?= htmlspecialchars($job['job_title'], ENT_QUOTES, 'UTF-8'); ?>" <?= $filter === $job['job_title'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($job['job_title'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-primary">Search</button>
            </form>
        </div>
    </section>

    <section class="featured-professions">
        <div class="section-heading">
            <span class="eyebrow">Most sought after professions</span>
            <h2>Top 3 roles employers are looking for today.</h2>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <h3>Full Stack Developer</h3>
                <p>Skilled in both frontend and backend development for modern web applications.</p>
                <form method="get" action="index.php" class="profession-filter-form">
                    <input type="hidden" name="filter" value="Full Stack Developer">
                    <button type="submit" class="btn-secondary">See Full Stack Developers</button>
                </form>
            </article>
            <article class="feature-card">
                <h3>UI/UX Designer</h3>
                <p>Designs user-friendly interfaces and experiences that drive engagement.</p>
                <form method="get" action="index.php" class="profession-filter-form">
                    <input type="hidden" name="filter" value="UI/UX Designer">
                    <button type="submit" class="btn-secondary">See UI/UX Designers</button>
                </form>
            </article>
            <article class="feature-card">
                <h3>Data Analyst</h3>
                <p>Transforms data into insights to support smarter business decisions.</p>
                <form method="get" action="index.php" class="profession-filter-form">
                    <input type="hidden" name="filter" value="Data Analyst">
                    <button type="submit" class="btn-secondary">See Data Analysts</button>
                </form>
            </article>
        </div>
    </section>

    <section class="slider-section">
        <div class="section-heading">
            <span class="eyebrow">Featured profiles</span>
            <h2>Choose a candidate and explore their full CV.</h2>
        </div>

        <?php if (empty($profiles)): ?>
            <p class="empty-message">No profiles match your search. Try another name or job title.</p>
        <?php else: ?>
            <div class="slider-wrapper">
                <div class="slider" id="profileSlider">
                    <?php foreach ($profiles as $index => $profile): ?>
                        <article class="slide <?= $index === 0 ? 'active' : ''; ?>" data-index="<?= $index; ?>">
                            <div class="profile-card">
                                <div class="profile-photo">
                                    <img src="<?= htmlspecialchars($profile['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($profile['fullname'], ENT_QUOTES, 'UTF-8'); ?> profile photo">
                                </div>
                                <div class="profile-content">
                                    <span class="profile-role"><?= htmlspecialchars($profile['job_title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <h3><?= htmlspecialchars($profile['fullname'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><?= nl2br(htmlspecialchars(substr($profile['about_me'], 0, 140), ENT_QUOTES, 'UTF-8')); ?></p>
                                    <div class="profile-actions">
                                        <a href="profile.php?id=<?= $profile['id']; ?>" class="btn-secondary">View Full CV</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="slider-controls">
                    <button type="button" class="slider-button" id="prevBtn">Previous</button>
                    <button type="button" class="slider-button" id="nextBtn">Next</button>
                </div>
            </div>
        <?php endif; ?>
    </section>

</main>

<a href="#top" id="scrollTop" class="scroll-top" aria-label="Scroll to top">↑</a>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
