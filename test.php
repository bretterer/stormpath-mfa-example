<?php

require_once __DIR__ . '/bootstrap.php';

$factorHref = 'https://api.stormpath.com/v1/factors/5anjSDJToityxpgq4LCeaP';

$factor = \Stormpath\Mfa\GoogleAuthenticatorFactor::instantiate();

dump($factor);