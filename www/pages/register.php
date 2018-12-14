<?php

use app\models\Settings;
use newzflash\Users;
use newzflash\db\DB;
use newzflash\Captcha;

require_once('config.php');

if ($page->users->isLoggedIn()) {
	header('Location: ' . WWW_TOP . '/');
}

$error = $firstName = $lastName = $userName = $password = $confirmPassword = $email = $inviteCode = $inviteCodeQuery = $customer_id = '';
$showRegister = 1;

$value = Settings::value('..registerstatus');
if ($value == Settings::REGISTER_STATUS_CLOSED || $value == Settings::REGISTER_STATUS_API_ONLY) {
	$error = "Registrations are currently disabled.";
	$showRegister = 0;
} elseif ($value == Settings::REGISTER_STATUS_INVITE && (!isset($_REQUEST["invitecode"]) || empty($_REQUEST['invitecode']))) {
	$error = "Registrations are currently invite only.";
	$showRegister = 0;
}

if ($showRegister == 1) {
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';

	//Be sure to persist the invite code in the event of multiple form submissions. (errors)
	if (isset($_REQUEST['invitecode'])) {
		$inviteCodeQuery = '&invitecode=' . htmlspecialchars($_REQUEST["invitecode"]);
	}

	$captcha = new Captcha($page);

	switch ($action) {
		case 'submit':
			if ($captcha->getError() === false) {
				$firstName = (isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '');
				$lastName = (isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '');
				$userName = htmlspecialchars($_POST['username']);
				$password = htmlspecialchars($_POST['password']);
				$confirmPassword = htmlspecialchars($_POST['confirmpassword']);
				$email = htmlspecialchars($_POST['email']);
				$inviteCode = htmlspecialchars($_POST["invitecode"]);

				// Check uname/email isn't in use, password valid. If all good create new user account and redirect back to home page.
				if ($password != $confirmPassword) {
					$error = "Password Mismatch";
				} else {
                    // Get the default user role.
                    $userDefault = $page->users->getDefaultRole();

                    $ret = $page->users->signUp($userName, $firstName, $lastName, $password, $email,
                        $_SERVER['REMOTE_ADDR'], $userDefault['id'], $userDefault['defaultinvites'], $inviteCode
                    );
                    if ($ret > 0) {
                    	//Move this to the signUp function class or whatever...
                    	try {
		                    $token = $_POST['stripeToken'];

		                    $customer = \Stripe\Customer::create(
			                    array(
				                    'email' => $email,
				                    'source' => $token
			                    )
		                    );

		                    // This is a $.50 charge in US Dollar.
		                    $sub = \Stripe\Subscription::create(
			                    array(
				                    'items' => [['plan' => 'monthly_sub']],
				                    'customer' => $customer->id
			                    )
		                    );

		                    $userID = '';

		                    $userID = $page->users->getByUsername($userName)['id'];

		                    $page->users->addSubscription($userID, $sub->id);

		                    $page->users->login($ret, $_SERVER['REMOTE_ADDR']);
	                        header("Location: " . WWW_TOP . "/");

	                    } catch(Exception $e) {
                    		header("Location: " . WWW_TOP . "/error");
		                    error_log("Unable to sign up customer:" . $_POST['stripeEmail'].
			                    ", error:" . $e->getMessage());
	                    }
                    } else {
                        switch ($ret) {
                            case Users::ERR_SIGNUP_BADUNAME:
                                $error = "Your username must be at least five characters.";
                                break;
                            case Users::ERR_SIGNUP_BADPASS:
                                $error = "Your password must be longer than eight characters.";
                                break;
                            case Users::ERR_SIGNUP_BADEMAIL:
                                $error = "Your email is not a valid format.";
                                break;
                            case Users::ERR_SIGNUP_UNAMEINUSE:
                                $error = "Sorry, the username is already taken.";
                                break;
                            case Users::ERR_SIGNUP_EMAILINUSE:
                                $error = "Sorry, the email is already in use.";
                                break;
                            case Users::ERR_SIGNUP_BADINVITECODE:
                                $error = "Sorry, the invite code is old or has been used.";
                                break;
                            default:
                                $error = "Failed to register.";
                                break;
                        }
                    }
                }
			}

			break;
		case "view": {
				$inviteCode = isset($_GET["invitecode"]) ? htmlspecialchars($_GET["invitecode"]) : null;
				if (isset($inviteCode)) {
					// See if it is a valid invite.
					$invite = $page->users->getInvite($inviteCode);
					if (!$invite) {
						$error = sprintf("Bad or invite code older than %d days.", Users::DEFAULT_INVITE_EXPIRY_DAYS);
						$showRegister = 0;
					} else {
						$inviteCode = $invite["guid"];
					}
				}
				break;
			}
	}
}
$page->smarty->assign([
		'username'          => $userName,
		'firstname'         => $firstName,
		'lastname'          => $lastName,
		'password'          => $password,
		'confirmpassword'   => $confirmPassword,
		'email'             => $email,
		'stripe_key'        => Settings::value('stripepublishablekey'),
		'invitecode'        => $inviteCode,
		'invite_code_query' => $inviteCodeQuery,
		'showregister'      => $showRegister,
		'error'             => $error
	]
);
$page->meta_title = "Register";
$page->meta_keywords = "register,signup,registration";
$page->meta_description = "Register";

$page->content = $page->smarty->fetch('register.tpl');
$page->render();
