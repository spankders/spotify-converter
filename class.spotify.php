<?php
/*
                _______________
 ⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯[ CLASS SPOTIFY ]⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯
  Dibuat oleh : Naufal Ardhan (ardzz)
  Contact     : me@ardzz-45.id          
  IG          : https://www.instagram.com/ar_dhann/ [@ar_dhann]
 ⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯

 ************** CATATAN **************
  1. Kebanyakan Error Dikarenakan : 
   A. KONEKSI INTERNET, minimial kecepatan download 250 kbps keatas :)

  2. Dilarang memperjual belikan! :)

*/
// Colours 

$green  = "\e[1;92m";
$cyan   = "\e[1;36m";
$normal = "\e[0m";
$blue   = "\e[34m";
$green1 = "\e[0;92m";
$yellow = "\e[93m";
$red    = "\e[1;91m";


class spotify{
	function get_string($string, $start, $end){
		$str = explode($start, $string);
		$str = explode($end, $str[1]);
		return $str[0];
	}
	function download($file_source, $file_target){
		$rh = fopen($file_source, 'rb');
		$wh = fopen($file_target, 'w+b');
		if (!$rh || !$wh) {
			return false;
		}
		while (!feof($rh)) {
			if (fwrite($wh, fread($rh, 4096)) === FALSE) {
				return false;
			}
			flush();
		}
		fclose($rh);
		fclose($wh);
		return true;
	}
	function is_root(){
		if(posix_getuid() == 0){
			return true;
		}else{
			return false;
		}
	}
	function curl_post_data($url, $param){
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array(
			"User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36"
			));
		curl_setopt($c, CURLOPT_POSTFIELDS, $param);
		curl_setopt($c, CURLOPT_POST, 1);
		$out = curl_exec($c);
		curl_close($c);
		return $out;
	}
	function email_exists($email){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.spotify.com/id/xhr/json/isEmailAvailable.php?signup_form%5Bemail%5D={$email}&email={$email}");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36', // user-agent bisa diganti sesuka hati <3
		));
		$res = curl_exec($ch);
		$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		return $body;
	}
	function get_csrf(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/login/?_locale=id-ID&continue=https%3A//www.spotify.com/id/account/overview/");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Host: accounts.spotify.com",
			"User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Language: en-US,en;q=0.5",
			"Accept-Encoding: gzip, deflate",
			"Referer: https://www.spotify.com/",
			"DNT: 1",
			"Connection: close",
			"Upgrade-Insecure-Requests: 1",
		));
		$res = curl_exec($ch);
		$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		$csrf_token = self::get_string($header, "csrf_token=",";");
		return $csrf_token;
	}
	/*function login($email, $password){
		$csrf = self::get_csrf();
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, "https://accounts.spotify.com/api/login");
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HEADER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array(
			"User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36",
			"Cookie: csrf_token={$csrf}; __bon=MHwwfDExMDc5NjU4MDR8NDY1MzQ1NjM3Njh8MXwxfDF8MQ==; fb_continue=https%3A%2F%2Fwww.spotify.com%2Fid%2Faccount%2Foverview%2F; remember={$email}"
			));
		$param = "remember=true&username={$email}&password={$password}&captcha_token=&csrf_token=".$csrf;
		curl_setopt($c, CURLOPT_POSTFIELDS, $param);
		curl_setopt($c, CURLOPT_POST, 1);
		$res = curl_exec($c);
		$header = substr($res, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$body = substr($res, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$info = curl_getinfo($c);
		curl_close($c);
		if ($info["http_code"] == "200") {
			return [
			"cookie" => "sp_dc=".self::get_string($header, "Set-Cookie: sp_dc=", ";").";",
			"body" => $body];
		}
		else{
			return false;
		}
	}
	function get_access_token($cookie){
		if (empty($cookie)) {
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/authorize?response_type=token&redirect_uri=https%3A%2F%2Fdeveloper.spotify.com%2Fcallback&client_id=774b29d4f13844c495f206cafdad9c86&scope=user-read-private+user-read-email+user-library-read+user-follow-read+user-top-read+playlist-modify-public+user-read-playback-state+user-modify-playback-state+user-read-recently-played+user-read-currently-playing+user-follow-modify+playlist-modify-private+playlist-read-collaborative+user-library-modify+playlist-read-private+user-read-birthdate&state=ctgy5i"); // nilai variabel scope bisa diganti sesuka hati <3
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36', // user-agent bisa diganti sesuka hati <3
		"Cookie: {$cookie}",
		));
		$res = curl_exec($ch);
		$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		if (preg_match('/access_token/i', $header)) {
			$output = self::get_string($header, "#access_token=", "&");
			return $output;
		}
		elseif (empty($header) && empty($body)) {
			return false;
		}
		else{
			return false;
		}
		//return ["header" => $header, "body" => $body];
	}*/
	function login($email, $password){
		$csrf = self::get_csrf();
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, "https://accounts.spotify.com/api/login");
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HEADER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array(
			"User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36",
			"Cookie: csrf_token={$csrf}; __bon=MHwwfDExMDc5NjU4MDR8NDY1MzQ1NjM3Njh8MXwxfDF8MQ==; fb_continue=https%3A%2F%2Fwww.spotify.com%2Fid%2Faccount%2Foverview%2F; remember={$email}"
			));
		$param = "remember=true&username={$email}&password={$password}&captcha_token=&csrf_token=".$csrf;
		curl_setopt($c, CURLOPT_POSTFIELDS, $param);
		curl_setopt($c, CURLOPT_POST, 1);
		$res = curl_exec($c);
		$header = substr($res, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$body = substr($res, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$info = curl_getinfo($c);
		curl_close($c);
		if ($info["http_code"] == "200") {
			return [
			"cookie" => "sp_dc=".self::get_string($header, "Set-Cookie: sp_dc=", ";").";",
			"csrf" => $csrf,
			"body" => $body];
		}
		else{
			return false;
		}
	}
	function get_access_token($cookie, $csrf){
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, "https://accounts.spotify.com/authorize/accept");
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HEADER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array(
			"User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36",
			"Cookie: csrf_token={$csrf}; {$cookie};",
			));
		$param = "response_type=token&redirect_uri=https%3A%2F%2Fdeveloper.spotify.com%2Fcallback&client_id=774b29d4f13844c495f206cafdad9c86&scope=user-read-private+user-read-email+user-library-read+user-top-read+playlist-modify-public+user-read-playback-state+user-follow-read+user-modify-playback-state+user-read-recently-played+user-read-currently-playing+user-follow-modify+playlist-modify-private+playlist-read-collaborative+user-library-modify+playlist-read-private+user-read-birthdate&csrf_token=".$csrf;
		curl_setopt($c, CURLOPT_POSTFIELDS, $param);
		curl_setopt($c, CURLOPT_POST, 1);
		$res = curl_exec($c);
		$header = substr($res, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$body = substr($res, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$info = curl_getinfo($c);
		curl_close($c);
		if (preg_match('/access_token/i', $header)) {
			$output = self::get_string($header, "#access_token=", "&");
			return $output;
		}
		elseif (empty($header) && empty($body)) {
			return false;
		}
		else{
			return false;
		}
		//return ["header" => $header, "body" => $body];
	}
	function get_id($string){
		if (preg_match('/(.*?)spotify:playlist:(.*?)/si', $string)) {
			return explode(":", $string)[4];
		}
		elseif (preg_match("'<iframe src=\"https://open.spotify.com/embed/user/spotify/playlist/(.*?)\" width=\"300\" height=\"380\" frameborder=\"0\" allowtransparency=\"true\" allow=\"encrypted-media\"></iframe>'si", $string, $match)) {
			return $match[1];
		}
		elseif (preg_match("'https:\/\/open.spotify.com\/user\/spotify\/playlist\/(.*?)si=(.*?)'si", $string, $match)) {
			return str_replace("?", "", $match[1]);
		}
		else{
			return $string;
		}
	}
	/*function get_playlist($cookie, $id_playlist){
		$id_playlist = self::get_id($id_playlist);
		$access_token = self::get_access_token($cookie);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/playlists/{$id_playlist}");
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36',
				"authorization: Bearer {$access_token}",
				));
			$res = curl_exec($ch);
			$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$info = curl_getinfo($ch);
			curl_close($ch);
			if ($info["http_code"] == "200") {
				$nama_file = json_decode($body,1)["name"].".js";
				if (!file_exists($nama_file)) {
					file_put_contents($nama_file, $body);
					return json_decode($body,1);
				}else{
					return json_decode($body,1);
				}
			}
			else{
				return false;
			}
	}*/
	function get_playlist($cookie, $id_playlist, $csrf){
		$id_playlist = self::get_id($id_playlist);
		$access_token = self::get_access_token($cookie, $csrf);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/playlists/{$id_playlist}");
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36',
				"authorization: Bearer {$access_token}",
				));
			$res = curl_exec($ch);
			$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$info = curl_getinfo($ch);
			curl_close($ch);
			if ($info["http_code"] == "200") {
				$nama_file = json_decode($body,1)["name"].".js";
				if (!file_exists($nama_file)) {
					file_put_contents($nama_file, $body);
					return json_decode($body,1);
				}else{
					return json_decode($body,1);
				}
			}
			else{
				return false;
			}
	}
	function youtube_search($query){
		$api_key = "AIzaSyC9PbnUX-8rXVn32Z5Ty2YnqP4XMnT9zuE";
		$query_encoded = urlencode($query);
		$json_data = file_get_contents("https://www.googleapis.com/youtube/v3/search?part=id,snippet&q={$query_encoded}&type=video&key={$api_key}");
		$array_data = json_decode($json_data, 1);
		return $array_data["items"][0]["id"]["videoId"]; // return video id
	}
	function youtube_convert_mp3($id){
		// proses 1
		$url = "https://www2.onlinevideoconverter.com/webservice";
		$parameter = "function=validate&args[dummy]=1&args[urlEntryUser]=https://youtube.com/watch?v={$id}&args[fromConvert]=urlconverter&args[requestExt]=mp3&args[nbRetry]=0&args[videoResolution]=-1&args[audioBitrate]=0&args[audioFrequency]=0&args[channel]=stereo&args[volume]=0&args[startFrom]=-1&args[endTo]=-1&args[custom_resx]=-1&args[custom_resy]=-1&args[advSettings]=false&args[aspectRatio]=-1";
		$curl_post_data = self::curl_post_data($url,$parameter);
		$array_data = json_decode($curl_post_data,1);
		if ($array_data["result"]["status"] == "default") {
			// proses 2
			$url = "https://www.onlinevideoconverter.com/success";
			$id = $array_data["result"]["dPageId"];
			$parameter = "id={$id}";
			$curl_post_data = self::curl_post_data($url, $parameter);
			$url_download = self::get_string($curl_post_data,'<a style=\'display:none\' class="download-button" href="','" id="downloadq">Download</a>');
			$size = self::get_string($curl_post_data,'.mp3                                                &nbsp;
                                                ','MB                                            </p>
                                        </div>');
			$output = array(
				"url_download" => $url_download,
				"size" => "{$size}MB"
				);
				return $output;
		}else{
			return false;
		}
	}
	function youtube_video_info($id){
		$data = file_get_contents("https://api.zonkploit.com/yt/video-info/{$id}");
		$array_data = json_decode($data, 1);
		return $array_data;
	}
	function check_update(){
		$array = json_decode(file_get_contents("https://raw.githubusercontent.com/ardzz/repo/master/spotify-converter/update.js"),1);
		if (text::show_version() == $array["version"]) {
			if ($array["update"]) {
				return true;
			}
			else{
				return $array["msg"];
			}
		}
		else{
			return false;
		}
	}

}
class text{
	
