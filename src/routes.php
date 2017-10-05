<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Appel de la route racine ");


    // Render index view
    return $response->withJson("Merci de voir la documentation de l'api méteo",200);
});


$app->get('/vols[/{city}]', Src\Controllers\IndexController::class.":getVolByVille");

$app->post('/vols', Src\Controllers\IndexController::class.":addVol");
$app->get('/new/vol/[{lastVol}]', Src\Controllers\IndexController::class.":newVol");
