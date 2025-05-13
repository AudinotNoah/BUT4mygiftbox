<?php

declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../src/conf/bootstrap.php';

$app->run();