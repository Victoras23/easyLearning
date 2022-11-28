<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->setBasePath("/employe-api/public");

$app->addErrorMiddleware(true, true, true);

$app->get('/',function(Request $request , Response $response){
    $response->getBody()->write("it works");
    return $response;
});

require __DIR__ . '/../src/routes/employers.php';

$app->run();