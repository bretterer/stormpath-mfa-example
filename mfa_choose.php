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

require __DIR__ . '/bootstrap.php';

getHead();
getNav($user);

$accessToken = $client->getDataStore()->getResource('https://api.stormpath.com/v1/accessTokens/'.request()->cookies->get('access_token_id'), Stormpath::ACCESS_TOKEN);

$factors = $accessToken->account->factors;


?>

    <div class="container">
        <div class="well col-sm-6 col-sm-offset-3">
            <h2 class="form-signin-heading">Which factor do you want to use?</h2>
            <?php
            foreach($factors as $factor) {
                ?>
                <a href="/set_factor.php?factor=<?php print $factor->getHref(); ?>&type=<?php print $factor->getType(); ?>">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><?php echo $factor->type; ?> </h3>
                            <?php if (strtolower($factor->type) == 'sms' ) : ?>
                                <p>*********<?php echo substr($factor->phone->number, -2); ?></p>
                            <?php endif; ?>

                            <?php if (strtolower($factor->type) == 'google-authenticator' ) : ?>
                                <p><?php echo $factor->issuer; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>



<?php getFooter(); ?>