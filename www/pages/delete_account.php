<?php

use newzflash\Category;
use newzflash\NZBGet;
use newzflash\SABnzbd;
use newzflash\Users;
use newzflash\utility\Misc;
use newzflash\utility\Text;

require_once('config.php');

if (!$page->users->isLoggedIn()) {
	$page->show403();
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';

$userid = $page->users->currentUserId();
$data = $page->users->getById($userid);

if (!$data) {
	$page->show404();
}

$errorStr = '';

switch ($action) {

	case 'submit':
		$sub_id = $page->users->getSubscription($userid);

		//Cancel the subscription
		$sub = \Stripe\Subscription::retrieve($sub_id['subscription_id']);

		$sub->cancel();

		//Delete the user
		$page->users->delete($userid);

		//Go to the login page
		header("Location:" . WWW_TOP . "/logout");

		die();
		break;

	case 'view':
	default:
		header("Location:" . WWW_TOP . "/profileedit");
		break;
}

//$page->smarty->assign('error', $errorStr);
//$page->smarty->assign('user', $data);
//
//$page->meta_title = "Delete User";
//$page->meta_keywords = "delete,user";
//$page->meta_description = "Delete User Profile for " . $data["username"];
//
//
//$page->content = $page->smarty->fetch('profileedit.tpl');
//$page->render();