	public function show_banner(){
		global $green;
		global $cyan;
		global $normal;
		global $blue;
		global $green1;
		global $yellow;
		global $red;
		$version = self::show_version();
		return $green1."
  .▄▄ ·  ▄▄▄·      ▄▄▄▄▄▪  ·▄▄▄ ▄· ▄▌ 
  ▐█ ▀. ▐█ ▄█▪     •██  ██ ▐▄▄·▐█▪██▌
  ▄▀▀▀█▄ ██▀· ▄█▀▄  ▐█.▪▐█·██▪ ▐█▌▐█▪
  ▐█▄▪▐█▐█▪·•▐█▌.▐▌ ▐█▌·▐█▌██▌. ▐█▀·.
   ▀▀▀▀ .▀    ▀█▄▀▪ ▀▀▀ ▀▀▀▀▀▀   ▀ •{$normal} 
    {$blue}═══════════════════════════════{$normal}                       
     {$yellow}╔═╗╔═╗╔╗╔╦  ╦╔═╗╦═╗╔╦╗╔═╗╦═╗
     ║  ║ ║║║║╚╗╔╝║╣ ╠╦╝ ║ ║╣ ╠╦╝
     ╚═╝╚═╝╝╚╝ ╚╝ ╚═╝╩╚═ ╩ ╚═╝╩╚═{$normal} 
              {$cyan}🎧  {$version} 🎧{$normal}                                 
".PHP_EOL;
	}
	public function show_menu(){
		global $green;
		global $cyan;
		global $normal;
		global $blue;
		global $green1;
		global $yellow;
		global $red;
		$current_user = get_current_user();
		$hostname     = gethostname();
		if (spotify::is_root()) {
		$user_input = "[{$green}root{$normal}@{$yellow}{$hostname}{$normal} ~ {$cyan}Option Input{$normal}]#";
	}
	else{
		$user_input = "[{$green}{$current_user}{$normal}@{$yellow}{$hostname}{$normal} ~ {$cyan}Option Input{$normal}]$";
		}
		return "
   [1] Get an Access Token
   [2] Get Playlist → Save as *.js file
   [3] Get Song Names in Playlists [SONG]
   [4] Get Song & Artists Names in Playlist [SONG & ARTISTS]
   [5] Conversion of songs from playlist to mp3
   [6] Check update
   [7] About
   [8] Help
   [9] Exit

   {$user_input}
   ".PHP_EOL;
	}
	public function clear_screen(){
		return chr(27).chr(91).'H'.chr(27).chr(91).'J';
	}
	public function show_version(){
		return "1.5";
	}
	public function censor_string($email){
		$prop = 2;
		$domain = substr(strrchr($email, "@"), 1);
		$mailname=str_replace($domain,'',$email);
		$name_l=strlen($mailname);
		$domain_l=strlen($domain);
		$start = NULL;
		$end = NULL;
		for($i=0;$i<=$name_l/$prop-1;$i++){
			$start.='*';
		}
        for($i=0;$i<=$domain_l/$prop-1;$i++){
        	$end.='*';
        }
        return substr_replace($mailname, $start, 2, $name_l/$prop).substr_replace($domain, $end, 2, $domain_l/$prop);
	}
	public function show_about(){
		global $green;
		global $cyan;
		global $normal;
		global $blue;
		global $green1;
		global $yellow;
		global $red;
		return self::clear_screen().str_replace("#","{$cyan}#{$normal}","

                 {$green1}╔═╗╔╗ ╔═╗╦ ╦╔╦╗{$normal}
                 {$green1}╠═╣╠╩╗║ ║║ ║ ║{$normal}
                 {$green1}╩ ╩╚═╝╚═╝╚═╝ ╩{$normal} 
          {$blue}════════════════════════════{$normal}

    +-------------+-------------------------+
    | Data        | Value                   |
    +-------------+-------------------------+
    | Package     | {$green}Spotify Converter{$normal} [CLI] |
    | Version     | 1.5 [full version]      |
    | Open Source | TRUE                    |
    | Author      | {$green1}Naufal Ardhan{$normal} [{$cyan}Ardzz{$normal}]   |
    | Contact     | me@ardzz-45.id          |
    +-------------+-------------------------+

");
	}
	public function show_help(){
		global $green;
		global $cyan;
		global $normal;
		global $blue;
		global $green1;
		global $yellow;
		global $red;
		return self::clear_screen().
"	         {$blue}══════════════════════════════{$normal}
                          {$green1}╦ ╦╔═╗╦  ╔═╗{$normal}
                          {$green1}╠═╣║╣ ║  ╠═╝{$normal}
                          {$green1}╩ ╩╚═╝╩═╝╩{$normal}  
                 {$blue}══════════════════════════════{$normal}
   +-----------------------+------------------------------+
   | Menu                  | Description                  |
   +-----------------------+------------------------------+
   | Get an Access Token   | An access token will be      |
   |                       | used when performing cURL    |
   |                       | on the Spotify API           |
   | --------------------- | ---------------------        |
   | Get Playlists         | Get playlists                |
   |                       | and songs from Spotify       |
   | --------------------- | ---------------------        |
   | Conversion of songs   | This menu may require        |
   | from playlist to mp3  | 250MB of space               |
   |                       | and a minimum connection     |
   |                       | speed of >250Kbps            |
   +-----------------------+------------------------------+

";
	}
	function delay($seconds){
		$seconds = abs($seconds);
		if ($seconds < 1){
			usleep($seconds * 1000000);
		}
		else{
			sleep($seconds);
		}
	}
	function loading($color){
		$normal = "\e[0m";
		echo $color . "   [*] Please Wait, Loading";
		for ($i = 1; $i <= 10; $i++){
			self::delay(0.25);
			$loading = $color . "." . $normal;
			echo $loading;
		}
	}
}
?>
