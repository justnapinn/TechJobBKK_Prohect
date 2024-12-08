<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

// Check if the user is logged in and has 'company' user_type
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit();
}

