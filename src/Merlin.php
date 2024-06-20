<?php

namespace Merlin;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Merlin
{
    private $directory;
    private $logger;

    public function __construct($directory = 'content')
    {
        $this->directory = $directory;

        $this->logger = new Logger('merlin_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

        $this->logger->info('Merlin instance created.', ['directory' => $this->directory]);
    }

    public function listDirectory($subdirectory = '')
    {
        $directoryPath = $this->directory . ($subdirectory ? DIRECTORY_SEPARATOR . $subdirectory : '');
        $this->logger->info('Listing directory.', ['directory' => $directoryPath]);
        return $this->scanDirectory($directoryPath, $subdirectory);
    }

    private function scanDirectory($directory, $subdirectory)
    {
        $items = scandir($directory);
        $output = [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;
            $relativePath = ($subdirectory ? $subdirectory . DIRECTORY_SEPARATOR : '') . $item;

            if (is_dir($path)) {
                $output[] = 'folder - <a href="?dir=' . urlencode($relativePath) . '">' . $item . '</a>';
            } else {
                $output[] = 'file - <a href="content/' . urlencode($relativePath) . '">' . $item . '</a>';
            }
        }

        $this->logger->debug('Directory scanned.', ['directory' => $directory, 'output' => $output]);
        return $output;
    }
}
