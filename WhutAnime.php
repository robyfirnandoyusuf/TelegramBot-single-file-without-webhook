<?php

while (true) 
{
	WhutAnime::main();
}









class WhutAnime
{

	static protected $token 		= '';
	static protected $usernamebot  	= "";
	static public $base_url;


    public static function main()
    {
    	$check 		= self::getLastMessage();
		$jsonDec 	= json_decode($check,true);
		$dataLast 	= array_slice($jsonDec, -1, 1, true);
		$dataLast 	= end($dataLast['result']);
		$offset 	= (int)$dataLast['update_id'] + 1;

		$getMostNew	= self::getLastMessage($offset);

		if (!empty(json_decode($check)->result)) 
		{
			echo "[+] => [New Message]\n";
	        self::send_message($dataLast);
		}
		else
		{
			echo "[-] => -\n";
		}
    }

    public function base_url()
    {
    	return self::$base_url = "https://api.telegram.org/".self::$token;
    }

    public function getLastMessage($offset='')
    {
    	$get_last = file_get_contents( self::base_url().'/getUpdates'.(!empty($offset) ? "?offset=".$offset : ""));
    	return $get_last;
    }

    public function send_message($dataLast)
	{
		$chatid = $dataLast['message']['chat']['id'];
		$text 	= $dataLast['message']['text'];
		$msgid 	= $dataLast['message']['message_id'];

	    $data = array(
	        'chat_id' 				=> $chatid,
	        'text'  				=> $text,
	        'reply_to_message_id' 	=> $msgid   
	    );

	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::base_url()."/sendMessage");
		curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
		$res = curl_exec($ch);
	   
	}
    
}


?>