<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>My PHP App

        <?php
        if (isset($title))
        {
            echo ' - '.$title;
        }
        ?>

    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="/Assets/favicon.png">
</head>

<body class="d-flex flex-column min-vh-100 bg-body-tertiary">

<?php
if (!isset($noheader)) {
    require dirname(__DIR__, 1) . '/Layouts/Header.php';
}
?>

<div class="d-flex flex-grow-1">
    <main class="flex-grow-1">
        <?=$this->content;?>
    </main>
</div>

<?php
if (!isset($nofooter)) {
    require dirname(__DIR__, 1) . '/Layouts/Footer.php';
}
?>

</body>
</html>