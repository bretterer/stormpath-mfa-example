<?php

use Stormpath\Stormpath;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/bootstrap.php';

try {

    $authRequest = new \Stormpath\Oauth\PasswordGrantRequest(request()->get('email'), request()->get('password'));
    $authResult = (new \Stormpath\Oauth\PasswordGrantAuthenticator($application))->authenticate($authRequest);


    $token = $client->getDataStore()->getResource($authResult->getAccessTokenHref(), Stormpath::ACCESS_TOKEN, ['expand' => 'account']);
    $factors = $token->account->factors->getSize();

    $converter = new \Bretterer\IsoDurationConverter\DurationParser();
    $accessTokenTtl = $converter->parse($application->getOauthPolicy()->getAccessTokenTtl());
    $explodedTokenHref = explode('/', $token->href);
    $id = end($explodedTokenHref);

    switch(true) {
        case ($factors == 1) :
            $factor = $token->account->factors->getIterator()->current();
            $accessTokenId = new Cookie("access_token_id", $id, time() + 12000, '/', getenv('COOKIE_DOMAIN'));
            $factorCookie = new Cookie("factor", $factor->href, time() + 120, '/', getenv('COOKIE_DOMAIN'));
            $factorType = new Cookie("factor_type", $factor->type, time() + 120, '/', getenv('COOKIE_DOMAIN'));
            $response = Response::create('', Response::HTTP_FOUND, ['Location' => '/resolve_factor.php']);
            $response->headers->setCookie($accessTokenId);
            $response->headers->setCookie($factorCookie);
            $response->headers->setCookie($factorType);
            break;
        case ($factors > 1) :
            $accessTokenId = new Cookie("access_token_id", $id, time() + 12000, '/', getenv('COOKIE_DOMAIN'));
            $response = Response::create('', Response::HTTP_FOUND, ['Location' => '/mfa_choose.php']);
            $response->headers->setCookie($accessTokenId);
            break;
        default :
            $factor = $token->account->factors->getIterator()->current();
            $response = Response::create('', Response::HTTP_FOUND, ['Location' => '/account.php']);
            $accessToken = new Cookie("access_token", $token->getJwt(), time() + $accessTokenTtl, '/', getenv('COOKIE_DOMAIN'));
            $factorCookie = new Cookie("factor", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
            $factorType = new Cookie("factor_type", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
            $tokenId = new Cookie("access_token_id", 'EXPIRED', time() - 9999, '/', getenv('COOKIE_DOMAIN'));
            $response->headers->setCookie($accessToken);
            $response->headers->setCookie($factorCookie);
            $response->headers->setCookie($factorType);
            $response->headers->setCookie($tokenId);
            break;

    }

    $response->send();


} catch (\Stormpath\Resource\ResourceError $re) {
    dump($re);
    die();
}

