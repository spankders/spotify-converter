<?php
/**
* @author Naufal Ardhan [ardzz]
* @package Spotify Converter
* Contact me@ardzz-45.id
* Created at 13.32.31 WIB (Asia/Jakarta) 

* Silahkan recode tapi jangan dihapus copyrightnya! :)

 ************** CATATAN **************
  1. Kebanyakan Error Dikarenakan : 
   A. KONEKSI INTERNET, minimial kecepatan download 250 kbps keatas :)
   
  2. Dilarang memperjual belikan! :)

*/

if (!file_exists("class.spotify.php")) {
	echo "   {$red}[!] Error! Missing File class.spotify.php".PHP_EOL;
	exit();
}
if (!file_exists("config.php")) {
	echo "   {$red}[!] Error! Missing File config.php";
}
include 'class.spotify.php';
include 'config.php';
$text_spotify = new text();
$spotify = new spotify();
$finish = "   {$green}[*] {$normal}Finish!".PHP_EOL;
echo $text_spotify::clear_screen();
echo $text_spotify::show_banner();
menu:
echo $text_spotify::show_menu();
$input_opsi = readline("   [*] Option Input : ");
if ($input_opsi < 6) {
	$login = $spotify::login($email, $password);
	$cookie = $login["cookie"];
	if ($spotify::email_exists($email) == "true") {
		echo PHP_EOL;
		echo "   {$red}[!] Can't Login!{$normal}".PHP_EOL;
		echo "   {$red}[!] Email     : ".$text_spotify::censor_string($email).$normal.PHP_EOL;
		echo "   {$red}[*] Error Msg : Email isn't already registered!".PHP_EOL;
		echo $finish;
		exit();
	}
	if (!$cookie) {
		echo PHP_EOL;
		echo "   {$red}[!] Can't Login!{$normal}".PHP_EOL;
		echo "   {$red}[!] Email     : ".$text_spotify::censor_string($email).$normal.PHP_EOL;
		echo "   {$red}[*] Error Msg : Wrong Password!".PHP_EOL;
		echo $finish;
		exit();
	}
}
if ($input_opsi == 1) {
	$text_spotify->loading($normal);
	echo PHP_EOL;
	$access_token = $spotify::get_access_token($cookie, $login["csrf"]);
	if ($access_token) {
		if (file_exists("access_token.txt")) {
			echo "   {$red}[!] access_token.txt file is already exists{$normal}".PHP_EOL;
			$kondisi = readline("   [?] Wont to replace it? [y/n] : ");
			if (strtolower($kondisi) == "y") {
				unlink("access_token.txt");
				file_put_contents("access_token.txt", $access_token);
				echo "   {$green}[*]{$normal} Managed to get and save it in the file access_token.txt".PHP_EOL;
				echo $finish;
			}
			elseif (strtolower($kondisi) == "n") {
				$file = readline("   [?] Save as : ");
				if (empty($file)) {
					$file = "access_token_".rand(000000,999999).".txt";
				}
				file_put_contents($file, $access_token);
				echo $finish;
				echo "   {$green}[*]{$normal} Managed to get and save it in the file {$file}".PHP_EOL;
			}
		}
		else{
			file_put_contents("access_token.txt", $access_token);
			echo "   {$green}[*]{$normal} Managed to get and save it in the file access_token.txt".PHP_EOL;
			echo $finish;
		}
	}
	else{
		echo "   {$red}[!] Error!{$normal}".PHP_EOL;
	}
}
elseif ($input_opsi == 2) {
	$input = readline("   [?] Input Spotify Playlist ID : ");
	if (empty($input)) {
		echo "   {$red}[!] Error!{$normal}".PHP_EOL;
		exit();
	}
	$text_spotify->loading($normal);
	echo PHP_EOL;
	$playlist = $spotify::get_playlist($cookie, $input, $login["csrf"]);
	if ($playlist) {
		echo "   [*] Saved to ".$cyan.$playlist["name"].".js{$normal}".PHP_EOL;
		echo $finish;
	}else{
		echo "   {$red}[!] Invalid Playlist ID{$normal}".PHP_EOL;
	}
}
elseif ($input_opsi == 3) {
	$input = readline("   [?] Input Spotify Playlist ID : ");
	echo PHP_EOL;
	if (empty($input)) {
		echo "   {$red}[!] Error!{$normal}".PHP_EOL;
		exit();
	}
	$text_spotify->loading($normal);
	echo PHP_EOL;
	$playlist = $spotify::get_playlist($cookie, $input, $login["csrf"]);
	if($playlist){
		echo "   [*] Playlist Name : ".$playlist["name"].PHP_EOL;
		echo "   [*] Description   : ".$playlist["description"].PHP_EOL.PHP_EOL;
		$x = 1;
		foreach ($playlist["tracks"]["items"] as $var){
			$song = $var["track"]["name"];
			echo "   [".$x++."] {$song}".PHP_EOL;
		}
		echo $finish;
	}
	else{
		echo "   {$red}[!] Invalid Playlist ID{$normal}".PHP_EOL;
	}
}
elseif ($input_opsi == 4) {
	$input = readline("   [?] Input Spotify Playlist ID : ");
	echo PHP_EOL;
	if (empty($input)) {
		echo "   {$red}[!] Error!{$normal}".PHP_EOL;
		exit();
	}
	$text_spotify->loading($normal);
	echo PHP_EOL;
	$playlist = $spotify::get_playlist($cookie, $input, $login["csrf"]);
	if($playlist){
		echo "   [*] Playlist Name : ".$playlist["name"].PHP_EOL;
		echo "   [*] Description   : ".$playlist["description"].PHP_EOL.PHP_EOL;
		$x = 1;
		foreach ($playlist["tracks"]["items"] as $var){
			$song = $var["track"]["name"];
			$artists = [];
			foreach($var["track"]["artists"] as $y) {
				$artists[] = $y["name"];
			}
			$artist = implode(", ", $artists);
			echo "   [".$x++."] {$song} ⎯ {$cyan}{$artist}{$normal}".PHP_EOL;
		}
		echo $finish;
	}
	else{
		echo "   {$red}[!] Invalid Playlist ID{$normal}".PHP_EOL;
	}
}
elseif ($input_opsi == 5) {
	$input = readline("   [?] Input Spotify Playlist ID : ");
	echo PHP_EOL;
	if (empty($input)) {
		echo "   {$red}[!] Error!{$normal}".PHP_EOL;
		exit();
	}
	$text_spotify->loading($normal);
	echo PHP_EOL;
	$playlist = $spotify::get_playlist($cookie, $input, $login["csrf"]);
	if($playlist){
		echo "   [*] Playlist Name : ".$playlist["name"].PHP_EOL;
		echo "   [*] Description   : ".$playlist["description"].PHP_EOL.PHP_EOL;
		$x = 1;
		foreach ($playlist["tracks"]["items"] as $var){
			$song = $var["track"]["name"];
			$url = $var["track"]["external_urls"]["spotify"];
			$artists = [];
			foreach($var["track"]["artists"] as $y) {
				$artists[] = $y["name"];
			}
			$artist = implode(", ", $artists);
			echo "   [".$x++."] {$song} ⎯ {$cyan}{$artist}{$normal}".PHP_EOL;
			echo "   [*] {$url} [ {$green}Spotify{$normal} ]".PHP_EOL;
			echo "   [*] Retrieving song information from Youtube ...".PHP_EOL;
			$id = $spotify::youtube_search($song." ".$artist);
			$vid_info = $spotify::youtube_video_info($id);
			echo "   [*] Video Title  : ".$vid_info["video_title"]." [ {$red}YouTube{$normal} ]".PHP_EOL;
			echo "   [*] Channel Name : ".$vid_info["channel_title"].PHP_EOL;
			echo "   [*] Channel ID   : ".$vid_info["channel_id"].PHP_EOL;
			echo "   [*] Duration     : ".$vid_info["duration"].PHP_EOL;
			echo "   [*] URL          : https://youtube.com/watch?v={$id}".PHP_EOL;
			if (!is_dir($playlist["name"])) {
				mkdir($playlist["name"]);
			}
			$convert_to_mp3 = $spotify::youtube_convert_mp3($id);
			if ($convert_to_mp3) {
				echo "   {$green1}[*] Success convert to mp3{$normal}".PHP_EOL;
				echo "   [*] URL Download : ".$convert_to_mp3["url_download"].PHP_EOL;
				echo "   [*] Size         : ".$convert_to_mp3["size"].PHP_EOL;
				echo "   [*] Downloading file, it might take some time ...".PHP_EOL;
				$path = getcwd()."/".$playlist["name"];
				$title = str_replace("/"," - ", $vid_info["video_title"])." [ SPOTIFY CONVERTER - Ardzz ].mp3";
				if ($spotify::download($convert_to_mp3["url_download"], $path."/".$title)) {
					echo "   {$green1}[*] Successfully downloaded the file and saved it at {$red}\"".$cyan.$playlist["name"]."/".$title.$normal.$red."\"".$normal.PHP_EOL;
				}else{
					echo "  {$red}[!] Failed to download file{$normal}".PHP_EOL;
				}
			}
			else{
				"   {$red}[!] Failed to convert to mp3{$normal}".PHP_EOL;
			}
			echo PHP_EOL;
		}
		echo $finish;
	}
	else{
		echo "   {$red}[!] Invalid Playlist ID{$normal}".PHP_EOL;
	}
}
elseif ($input_opsi == 6) {
	$text_spotify->loading($normal);
	echo PHP_EOL;
	$update = $spotify::check_update();
	if (!$update) {
		echo "   [*] Update Is Available!".PHP_EOL;
		echo "   [*] Visit https://github.com/ardzz/spotify-converter".PHP_EOL;
	}else{
		echo "   [*] Update Isn't Available! ".$update.PHP_EOL;
	}
}
elseif ($input_opsi == 7) {
	echo $text_spotify::show_about();
}
elseif ($input_opsi == 8) {
	echo $text_spotify::show_help();
}
elseif ($input_opsi == 9) {
	exit();
}
else{
	echo "   {$red}[!] Invalid Option!{$normal}".PHP_EOL;
	exit();
}
?>
