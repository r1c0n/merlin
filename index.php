<?php

require 'vendor/autoload.php';

use Merlin\Merlin;

$subdirectory = isset($_GET['dir']) ? $_GET['dir'] : '';
$merlin = new Merlin();
$directoryStructure = $merlin->listDirectory($subdirectory);

$displayPath = $subdirectory ? '/' . $subdirectory . '/' : '/';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="msapplication-config" content="./images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <title>Merlin</title>
    <link rel="apple-touch-icon" sizes="180x180" href="./images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon/favicon-16x16.png">
    <link rel="manifest" href="./images/favicon/site.webmanifest">
    <link rel="mask-icon" href="./images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="./images/favicon/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <img src="./images/merlin.png" alt="Merlin" width="240" height="86">
    <h1>Directory Listing</h1>
    <p>Path: <?php echo htmlspecialchars($displayPath); ?></p>
    <pre><?php foreach ($directoryStructure as $item): ?>
            <li><?php echo $item; ?></li>
        <?php endforeach; ?>
    </pre>
    <?php if ($subdirectory): ?>
        <a class="back-link" href="?">Back to top-level</a>
    <?php endif; ?>
    <hr />
    <p>Copyright &copy; 2024 Merlin Index, All Rights and Lefts Reserved.</p>
</body>
</html>
