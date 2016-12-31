<?php

use Stormpath\Resource\Account;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/bootstrap.php';

try {

    $user = Account::instantiate([
        'givenName' => request()->get('firstName'),
        'surname' => request()->get('lastName'),
        'email' => request()->get('email'),
        'password' => request()->get('password')
    ]);

    $application->createAccount($user);

    $response = Response::create('', Response::HTTP_FOUND, ['Location' => '/login.php']);
    $response->send();

} catch (\Exception $e) {
    dump($e);
    die();
}