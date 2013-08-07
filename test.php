<?php

require_once('src/Session/Session.php');

$session = new Session\Session(true, 180, true, true);

?><h4>Session</h4><?php
	echo '<pre>'.print_r($_SESSION,true).'</pre>';

$session->SetOnce('test','valid');

echo $session->Get('test');

$session->SetOnce('test', 'invalid');

echo $session->Get('test');

echo $session->Get('this does not exist');

echo $session->get('time');  // this doesn't work.
