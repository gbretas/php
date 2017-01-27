<?php
// Sanatize strings for databases & security
function clean($str) { 
  $str = @trim($str);
	if(get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysqli_real_escape_string($str);
}