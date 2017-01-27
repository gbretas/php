<?php
if (file_exists('./phpFunction.php')){
	unlink('./phpFunction.php');
}

$functions = glob('functions/*.php');
if(count($functions) > 0){
	file_put_contents('./phpFunctions.php', "<?php\n", FILE_APPEND);
	foreach($functions as $function){			
		$code = str_replace('<?php', '', file_get_contents($function));
		file_put_contents('./phpFunctions.php', "{$code}\n\n//---------------------------------------------------------------------------", FILE_APPEND);
	}
}
	