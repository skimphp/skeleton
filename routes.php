<?php declare(strict_types=1);

/** @var Skim\Core\App $app */

// Register your HTTP routes here.
$app->router->get('/', [App\Controllers\HomeController::class, 'index']);
