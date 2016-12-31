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

require_once __DIR__ . '/bootstrap.php';

$type = request()->cookies->get('factor_type');
$image = null;
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

$factor = $client->getDataStore()->getResource(request()->cookies->get('factor'), $type, $options);

if(strtolower($factor->type) == 'sms') {
    $factor->createChallenge('YO! Confirm yo account fool! ' . Stormpath::MFA_CHALLENGE_CODE_PLACEHOLDER);
}
if(strtolower($factor->type) == 'google-authenticator') {
    $image = $factor->getBase64QRImage();
}

getHead();
getNav($user);
?>

<div class="container">
    <div class="well">
        <?php if($image) : ?>
            <p>Scan this code in the google authenticator app.</p>
            <image src="data:image/png;base64,<?php echo $image; ?>" />
        <?php endif; ?>
        <form class="form-signin" method="post" action="confirm_factor_addition.php">
            <h2 class="form-signin-heading">What is the code?</h2>

            <label for="inputCode" class="sr-only">Code</label>
            <input type="text" id="inputCode" name="code" class="form-control" placeholder="Code" required autofocus>

            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Finish Sign in</button>
        </form>
    </div>
</div>

<?php getFooter(); ?>
