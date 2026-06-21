<?php
require_once 'pdo.php';

$errors = [];
$values = [
    'fullname' => '',
    'job_title' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'about_me' => '',
    'education' => '',
    'experience' => '',
    'skills' => '',
    'languages' => '',
    'github' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function resizeAndCropImage(string $sourcePath, string $destinationPath, int $targetWidth, int $targetHeight): bool {
        $info = getimagesize($sourcePath);
        if ($info === false) {
            return false;
        }

        [$srcWidth, $srcHeight, $imageType] = $info;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $srcImage = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $srcImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        if (!$srcImage) {
            return false;
        }

        $srcRatio = $srcWidth / $srcHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($srcRatio > $targetRatio) {
            $intermediateHeight = $targetHeight;
            $intermediateWidth = (int) round($targetHeight * $srcRatio);
        } else {
            $intermediateWidth = $targetWidth;
            $intermediateHeight = (int) round($targetWidth / $srcRatio);
        }

        $intermediateImage = imagecreatetruecolor($intermediateWidth, $intermediateHeight);
        imagecopyresampled(
            $intermediateImage,
            $srcImage,
            0,
            0,
            0,
            0,
            $intermediateWidth,
            $intermediateHeight,
            $srcWidth,
            $srcHeight
        );

        $finalImage = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopy(
            $finalImage,
            $intermediateImage,
            0,
            0,
            (int) floor(($intermediateWidth - $targetWidth) / 2),
            (int) floor(($intermediateHeight - $targetHeight) / 2),
            $targetWidth,
            $targetHeight
        );

        $extension = strtolower(pathinfo($destinationPath, PATHINFO_EXTENSION));
        $result = false;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $result = imagejpeg($finalImage, $destinationPath, 90);
                break;
            case 'png':
                imagealphablending($finalImage, false);
                imagesavealpha($finalImage, true);
                $result = imagepng($finalImage, $destinationPath, 9);
                break;
            case 'gif':
                $result = imagegif($finalImage, $destinationPath);
                break;
            case 'webp':
                $result = imagewebp($finalImage, $destinationPath, 90);
                break;
        }

        imagedestroy($srcImage);
        imagedestroy($intermediateImage);
        imagedestroy($finalImage);

        return (bool) $result;
    }

    foreach ($values as $key => $value) {
        $values[$key] = trim((string)($_POST[$key] ?? ''));
    }

    $uploadDir = __DIR__ . '/assets/images/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $photoPath = 'assets/images/default-profile.svg';
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

            if (!resizeAndCropImage($_FILES['photo_file']['tmp_name'], $destination, 512, 512)) {
                $errors[] = 'Unable to resize the uploaded photo. Please try another image.';
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
            'INSERT INTO profiles (fullname, job_title, photo, email, phone, address, about_me, education, experience, skills, languages, github)
             VALUES (:fullname, :job_title, :photo, :email, :phone, :address, :about_me, :education, :experience, :skills, :languages, :github)'
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
        ]);

        $newId = $pdo->lastInsertId();
        header('Location: profile.php?id=' . $newId);
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
    <title>Add New CV Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-loader" id="pageLoader">
    <div class="loader-ring"></div>
</div>
<?php include 'header.php'; ?>
<main class="page-content">
    <section class="form-panel">
        <h1>Add New CV Profile</h1>
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

        <form method="post" action="add.php" enctype="multipart/form-data">
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
                    <label for="photo_file">Profile photo</label>
                    <input id="photo_file" name="photo_file" type="file" accept="image/*">
                    <small>Choose a JPG, PNG, GIF, or WEBP file. If left empty, the default image will be used.</small>
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
                <button type="submit" class="btn-primary">Save Profile</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </section>
</main>
<a href="#top" id="scrollTop" class="scroll-top" aria-label="Scroll to top">↑</a>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
