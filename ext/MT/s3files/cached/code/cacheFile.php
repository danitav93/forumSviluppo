<?php
 
$arrayImmagesType = array('image/png','image/jpeg','image/gif');
		
$arrayFilesType = array('text/plain','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 
define('IN_PHPBB', true); 
if (!defined('PHPBB_ENVIRONMENT'))
{
	@define('PHPBB_ENVIRONMENT', 'production');
}

 if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
		$messageError="Attenzione! File upload error: ".$_FILES['file']['error'];
		if ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE) {
			$messageError="Attenzione! File troppo grande";
		}
        response(422 ,$messageError,NULL,NULL);
		
		
		return;

 }


$name = basename( $_FILES['file']['name']);

$hash = randomString(40);
 
$path= '../'.$hash;

move_uploaded_file($_FILES['file']['tmp_name'], $path);

$size= basename( $_FILES['file']['size'])/1000;


$mymeType=mime_content_type($path);

//sets for db
$phpbb_root_path = '../../../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'includes/startup.' . $phpEx);
require($phpbb_root_path . 'phpbb/class_loader.' . $phpEx);
$phpbb_class_loader = new \phpbb\class_loader('phpbb\\', "{$phpbb_root_path}phpbb/", $phpEx);
$phpbb_class_loader->register();
$phpbb_config_php_file = new \phpbb\config_php_file($phpbb_root_path, $phpEx);
// Set up container
$phpbb_container_builder = new \phpbb\di\container_builder($phpbb_root_path, $phpEx);
$phpbb_container = $phpbb_container_builder->with_config($phpbb_config_php_file)->get_container();
/* @var $db \phpbb\db\driver\driver_interface */
$db= $phpbb_container->get('dbal.conn');


//peso orfani in GB
$sql= "SELECT SUM(size) FROM phpbb_s3 where flag_orfano=1";
$result=$db->sql_query($sql);
$peso_orfani=$db->sql_fetchrow($result)['SUM(size)']/1000000;
$db->sql_freeresult($result); 
$max_cache_size = 0.5;

if ($peso_orfani>$max_cache_size) {//check if cache is not too big
	response(422 ,"Attenzione! Non è possibile al momento aggiungere file. Se il problema persiste contattare un amministratore",NULL,$path);
} else if ((strpos($name, '#') !== false) or (strpos($name, '*')!==false)) {//check if name is valid
	response(422 ,"Errore! Il nome del file non deve contenere i seguenti caratteri: #,* ",NULL,$path);
} else if (!in_array($mymeType,$arrayImmagesType ) and !in_array($mymeType,$arrayFilesType )) { //check if extension is valid
	response(422 ,"Errore! Estensione ".$mymeType."  non supportata. Estensioni ammesse: jpg,png,gif,doc,pdf,txt",NULL,$path);
} else if ($size>600) { //check if size is valid
	response(422 ,"Errore! Dimensone file troppo grande. Dimensione massima 600 KB",NULL,$path);
} else {
	
	$tag="file";
	if (in_array($mymeType,$arrayImmagesType )) {
		$tag="image";
	}

	$link = '../cached/'.$hash; 
	
	$sql = 'INSERT INTO phpbb_s3 ' . $db->sql_build_array('INSERT', array(
					'nome'		=> (string) $name,
					'link'		=> (string) $link,
					'hash'		=> (string) $hash,
					'size'		=> (float) $size,
					'tag'		=> (string) $tag,
					));
	$db->sql_query($sql);
	$id = (int) $db->sql_nextid();
	$data=$id."#".$tag;
	if ($id) {
		response(200,"ok",$data,$path);
	} else {
		response(400,"errore",NULL,$path);
	}
}

/*
* Create a random string
* @author	XEWeb <>
* @param $length the length of the string to create
* @return $str the string
*/
function randomString($length) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}
function response($status,$status_message,$data,$path)
{
	if ($status!=200 and $path) {
		unlink($path);
	}
	
	header("HTTP/1.1 ".$status);
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}
?>