<?php

use Stormpath\Mfa\SmsFactor;
use Stormpath\Stormpath;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/bootstrap.php';

$phone = request()->get('phone');

$factor = new SmsFactor();
$factor->phone = $phone;
$factor->challenge = 'Confirm your sms factor: ${code}';

$user->addFactor($factor);



$response = Response::create('', Response::HTTP_FOUND, ['Location' => '/confirm_new_factor.php']);

$factorCookie = new Cookie("factor", $factor->href, time() + 120, '/', getenv('COOKIE_DOMAIN'));
$factorType = new Cookie("factor_type", $factor->type, time() + 120, '/', getenv('COOKIE_DOMAIN'));
$response->headers->setCookie($factorCookie);
$response->headers->setCookie($factorType);

$response->send();