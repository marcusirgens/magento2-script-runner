<?php

declare(strict_types=1);

require __DIR__ . '/lib/path.php';
require __DIR__ . '/lib/detect_magento.php';

try {
    $m2 = detect_magento(__DIR__);
} catch (\Exception $e) {
    fwrite(STDERR, "Could not find Magento 2\n");
    exit (1);
}

require $m2 . '/app/bootstrap.php';
require __DIR__ . '/lib/ScriptApplication.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
/** @var \ScriptApplication $app */
$app = $bootstrap->createApplication(\ScriptApplication::class);
$bootstrap->run($app);
