<?php
session_start();
require_once 'databaseConnect.php';

// Check if user is logged in or not authorized
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit();
}