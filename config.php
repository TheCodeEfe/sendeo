<?php

session_start();
error_reporting(0); //hataları gizle ve error_log'a yazmasın.
ini_set("display_errors",0); // hataları gizle

$host   = '49.12.229.78';
$dbName = 'sendeo';
$user   = 'sendeo';
$pass   = 'sendeo2022*';


try {
    $db = new PDO('mysql:host='.$host.';dbname='.$dbName.';charset=utf8mb4',$user,$pass);
} catch (PDOException $e) {
    die('<h1>Veritabanı Hatası:</h1><br>'.$e->getMessage());
}

//Türkiye saati olarak baz alınsın
date_default_timezone_set('Europe/Istanbul');




//functions
if( file_exists( $_SERVER["DOCUMENT_ROOT"].'/sendeo/') ){
    $path =  $_SERVER["DOCUMENT_ROOT"].'/sendeo/';
}else{
    $path = $_SERVER["DOCUMENT_ROOT"].'/';
}

include_once $path.'functions/functions.php';
include_once $path.'functions/db.php';
include_once $path.'functions/app.php';
include_once $path.'functions/MesajPaneliApi.php';
include_once $path.'functions/sms.php';


