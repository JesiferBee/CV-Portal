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
        <h2>Database structure</h2>
        <ul>
            <li><strong>id</strong> — unique profile identifier</li>
            <li><strong>fullname</strong> — candidate name</li>
            <li><strong>job_title</strong> — position or role</li>
            <li><strong>photo</strong> — profile photo path</li>
            <li><strong>email</strong>, <strong>phone</strong>, <strong>address</strong> — contact details</li>
            <li><strong>about_me</strong>, <strong>education</strong>, <strong>experience</strong>, <strong>skills</strong>, <strong>languages</strong> — resume content</li>
            <li><strong>github</strong> — social links</li>
            <li><strong>created_at</strong> — record timestamp</li>
        </ul>
    </section>

    <section class="content-block">
        <h2>Security and best practices</h2>
        <p>The database connection is stored in <code>pdo.php</code> and uses a PDO instance with prepared statements to prevent SQL injection. Every value that is sent to the browser is escaped with <code>htmlspecialchars()</code> to keep output safe.</p>
    </section>
</main>

<a href="#top" id="scrollTop" class="scroll-top" aria-label="Scroll to top">↑</a>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
