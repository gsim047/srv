<?php
header("Content-type:text/html; charset: UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: x-requested-with");

// send message: from bot, to wifi/ip state group
include_once(__DIR__."/config_wifi.php");


$host = rtrim(`hostname`);
$text = "server [$host] start. ip:\n";
// $text = $text . `hostname -I`;
$text = $text . `ip a|grep " inet "|grep -v 127.0.`;

$data = [
    'chat_id' => $chat_id,
    'text' => $text
];

$response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );// Do what you want with result

?>
