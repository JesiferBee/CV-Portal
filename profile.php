<?php
require_once 'pdo.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM profiles WHERE id = :id');
$stmt->execute(['id' => $id]);
$profile = $stmt->fetch();

if (!$profile) {
    header('Location: index.php');
    exit;
}

function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function renderList($text) {
    $items = array_filter(array_map('trim', explode("\n", $text)));
    foreach ($items as $item) {
        echo '<li>' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</li>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($profile['fullname']); ?> | Full CV</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-loader" id="pageLoader">
    <div class="loader-ring"></div>
</div>
<?php include 'header.php'; ?>
<main class="page-content profile-page">
    <section class="profile-hero">
        <div class="profile-hero-photo">
            <img src="<?= escape($profile['photo']); ?>" alt="<?= escape($profile['fullname']); ?> profile photo">
        </div>
        <div class="profile-hero-copy">
            <p class="eyebrow">Full CV</p>
            <h1><?= escape($profile['fullname']); ?></h1>
            <p class="subheading"><?= escape($profile['job_title']); ?></p>
            <div class="contact-grid">
                <div><strong>Email</strong><a href="mailto:<?= escape($profile['email']); ?>"><?= escape($profile['email']); ?></a></div>
                <div><strong>Phone</strong><a href="tel:<?= escape($profile['phone']); ?>"><?= escape($profile['phone']); ?></a></div>
                <div><strong>Address</strong><span><?= escape($profile['address']); ?></span></div>
            </div>
            <div class="social-badges">
                <?php if ($profile['facebook']): ?><a href="<?= escape($profile['facebook']); ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
                <?php if ($profile['linkedin']): ?><a href="<?= escape($profile['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
                <?php if ($profile['github']): ?><a href="<?= escape($profile['github']); ?>" target="_blank" rel="noopener">GitHub</a><?php endif; ?>
            </div>
            <div class="profile-actions">
                <a href="edit.php?id=<?= $profile['id']; ?>" class="btn-primary">Edit CV</a>
            </div>
        </div>
    </section>

    <section class="content-block about-block">
        <h2>About me</h2>
        <p><?= nl2br(escape($profile['about_me'])); ?></p>
    </section>

    <div class="resume-grid">
        <section class="content-block">
            <h2>Education</h2>
            <ul>
                <?php renderList($profile['education']); ?>
            </ul>
        </section>
        <section class="content-block">
            <h2>Experience</h2>
            <ul>
                <?php renderList($profile['experience']); ?>
            </ul>
        </section>
        <section class="content-block">
            <h2>Skills</h2>
            <ul class="pill-list">
                <?php foreach (array_filter(array_map('trim', explode(',', $profile['skills']))) as $skill): ?>
                    <li><?= escape($skill); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section class="content-block">
            <h2>Languages</h2>
            <ul class="pill-list">
                <?php foreach (array_filter(array_map('trim', explode(',', $profile['languages']))) as $language): ?>
                    <li><?= escape($language); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</main>

<a href="#top" id="scrollTop" class="scroll-top" aria-label="Scroll to top">↑</a>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
