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

require __DIR__ . '/bootstrap.php';

getHead();
getNav($user);

?>

<div class="container">
    <div class="well col-sm-6 col-sm-offset-3">
        <form class="form-signin" method="post" action="doRegister.php">
            <h2 class="form-signin-heading">Register</h2>

            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
            <br>
            <label for="inputFirstName" class="sr-only">First Name</label>
            <input type="text" id="inputFirstName" name="firstName" class="form-control" placeholder="First Name" required>
            <br>
            <label for="inputLastName" class="sr-only">Last Name</label>
            <input type="text" id="inputLastName" name="lastName" class="form-control" placeholder="LastName" required>
            <br>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        </form>
    </div>
</div>


<?php getFooter(); ?>


