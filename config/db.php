<?php

function getDB(): PDO {
    $dbname = 'fidelx';
    $user = 'root';
    $pass = '';
    $socket = '/tmp/mysql.sock';

    $db = new PDO("mysql:unix_socket=$socket;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $db;
}
