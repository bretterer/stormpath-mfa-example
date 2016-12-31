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


require_once __DIR__ . '/vendor/autoload.php';

// Load our Env file
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Create a Stormpath Client
/** @var \Stormpath\ClientBuilder $clientBuilder */
$clientBuilder = new \Stormpath\ClientBuilder();
$clientBuilder->setApiKeyProperties("apiKey.id=".getenv('STORMPATH_CLIENT_APIKEY_ID')."\napiKey.secret=".getenv('STORMPATH_CLIENT_APIKEY_SECRET'));

/** @var \Stormpath\Client $client */
$client = $clientBuilder->build();

// Get the Stormpath Application
/** @var \Stormpath\Resource\Application $application */
$application = $client->getDataStore()->getResource(getenv('STORMPATH_APPLICATION_HREF'), \Stormpath\Stormpath::APPLICATION);

// Get the User if found
/** @var \Stormpath\Resource\Account $user */
$user = null;
if(request()->cookies->has('access_token')) {
    try {
        $decoded = JWT::decode(request()->cookies->get('access_token'), getenv('STORMPATH_CLIENT_APIKEY_SECRET'), ['HS256']);
        $user = $client->getDataStore()->getResource($decoded->sub, \Stormpath\Stormpath::ACCOUNT);
    } catch (\Stormpath\Resource\ResourceError $re) {
        error($re->getDeveloperMessage());
    }
}