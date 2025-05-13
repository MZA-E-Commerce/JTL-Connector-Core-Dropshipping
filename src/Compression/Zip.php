<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Compression;

use Jtl\Connector\Core\Exception\CompressionException;
use Jtl\Connector\Core\Exception\FileNotFoundException;

class Zip
{
    /**
     * @param string $sourceFile
     * @param string $targetFolder
     *
     * @return bool
     * @throws CompressionException
     * @throws FileNotFoundException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function extract(string $sourceFile, string $targetFolder): bool
    {
        if (!\class_exists('ZipArchive')) {
            throw new CompressionException('Class ZipArchive not found. PHP 5 >= 5.2.0, PECL zip >= 1.1.0 installed?');
        }

        if (!\file_exists($sourceFile)) {
            throw new FileNotFoundException(\sprintf('File (%s) not found', $sourceFile));
        }

        if (!\is_dir($targetFolder) || !\is_writable($targetFolder)) {
            throw new \InvalidArgumentException(
                \sprintf('Folder (%s) does not exists or is not writeable', $targetFolder)
            );
        }

        $archive = new \ZipArchive();
        if ($archive->open($sourceFile) === true) {
            $index         = 0;
            $isBomb        = false;
            $imageTooLarge = false;

            while (($stat = $archive->statIndex($index))) {
                $size     = $stat['size'];
                $compSize = $stat['comp_size'];

                // 20MB
                if ($size > 20000000) {
                    $imageTooLarge = true;
                    break;
                }

                // Images won't reach a compression ratio this high
                if ($size / $compSize > 100) {
                    $isBomb = true;
                    break;
                }

                if ($index > 100) {
                    $isBomb = true;
                    break;
                }

                $index++;
            }

            if ($imageTooLarge) {
                throw new \RuntimeException("Image {$index} too large");
            }

            if ($isBomb) {
                throw new \RuntimeException('Zip bomb detected');
            }

            $archive->extractTo($targetFolder);
            $archive->close();

            return true;
        }

        return false;
    }
}
