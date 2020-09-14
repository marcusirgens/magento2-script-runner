<?php

declare(strict_types=1);

/**
 * Detect the Magento install directory
 *
 * @param string $dir
 * @return string
 * @throws Exception
 */
function detect_magento(string $dir): string
{
    if (!is_dir($dir)) {
        throw new InvalidArgumentException("\$dir was not a valid directory");
    }

    if (
        file_exists(implode(DIRECTORY_SEPARATOR, [$dir, "app", "autoload.php"]))
        && file_exists(implode(DIRECTORY_SEPARATOR, [$dir, "bin", "magento"]))
    ) {
        return $dir;
    }

    $parent = dirname($dir);
    if ($parent == $dir) {
        throw new \Exception("Magento not found");
    }

    return detect_magento($parent);
}
