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

$errors = [];
$values = [
    'fullname' => $profile['fullname'],
    'job_title' => $profile['job_title'],
    'email' => $profile['email'],
    'phone' => $profile['phone'],
    'address' => $profile['address'],
    'about_me' => $profile['about_me'],
    'education' => $profile['education'],
    'experience' => $profile['experience'],
    'skills' => $profile['skills'],
    'languages' => $profile['languages'],
    'github' => $profile['github'],
];
$photoPath = $profile['photo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $value) {
        $values[$key] = trim((string)($_POST[$key] ?? ''));
    }

    $uploadDir = __DIR__ . '/assets/images/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!empty($_FILES['photo_file']['name']) && is_uploaded_file($_FILES['photo_file']['tmp_name'])) {
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        $fileType = mime_content_type($_FILES['photo_file']['tmp_name']);
        if (!isset($allowedTypes[$fileType])) {
            $errors[] = 'Photo must be a JPG, PNG, GIF, or WEBP image.';
        } else {
            $extension = $allowedTypes[$fileType];
            $filename = sprintf('profile-%s.%s', bin2hex(random_bytes(8)), $extension);
            $destination = $uploadDir . '/' . $filename;

            if (!move_uploaded_file($_FILES['photo_file']['tmp_name'], $destination)) {
                $errors[] = 'Unable to save uploaded photo. Please try again.';
            } else {
                $photoPath = 'assets/images/uploads/' . $filename;
            }
        }
    }

    if ($values['fullname'] === '') {
        $errors[] = 'Full name is required.';
    }
    if ($values['job_title'] === '') {
        $errors[] = 'Job title is required.';
    }
    if ($values['email'] === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email must be a valid address.';
    }
    if ($values['phone'] === '') {
        $errors[] = 'Phone number is required.';
    }
    if ($values['address'] === '') {
        $errors[] = 'Address is required.';
    }
    if ($values['about_me'] === '') {
        $errors[] = 'About me is required.';
    }
    if ($values['education'] === '') {
        $errors[] = 'Education is required.';
    }
    if ($values['experience'] === '') {
        $errors[] = 'Experience is required.';
    }
    if ($values['skills'] === '') {
        $errors[] = 'Skills are required.';
    }
    if ($values['languages'] === '') {
        $errors[] = 'Languages are required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'UPDATE profiles SET fullname = :fullname, job_title = :job_title, photo = :photo, email = :email, phone = :phone, address = :address, about_me = :about_me, education = :education, experience = :experience, skills = :skills, languages = :languages, github = :github WHERE id = :id'
        );

        $stmt->execute([
            'fullname' => $values['fullname'],
            'job_title' => $values['job_title'],
            'photo' => $photoPath,
            'email' => $values['email'],
            'phone' => $values['phone'],
            'address' => $values['address'],
            'about_me' => $values['about_me'],
            'education' => $values['education'],
            'experience' => $values['experience'],
            'skills' => $values['skills'],
            'languages' => $values['languages'],
            'github' => $values['github'] ?: null,
            'id' => $id,
        ]);

        header('Location: profile.php?id=' . $id);
        exit;
    }
}

function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit CV Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-loader" id="pageLoader">
    <div class="loader-ring"></div>
</div>
<?php include 'header.php'; ?>
<main class="page-content">
    <section class="form-panel">
        <h1>Edit CV Profile</h1>
        <?php if ($errors): ?>
            <div class="form-errors">
                <strong>Please fix the errors below:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= escape($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="edit.php?id=<?= $id; ?>" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-field">
                    <label for="fullname">Full name</label>
                    <input id="fullname" name="fullname" type="text" value="<?= escape($values['fullname']); ?>" required>
                </div>
                <div class="form-field">
                    <label for="job_title">Job title</label>
                    <input id="job_title" name="job_title" type="text" value="<?= escape($values['job_title']); ?>" required>
                </div>
                <div class="form-field">
                    <label for="photo_file">Upload new profile photo</label>
                    <input id="photo_file" name="photo_file" type="file" accept="image/*">
                    <small>Leave blank to keep the existing photo.</small>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?= escape($values['email']); ?>" required>
                </div>
                <div class="form-field">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="text" value="<?= escape($values['phone']); ?>" required>
                </div>
                <div class="form-field">
                    <label for="address">Address</label>
                    <input id="address" name="address" type="text" value="<?= escape($values['address']); ?>" required>
                </div>
                <div class="form-field field-full">
                    <label for="about_me">About me</label>
                    <textarea id="about_me" name="about_me" required><?= escape($values['about_me']); ?></textarea>
                </div>
                <div class="form-field field-full">
                    <label for="education">Education</label>
                    <textarea id="education" name="education" placeholder="Enter each item on a new line" required><?= escape($values['education']); ?></textarea>
                </div>
                <div class="form-field field-full">
                    <label for="experience">Experience</label>
                    <textarea id="experience" name="experience" placeholder="Enter each item on a new line" required><?= escape($values['experience']); ?></textarea>
                </div>
                <div class="form-field">
                    <label for="skills">Skills</label>
                    <input id="skills" name="skills" type="text" placeholder="Comma-separated" value="<?= escape($values['skills']); ?>" required>
                </div>
                <div class="form-field">
                    <label for="languages">Languages</label>
                    <input id="languages" name="languages" type="text" placeholder="Comma-separated" value="<?= escape($values['languages']); ?>" required>
                </div>
                <div class="form-field">
                    <label for="github">GitHub URL</label>
                    <input id="github" name="github" type="url" value="<?= escape($values['github']); ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="profile.php?id=<?= $id; ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </section>
</main>
<a href="#top" id="scrollTop" class="scroll-top" aria-label="Scroll to top">↑</a>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
