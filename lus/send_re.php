<?php
include_once("config_re.php");

/*
echo "argc: $argc\n";
for ( $i = 0; $i < $argc; $i++ ){
	$a = $argv[$i];
	echo "[$i]: $a\n";
}

if ( $argc <= 1 )
	return 1;

echo "-1-\n";

*/

$host = gethostname();
$arc = $host . ".bz";
$old = $arc . ".old";

$usr = get_current_user();
$ph0 = getcwd();       // init cwd

$ph1 = "/home/". $usr . "/tmp/" . getRandomString(16);
if ( $usr == "root" ){
	$ph1 = "/". $usr . "/tmp/" . getRandomString(16);
}
date_default_timezone_set('Europe/Moscow');
$dat = date("Y-m-d H-i-s");
//`date -R`;

$log = $ph0 . "/result.txt";
$fo = fopen($log, "w");
//system("echo '$host / $dat' > $log");
fprintf($fo, "%s / %s\n", $host, $dat);

$farc = $ph0 . "/" . $arc;
$fold = $ph0 . "/" . $old;

if ( file_exists($farc) ){
	system("cp -f $farc $fold");
	unlink($farc);
	if ( file_exists($farc) ){
		fprinf($fo, "can't delete arc file $arc!\n");
		fclose($fo);
		reurn -1;
	}
}

echo "to dl $url/$arc\n";
system("wget -q $url/$arc");

//$fn = realpath($arc);  // full path of arc file
//$base = basename($fn); // nameext of arc file
//system("echo '$base:\n' >> $log");
fprintf($fo, "%s:\n", $arc);

if ( !file_exists($farc) ){
	//system("echo 'no file $fn!'>> $log");
	fprintf($fo, "no file $farc!\n");
	fclose($fo);
	return -2;
}

{
	$out = null;
	$res = null;
	$erl = exec("cmp -b -s $farc $fold", $out, $res);

	if ( $res == 0 ){
		fprintf($fo, "old cmd file $arc!\n");
		fclose($fo);
		return -3;
	}
}
	


//$usr = trim(`whoami`);


echo "$ph0 ::: $ph1\n";

//system("mkdir -p $ph1");
mkdir($ph1);
chdir($ph1);
echo "pwd: " . getcwd() . "\n";

$psw = file_get_contents("/home/user/bin/psw.txt");

system("7z x -p$psw $farc");
system("ls -lA");


{
	fprintf($fo, "-----\n");
	$out = null;
	$res = null;
	$erl = exec("bash ./run.sh", $out, $res);
//	system("bash ./run.sh >> $log");
	//system("echo '$out' >> $log");
	fprintf($fo, "%s\n", implode("\n", $out));
	fprintf($fo, "-----\n");
}
//system("cp ./result.txt $ph0/");
//sendTgMsg("test 1", $chat_id);
sendTgFromFile($log, $chat_id);

chdir($ph0);

system("rm -f -r $ph1/*");
rmdir($ph1);

fclose($fo);

?>
