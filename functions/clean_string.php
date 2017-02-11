<?php
// Sanatize strings for databases & security
function clean_string($str) { 
	if(get_magic_quotes_gpc()){
		$str = stripslashes($str);
	}
	$str = mysqli_real_escape_string($str);
	$str = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$str);
	$str = strip_tags($str);//tira tags html e php
	$str = addslashes($str);//Adiciona barras invertidas a uma string
	return $str;
}

foreach ($_REQUEST as $index => $value){
      $_REQUEST[$index] = clean_string($value);
}
foreach ($_GET as $index => $value){
      $_GET[$index] = clean_string($value);
}
foreach ($_POST as $index => $value){
      $_POST[$index] = clean_string($value);
}