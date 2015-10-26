<?php
 
	ini_set('max_execution_time', 90000); //300 seconds = 5 minutes
	$whmusername = 'root';
	$whmpassword = 'CHANGEME';    /*  <== put in the root password for WHM which also works for cpanel for any user */
	
	$accountlist_file = file_get_contents("accounts.csv");

	foreach (explode("\r",$accountlist_file) as $csv_row) {

		$csv_row = str_getcsv($csv_row);
				
		$user = $csv_row[0];
		$domain = $csv_row[1];
		$password = $csv_row[2];
		$email = $csv_row[3];
		$plan = $csv_row[4];
		$server = $csv_row[5];
		$mxcheck = $csv_row[6];
		

		$user = preg_replace('/\s+/', '', $user);
		$domain = preg_replace('/\s+/', '', $domain);
		$password = preg_replace('/\s+/', '', $password);
		$email = preg_replace('/\s+/', '', $email);
		$plan = preg_replace('/\s+/', '', $plan);
		$server = preg_replace('/\s+/', '', $server);
		
		$query = 'https://' . $server . ':2087/json-api/createacct?api.version=1&user=' . $user . '&domain=' . $domain . '&password=' . $password . '&contactemail=' . $email . '&plan=' . $plan . '&mxcheck=' . $mxcheck;
		
		$curl = curl_init();       
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);  
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl, CURLOPT_HEADER,0);          
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);  
		$header[0] = "Authorization: Basic " . 
		base64_encode($whmusername.":".$whmpassword) . "\n\r";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
		curl_setopt($curl, CURLOPT_URL, $query); 
		$result = curl_exec($curl);
		if ($result == false) {
			error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $query<br /><br />"); 
															// log error if curl exec fails
		}

		curl_close($curl);
		
		echo "Attempted to create the account for the domain " . $domain . " by referencing the username " . $user . " on the server " . $server . "<br /><br />";
		print $result;
		
		echo "<br /><hr /><br />";
		
		
	}
 
?>