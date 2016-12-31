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

use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/bootstrap.php';

getHead();
getNav($user);

$factors = $user->factors

?>

    <div class="container">
        <div class="well">
            <h2>Factors</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Factor</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($factors as $factor): ?>
                    <tr>
                        <td><?php print $factor->href; ?></td>
                        <td><?php print $factor->type; ?></td>
                        <td>
                            <a href="delete_factor.php?factor=<?php print $factor->href; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Add SMS Factor</h2>
            <form class="form-signin" method="post" action="add_sms_factor.php">

                <label for="inputPhone" class="sr-only">Email address</label>
                <input type="phone" id="inputPhone" name="phone" class="form-control" placeholder="Phone Number" required autofocus>
                <br>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Add SMS Factor</button>
            </form>

            <h2>Add Google Authenticator Factor</h2>
            <form class="form-signin" method="post" action="add_google_factor.php">

                <button class="btn btn-lg btn-primary btn-block" type="submit">Add Google Factor</button>
            </form>
        </div>
    </div>



<?php getFooter(); ?>