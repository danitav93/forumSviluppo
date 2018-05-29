<?php
/**
 *
 * s3files. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Daniele Tavernelli
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace MT\s3files\acp;

/**
 * s3files ACP module.
 */
class main_module
{

	var $u_action;

	function main($id, $mode)
	{
		global $config, $request, $template, $user, $db, $phpbb_root_path;

		
			/* if ($result and $db->sql_fetchrow($result)) {
			} else {
					
						//se sono in eccezione vuol dire che la tabella non esiste quindi la creo
						$sql = "CREATE TABLE `phpbb_s3` (
								`id_allegato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
								`nome` text NOT NULL,
								`link` text NOT NULL,
								`hash` text NOT NULL,
								`post_id` int(10) UNSIGNED DEFAULT NULL,
								`size` float NOT NULL,
								`flag_orfano` int(1) NOT NULL DEFAULT '1',
								`data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								`tag` text NOT NULL,
								`didascalia` text 
								) ";
						$db->sql_query($sql);
					
			} */
		
			$user->add_lang('acp/common');
			$this->tpl_name = 's3_body';
			$this->page_title = $user->lang('ACP_S3_TITLE');
			add_form_key('MT/s3files');
			
			//carico variabili dal db
			//file totali
			$sql= "SELECT COUNT(*) FROM phpbb_s3 where flag_orfano=0";
			$result=$db->sql_query($sql);
			$config['file_totali']=$db->sql_fetchrow($result)['COUNT(*)'];
			$db->sql_freeresult($result); 
			
			//peso totale
			$sql= "SELECT SUM(size) FROM phpbb_s3 where flag_orfano=0";
			$result=$db->sql_query($sql);
			$config['peso_totale']=strval(round($db->sql_fetchrow($result)['SUM(size)']/1000000,6))." GB";
			$db->sql_freeresult($result); 
			
			//file orfani
			$sql= "SELECT COUNT(*) FROM phpbb_s3 where flag_orfano=1";
			$result=$db->sql_query($sql);
			$config['orfani_totali']=$db->sql_fetchrow($result)['COUNT(*)'];
			$db->sql_freeresult($result); 
			
			//peso orfani
			$sql= "SELECT SUM(size) FROM phpbb_s3 where flag_orfano=1";
			$result=$db->sql_query($sql);
			$config['peso_orfani']=strval(round($db->sql_fetchrow($result)['SUM(size)']/1000000,6))." GB";
			$db->sql_freeresult($result); 
		
		

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('MT/s3files'))
			{
				trigger_error('FORM_INVALID');
			}

			$errors = [];
			if (!preg_match('/[A-Z0-9]{20}/', $request->variable('s3_aws_access_key_id', '')))
			{
				$errors[] = $user->lang('ACP_S3_AWS_ACCESS_KEY_ID_INVALID', $request->variable('s3_aws_access_key_id', ''));
			}

			if (!preg_match('/[A-Za-z0-9\/+=]{40}/', $request->variable('s3_aws_secret_access_key', '')))
			{
				$errors[] = $user->lang('ACP_S3_AWS_SECRET_ACCESS_KEY_INVALID', $request->variable('s3_aws_secret_access_key', ''));
			}

			if (empty($request->variable('s3_region', '')))
			{
				$errors[] = $user->lang('ACP_S3_REGION_INVALID');
			}

			if (empty($request->variable('s3_bucket', '')))
			{
				$errors[] = $user->lang('ACP_S3_BUCKET_INVALID');
			}

			// If we have no errors so far, let's ensure our AWS credentials are actually working.
			if (!count($errors))
			{
				try
				{
					// Instantiate an AWS S3 client.
					$s3_client = new \Aws\S3\S3Client([
						'credentials' => [
							'key'    => $request->variable('s3_aws_access_key_id', ''),
							'secret' => $request->variable('s3_aws_secret_access_key', ''),
						],
						'http'        => [
							'verify' => false,
						],
						'region'      => $request->variable('s3_region', ''),
						'version'     => 'latest',
					]);

					// Upload a test file to ensure credentials are valid and everything is working properly.
					$s3_client->upload($request->variable('s3_bucket', ''), 'test.txt', 'test body');

					// Delete the test file.
					$s3_client->deleteObject([
						'Bucket' => $request->variable('s3_bucket', ''),
						'Key'    => 'test.txt',
					]);
					
					//check if s3 table exist and in case create 
					$sql = "SHOW TABLES LIKE 'phpBB_s3' ";
					
					$result = $db->sql_query($sql);
					
					
				}
				catch (\Aws\S3\Exception\S3Exception $e)
				{
					$errors[] = $e->getMessage();
				}
			}

			// If we still don't have any errors, it is time to set the database config values.
			if (!count($errors))
			{
				$config->set('s3_aws_access_key_id', $request->variable('s3_aws_access_key_id', ''));
				$config->set('s3_aws_secret_access_key', $request->variable('s3_aws_secret_access_key', ''));
				$config->set('s3_region', $request->variable('s3_region', ''));
				$config->set('s3_bucket', $request->variable('s3_bucket', ''));
				$config->set('s3_is_enabled', 1);
				
				trigger_error($user->lang('ACP_S3_SETTING_SAVED') . adm_back_link($this->u_action));
			}
			
		//servizio di rimozioni allegati orfani
		} else if ($request->is_set_post('elimina_orfani')) {
			$errors = [];
			try
				{
					//cancello gli orfani in cache
					$local_cache_path = 'ext/MT/s3files/cached/';
					
					$sql = 'SELECT *
					FROM phpbb_s3
					WHERE  flag_orfano=1';
						
					$result = $db->sql_query($sql);
	
					//creo arrei dei file che erano sul db per questo post, come chiave c'è il nome del file, come valore l'id dell'allegato
					while ($row = $db->sql_fetchrow($result)){
						$path = $phpbb_root_path.$local_cache_path.$row['hash'];
						unlink($path);
					}
					$db->sql_freeresult($result);
					
					//cancello gli orfani nel db
					$sql= "DELETE FROM phpbb_s3 WHERE flag_orfano=1 ";
					$db->sql_query($sql);
					
				} catch (Exception $e)
				{
					$errors[] = $e->getMessage();
				}
			if (!count($errors)) {
				trigger_error("File orfani rimossi con successo" . adm_back_link($this->u_action));
			} else {
				trigger_error("Non sono riuscito a rimuovere i file" . adm_back_link($this->u_action));
			}
			
		}
		$template->assign_vars([
			'U_ACTION'                 => $this->u_action,
			'S3_ERROR'                 => isset($errors) ? ((count($errors)) ? implode('<br /><br />', $errors) : '') : '',
			'S3_AWS_ACCESS_KEY_ID'     => $config['s3_aws_access_key_id'],
			'S3_AWS_SECRET_ACCESS_KEY' => $config['s3_aws_secret_access_key'],
			'S3_REGION'                => $config['s3_region'],
			'S3_BUCKET'                => $config['s3_bucket'],
			'S3_IS_ENABLED'            => ($config['s3_is_enabled']) ? 'Enabled' : 'Disabled',
			'file_totali'			   => $config['file_totali'],
			'peso_totale'			   => $config['peso_totale'],
			'orfani_totali'   		   => $config['orfani_totali'],
			'peso_orfani'			   => $config['peso_orfani'],
		]);
	}
}
