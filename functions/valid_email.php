<?php
// Check if email is valid
// Usage: valid_email($email, $test_mx = Optional); 
// TestMX is a function to test if Hostname exists :)
function valid_email($email, $test_mx = false) { /* checks if email address is valid */
	if(eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)){
		if($test_mx){
			list($username, $domain) = split("@", $email);
			return getmxrr($domain, $mxrecords);
		}else
			return true;
		}
	}else{
		return false;
	}
}