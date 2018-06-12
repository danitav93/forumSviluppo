<?php
 

 
define('IN_PHPBB', true); 
if (!defined('PHPBB_ENVIRONMENT'))
{
	@define('PHPBB_ENVIRONMENT', 'production');
}


$id =$_POST['id'];



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


$sql = 'SELECT *
					FROM phpbb_s3
					WHERE id_allegato='.$id.' AND flag_orfano=1';
$result=$db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)){
	$hash=$row['hash'];
	$db->sql_freeresult($result);
	unlink('../'.$hash);
	$sql = 'delete 	FROM phpbb_s3
					WHERE id_allegato='.$id ;
					
	$db->sql_query($sql);
	break;
}



?>