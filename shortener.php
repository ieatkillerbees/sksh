<?php declare(strict_types=1);

require "vendor/autoload.php";

$container = new \SkillshareShortener\Container();

$router = $container->get(\League\Route\Router::class);

/** @var \Psr\Http\Message\ServerRequestInterface $request */
$request = $container->get('request');

try {
    /** @var \Psr\Http\Message\ResponseInterface $response */
    $response = $router->dispatch($request);
} catch (\League\Route\Http\Exception $httpException) {
    $response = new \Zend\Diactoros\Response\TextResponse($httpException->getMessage(), $httpException->getStatusCode());
} catch (\Exception $exception) {
    $response = new \Zend\Diactoros\Response\TextResponse("Internal Server Error", 500);
} finally {
    (new Zend\Diactoros\Response\SapiEmitter)->emit($response);
}