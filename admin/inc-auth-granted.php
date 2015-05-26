<?php
session_start();
if (!isset($_SESSION['accessGranted']) || !$_SESSION['accessGranted']) {
	header('Location: /admin/');
}

?>