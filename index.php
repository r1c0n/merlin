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
    <title>Directory Listing</title>
    <link rel="apple-touch-icon" sizes="180x180" href="./images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon/favicon-16x16.png">
    <link rel="manifest" href="./images/favicon/site.webmanifest">
    <link rel="mask-icon" href="./images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="./images/favicon/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <img src="./images/merlin.png" alt="Merlin" width="240" height="86">
            <h1>Index of <?php echo htmlspecialchars($displayPath); ?></h1>
            <nav class="breadcrumbs">
                <a href="?">Home</a>
                <?php if ($subdirectory): ?>
                    <?php $parts = explode('/', $subdirectory); $currentPath = ''; ?>
                    <?php foreach ($parts as $part): ?>
                        <?php $currentPath .= ($currentPath ? '/' : '') . $part; ?>
                        / <a href="?dir=<?= urlencode($currentPath) ?>"><?= htmlspecialchars($part) ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </nav>
        </header>

        <div class="file-browser">
            <table class="file-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($subdirectory): ?>
                        <tr>
                            <td colspan="4">
                                <a href="?<?php 
                                    $parentDir = dirname($subdirectory);
                                    if ($parentDir !== '.') {
                                        echo 'dir=' . urlencode($parentDir);
                                    }
                                ?>">
                                    📁 Parent Directory
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                    <?php foreach ($directoryStructure as $item): ?>
                        <tr>
                            <td>
                                <a href="<?= $item['link'] ?>">
                                    <?php if ($item['type'] === 'folder'): ?>
                                        📁
                                    <?php else: ?>
                                        📄
                                    <?php endif; ?>
                                    <?= htmlspecialchars($item['name']) ?>
                                </a>
                            </td>
                            <td><?= ucfirst($item['type']) ?></td>
                            <td><?= $item['type'] === 'file' ? formatSize($item['size']) : '-' ?></td>
                            <td><?= $item['modified'] ?? '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <footer>
            <hr />
            <p>Copyright &copy; 2024 - <?php echo date('Y'); ?> Merlin Index, All Rights and Lefts Reserved.</p>
            <div class="powered-by">Merlin File Browser</div>
        </footer>
    </div>
</body>
</html>

<?php
function formatSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}
?>