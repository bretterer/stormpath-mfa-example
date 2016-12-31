<?php

use Stormpath\Stormpath;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/bootstrap.php';

$type = request()->get('type');
switch(strtolower($type)) {
    case 'sms' :
        $type = Stormpath::SMS_FACTOR;
        $options = ['expand' => 'phone'];
        break;
    case 'google-authenticator' :
        $type = Stormpath::GOOGLE_AUTHENTICATOR_FACTOR;
        $options = [];
        break;
    default :
        throw new \Exception('could not resolve factor type');
}


$response = Response::create('', Response::HTTP_FOUND, ['Location' => '/resolve_factor.php']);

$factor = $client->getDataStore()->getResource(request()->get('factor'), $type, $options);

$factorCookie = new Cookie("factor", $factor->href, time() + 120, '/', getenv('COOKIE_DOMAIN'));
$factorType = new Cookie("factor_type", $factor->type, time() + 120, '/', getenv('COOKIE_DOMAIN'));
$response->headers->setCookie($factorCookie);
$response->headers->setCookie($factorType);
$response->send();
