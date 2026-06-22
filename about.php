<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | CV Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-loader" id="pageLoader">
    <div class="loader-ring"></div>
</div>
<?php include 'header.php'; ?>
<main class="page-content about-page">
    <section class="about-hero">
        <div class="section-heading">
            <span class="eyebrow">About this project</span>
            <h1>Personal CV Showcase</h1>
            <p>This basic website displays multiple CV profiles from a MySQL database using PHP and PDO.</p>
        </div>
    </section>

    <section class="content-block">
        <h2>How it works</h2>
        <p>The project uses a single database named <strong>cv_portal</strong> and a table called <strong>profiles</strong>. Every candidate record includes the user’s name, job title, contact details, biography, education, experience, skills, languages, and social links.</p>
        <p>The <code>index.php</code> page reads the profile list and renders the slider cards. When a visitor clicks a profile, the application loads <code>profile.php</code> and displays the candidate’s complete CV details.</p>
    </section>

    <section class="content-block">
        <h2>Security and best practices</h2>
        <p>The database connection is handled in <code>pdo.php</code> using PDO prepared statements, which helps prevent SQL injection. Data shown on the page is escaped with <code>htmlspecialchars()</code> before it reaches the browser.</p>
    </section>

    <section class="content-block donate-block">
        <h2>Donate us</h2>
        <p>Support the project by scanning the QR code below.</p>
        <img src="assets/images/donate-qr.svg" alt="Donate QR code" class="donate-qr-inline">
    </section>
</main>

<a href="#top" id="scrollTop" class="scroll-top" aria-label="Scroll to top">↑</a>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
