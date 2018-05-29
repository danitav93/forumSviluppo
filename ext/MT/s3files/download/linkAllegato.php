<?php
			

define('IN_PHPBB', true); 
if (!defined('PHPBB_ENVIRONMENT'))
{
	@define('PHPBB_ENVIRONMENT', 'production');
}

$id = $_GET['id'];
$tag = $_GET['tag'];

if(isset($_GET['link'])) {
	
	$need_link = $_GET['link'];

} else {
	
	$need_link=0;
	
}

if (!$id) {
	//response(400,"Invalid Request",NULL);
	echo(" ");
} else {
	
	$phpbb_root_path = '../../../../';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	require($phpbb_root_path . 'includes/startup.' . $phpEx);
	require($phpbb_root_path . 'phpbb/class_loader.' . $phpEx);

	$phpbb_class_loader = new \phpbb\class_loader('phpbb\\', "{$phpbb_root_path}phpbb/", $phpEx);
	$phpbb_class_loader->register();
	$phpbb_config_php_file = new \phpbb\config_php_file($phpbb_root_path, $phpEx);
	// Set up container
	$phpbb_container_builder = new \
	phpbb\di\container_builder($phpbb_root_path, $phpEx);
	$phpbb_container = $phpbb_container_builder->with_config($phpbb_config_php_file)->get_container();
	/* @var $db \phpbb\db\driver\driver_interface */
	$db= $phpbb_container->get('dbal.conn');
	$sql = 'SELECT *
					FROM phpbb_s3
					WHERE id_allegato='.$id ;
	$result=$db->sql_query($sql);
	
	while ($row = $db->sql_fetchrow($result)){
		
		$link=$row['link'];
		$size= floatval($row['size'])*1000;
		$tag=$row['tag'];
		$name=$row['nome'];
		$db->sql_freeresult($result);
		//file_put_contents("debug.txt",$link."   ".$size."     ".$tag."   ".$name,FILE_APPEND);
		/* $handle = fopen($link, 'rb');
		while (!feof($handle)) {
			$line = fgets($handle);
			echo($line);
		}
		fclose($handle); */
		if ($need_link==1) {
			
			echo '<!DOCTYPE html><html style="height:100%;overflow-y: hidden;"><body style="height:100%;overflow-y: hidden;"><img style=" max-height:98%; max-width:80%; " src="'. $link.'" ></body></html>';
			
		} else if ($tag=='image') {
			
			echo file_get_contents($link);
		
		} else if ($tag=='file'){
		
				header('Content-Type: application/octet-stream');
				header("Content-Transfer-Encoding: Binary"); 
				header("Content-disposition: attachment; filename=\"" . basename($name) . "\""); 
				readfile($link);
		}
		break;
	}
} 




function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}
 
 
?>
