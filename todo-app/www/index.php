<?php

declare(strict_types=1);

// Nastavení CORS headerů
header('Access-Control-Allow-Headers: accept, content-type, authorization');
header('Access-Control-Allow-Methods: GET,POST,OPTIONS,DELETE,PUT');
header('Access-Control-Allow-Origin: *');

// Kladná odpověď pro CORS Preflight check - request typu OPTIONS
if ("OPTIONS" === $_SERVER['REQUEST_METHOD']) {
	return;
}

require __DIR__ . '/../vendor/autoload.php';

App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
