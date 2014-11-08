<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = array();

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch($action) {
	case 'contact':
		if(!isset($_POST['name'])) {
			$errors[] = 'Name required';
		}

		if(!isset($_POST['email'])) {
			$errors[] = 'Email required';
		}

		if(!isset($_POST['message'])) {
 			$errors[] = 'Message required';
		}

		if(count($errors)) {
			errorExit($errors);
		}

		$name = stripslashes($_POST['name']);
		$email = stripslashes($_POST['email']);
		$msg = stripslashes($_POST['message']);
		
		if(strlen($name) < 1) {
			$errors[] = 'Please enter a First Name';
		}

		if(strlen($email) < 6) {
			$errors[] = 'Please enter a valid Email';
		}

		if(!validateEmail($email)) {
			$errors[] = 'Please enter a valid Email';
		}

		if(strlen($msg) < 1) {
			$errors[] = 'Please enter a Message';
		}

		if(count($errors)) {
			errorExit($errors);
		}
		
		sendMail($name, $email, $msg);
 		successExit('Contact email sent. Thank you!');
	break;

	case 'robly';
		$fname = mysql_real_escape_string($_POST['FNAME']);
		$lname = mysql_real_escape_string($_POST['LNAME']);
		$email = mysql_real_escape_string($_POST['email']);
	break;
	
	default:
	break;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}


function sendMail($name, $email, $msg) {
	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=iso-8859-1";
 	$headers[] = "From: HelixOne Trading <info@helixonetrading.in>";
	$headers[] = "Reply-To: {$name} <{$email}>";
	$headers[] = "HelixOne Trading India Contact Form - {$name}";
	$headers[] = "X-Mailer: PHP/".phpversion();
   	mail('info@helixonetrading.in', "HelixOne India Contact Form - {$name}", $msg, implode("\r\n", $headers));
}


function successExit($msg) {
	header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.1 200 Ok', true, 200);
	jsonExit(json_encode(array('success' => $msg)));
}


function errorExit($errs) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	jsonExit(json_encode(array('error' => $errs)));
}


function jsonExit($ret) {
	header('Content-Type: application/json');
	exit($ret);
}