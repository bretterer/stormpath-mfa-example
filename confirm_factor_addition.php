<?php
/**
 * Copyright 2016 Stormpath, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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



$response = Response::create('', Response::HTTP_FOUND, ['Location' => '/account.php']);
$factorCookie = new Cookie("factor", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
$factorType = new Cookie("factor_type", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
$response->headers->setCookie($factorCookie);
$response->headers->setCookie($factorType);
$response->send();
