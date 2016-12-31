<?php

use Stormpath\Stormpath;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/bootstrap.php';

$code = request()->get('code');

$type = request()->cookies->get('factor_type');
switch(strtolower($type)) {
    case 'sms' :
        $type = Stormpath::SMS_FACTOR;
        break;
    case 'google-authenticator' :
        $type = Stormpath::GOOGLE_AUTHENTICATOR_FACTOR;
        break;
    default :
        throw new \Exception('could not resolve factor type');
}

$factor = $client->getDataStore()->getResource(request()->cookies->get('factor'), $type);

if(strtolower($factor->type) == 'sms') {
    $challenge = $factor->mostRecentChallenge->validate($code);
}

if(strtolower($factor->type) == 'google-authenticator') {
    $challenge = $factor->validate($code);
}

if(!$challenge || $challenge->status != Stormpath::SUCCESS) {
    die('Invalid Code');
}


$tokenId = request()->cookies->get('access_token_id');
$accessToken = $client->getDataStore()->getResource('/accessTokens/'.$tokenId, Stormpath::ACCESS_TOKEN);
$converter = new \Bretterer\IsoDurationConverter\DurationParser();
$accessTokenTtl = $converter->parse($application->getOauthPolicy()->getAccessTokenTtl());

$response = Response::create('', Response::HTTP_FOUND, ['Location' => '/account.php']);

$factorCookie = new Cookie("factor", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
$factorType = new Cookie("factor_type", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
$tokenId = new Cookie("access_token_id", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
$accessToken = new Cookie("access_token", $accessToken->getJwt(), time() + $accessTokenTtl, '/', getenv('COOKIE_DOMAIN'));
$response->headers->setCookie($factorCookie);
$response->headers->setCookie($factorType);
$response->headers->setCookie($tokenId);
$response->headers->setCookie($accessToken);
$response->send();
