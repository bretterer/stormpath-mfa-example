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

/**
 * @param mixed|string $content
 * @param int $status
 * @param array $headers
 * @return \Symfony\Component\HttpFoundation\Response
 */
function response($content = '', $status = 200, $headers = []) {
    return new \Symfony\Component\HttpFoundation\Response($content, $status, $headers);
}

/**
 * @return \Symfony\Component\HttpFoundation\Request
 */
function request()
{
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

/**
 * @param $message
 */
function error($message)
{
    print "<pre>ERROR: {$message}</pre>";
}

/**
 *
 */
function getFooter()
{
    require __DIR__ . '/_partials/footer.php';
}

/**
 *
 */
function getHead()
{
    require __DIR__ . '/_partials/head.php';
}

/**
 * @param $user
 */
function getNav($user)
{
    require __DIR__ . '/_partials/nav.php';
}