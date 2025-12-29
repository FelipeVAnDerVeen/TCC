<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../TCC/login.php");
    exit;
}
