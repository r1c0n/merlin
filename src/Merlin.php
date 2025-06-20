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
        if (!is_dir($directoryPath)) {
            $this->logger->error('Directory not found', ['directory' => $directoryPath]);
            return ['Directory not found'];
        }
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
                $output[] = [
                    'type' => 'folder',
                    'name' => $item,
                    'path' => $relativePath,
                    'link' => '?dir=' . urlencode($relativePath)
                ];
            } else {
                $fileInfo = [
                    'type' => 'file',
                    'name' => $item,
                    'path' => $relativePath,
                    'link' => 'content/' . urlencode($relativePath),
                    'size' => filesize($path),
                    'modified' => date("Y-m-d H:i:s", filemtime($path))
                ];
                $output[] = $fileInfo;
            }
        }

        $directory = realpath($directory);
        if (strpos($directory, realpath($this->directory)) !== 0) {
            $this->logger->warning('Attempted directory traversal detected', ['directory' => $directory]);
            return ['Invalid directory path'];
        }

        $this->logger->debug('Directory scanned.', ['directory' => $directory, 'output' => $output]);
        return $output;
    }
}
