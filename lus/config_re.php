<?php

$url = "https://gsim047.ru/srvcmd";
$chat_id = '-1001927135291';


function getRandomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ( $i = 0; $i < $n; $i++ ){
        $index = random_int(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}// getRandom


function rmDirectory($dir) 
{
    if ( !file_exists($dir) )
        return true;

    if ( !is_dir($dir) )
        return unlink($dir);

    foreach ( scandir($dir) as $item ){
        if ($item == '.' || $item == '..')
            continue;

        if ( !rmDirectory($dir . DIRECTORY_SEPARATOR . $item))
            return false;

    }
    return rmdir($dir);
}// rmDir


function sendTgMsg($text, $id)
{
	$apiToken = "6003785445:AAEmY45xsOV7thvYtwVaG98m8zNbl-Ncxiw";
	$data = [
	    'chat_id' => $id,
	    'text' => $text
	];

	$response = file_get_contents("https://api.telegram.org/bot" . $apiToken . "/sendMessage?" . http_build_query($data) );
		// Do what you want with result
	printf($response);
}//


function sendTgFromFile($fn, $id)
{
	$text = file_get_contents($fn);
	sendTgMsg($text, $id);
}//

?>
