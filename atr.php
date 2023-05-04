<?php
//asd
$idtelegram = $_GET['id_telegram'];
$username = $_GET['username'];
$token = $_GET['tokenbot'];

$atualdados = json_decode(file_get_contents("bot/resource/conf.json")  , true);

$atualdados['dono'] = trim($idtelegram);

$atualdados['userDono'] = trim($username);

$dsalva = json_encode($atualdados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
$salva = file_put_contents('bot/resource/conf.json', $dsalva);

file_put_contents("bot/token.txt", trim($token));