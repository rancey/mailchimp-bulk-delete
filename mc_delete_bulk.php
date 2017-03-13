<?php

date_default_timezone_set('UTC');

function mailchimpRequest($type, $target, $data = false) {

    $api = array(
            'login' => 'anyuser',
            'key' => '<your API key>',   //here should be your API KEY
            'url' => 'https://us2.api.mailchimp.com/3.0/'
    );
    $ch = curl_init($api['url'] . $target);
    //echo 'url: '.$api['url'] . $target. "\n";

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: ' . $api['login'] . ' ' . $api['key']
        ));

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CS-PHP-SCRIPT');

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $response = curl_exec($ch);
    //echo "\nResponse: ";
	//var_dump($response);
	if ($response === false) {
        echo curl_error($ch);
        die();
    }
    curl_close($ch);
    return json_decode($response, true);
}


function DeleteMembers($filename, $ListId) {

	$row = 1;
	if (($handle = fopen($filename, "r")) !== FALSE) {
    	while ((($data = fgetcsv($handle)) !== FALSE)) {
    		if(count($data) > 0 ) {
    			$id = md5(strtolower($data[0]));
       			echo 'email address:'.$data[0].'; MD5 hash: '.$id.'...';
       			if(mailchimpRequest('DELETE', 'lists/' . $ListId . '/members/' . $id) === NULL) 
       			{ echo "deleted\n"; } else { echo "NOT deleted\n"; }
       		}
       		$row++;
    	}
    	fclose($handle);
	}
}

// MAIN CODE.
$ListID = "<your list ID>";
$EOL = "\r\n";
$cli =  ('cli' === PHP_SAPI);
if (!$cli) { print "FATAL ERROR: CLI use only.".$EOL; }
else
{
	$startup_params = getopt('f:lh');
	$help = (array_key_exists('h', $startup_params) || array_key_exists('hel', $startup_params));
	$filename = (array_key_exists('f', $startup_params)) ? $startup_params['f'] : "";
	$ListId = (array_key_exists('l', $startup_params)) ? $startup_params['l'] : $ListID;
	//echo "ListId = ".$ListId." ; filename = ".$filename.$EOL;
	if (($help) || ($filename =="") || ($ListId == "")) {
		echo "Usage:".$EOL."php mc_delete.php -f <filename> -l ListId".$EOL;
	}
	else
	{
		echo "ListId = ".$ListId." ; filename = ".$filename.$EOL;
		DeleteMembers($filename, $ListId);
	}
}

?>
