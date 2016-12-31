<?php

use Stormpath\Stormpath;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/bootstrap.php';
$token = request()->cookies->get('access_token');

if($token) {
    JWT::$leeway = 1;
    $jwt = JWT::decode($token, getenv('STORMPATH_CLIENT_APIKEY_SECRET'), ['HS256']);

    $accessToken = $client->getDataStore()->getResource('/accessTokens/' . $jwt->jti, Stormpath::ACCESS_TOKEN);
    $accessToken->delete();
}


$response = Response::create('', Response::HTTP_FOUND, ['Location' => '/']);
$accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
$response->headers->setCookie($accessToken);

$response->send();