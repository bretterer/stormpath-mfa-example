<?php

use Stormpath\Stormpath;

require_once __DIR__ . '/bootstrap.php';

$type = request()->cookies->get('factor_type');
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

getHead();
getNav($user);
?>

<div class="container">
    <div class="well">
        <form class="form-signin" method="post" action="confirm_code.php">
            <h2 class="form-signin-heading">What is the code?</h2>

            <label for="inputCode" class="sr-only">Code</label>
            <input type="text" id="inputCode" name="code" class="form-control" placeholder="Code" required autofocus>

            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Finish Sign in</button>
        </form>
    </div>
</div>

<?php getFooter(); ?>
