<?php


namespace MT\s3files\event;

use Aws\S3\S3Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var $phpbb_root_path */
	protected $phpbb_root_path;

	/** @var S3Client */
	protected $s3_client;
	
	/* @var \phpbb\request\request */
	protected $request;
	
	/* @var \phpbb\db\driver\factory */
	protected $db;
	
	/* @var \phpbb\auth\auth */
	protected $auth;
	
	
	

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config     $config   Config object
	 * @param \phpbb\template\template $template Template object
	 * @param \phpbb\user              $user     User object
	 * @param                          $phpbb_root_path
	 *
	 * @access public
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, \phpbb\request\request $request,\phpbb\db\driver\factory $db, \phpbb\auth\auth $auth)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->request = $request;
		$this->db = $db;
		$this->auth = $auth;

		if ($this->config['s3_is_enabled'])
		{
			// Instantiate an AWS S3 client.
			$this->s3_client = new S3Client([
				'credentials' => [
					'key'    => $this->config['s3_aws_access_key_id'],
					'secret' => $this->config['s3_aws_secret_access_key'],
				],
				'debug'       => false,
				'http'        => [
					'verify' => false,
				],
				'region'      => $this->config['s3_region'],
				'version'     => 'latest',
			]);
		}
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup'                               => 'user_setup',
			'core.posting_modify_submit_post_after' => 'gets3Attachments',
			'core.posting_modify_post_data' => 'loads3DataForTable',
            'core.text_formatter_s9e_configure_after' => 'configure_s3_bbcode',
			'core.text_formatter_s9e_parser_setup'    => 'set_bbcode_params',
			'core.posting_modify_submission_errors'  => 'check_posting'
		];
	}
	
	//set db and request params used to set the link of the image
	public function set_bbcode_params($event)
    {
        // We set my.id to a random number in this example
        $event['parser']->set_var('db', $this->db);
		$event['parser']->set_var('request', $this->request);
    }

	//create bb code for s3 files
	public function configure_s3_bbcode($event)
    {
         // Get the BBCode configurator
        $configurator = $event['configurator'];

        // Let's unset any existing BBCode that might already exist
        unset($configurator->BBCodes['image']);
        unset($configurator->tags['image']);
		unset($configurator->BBCodes['file']);
        unset($configurator->tags['file']);
		
		$configurator->BBCodes->addCustom(
            '[image name={URL} href={URL2?} didascalia={TEXT?} key={NUMBER}][/image]',
            '<a href="{URL2}" target="_blank" >
			
				<figure>
					<img src="{URL}" style="max-width:100%;max-height:600px"  >
					<figcaption style="font-style:italic;"> {TEXT} </figcaption>
				</figure>
			
			</a>'
        );
		
		
		$configurator->BBCodes->addCustom(
            '[file name={TEXT} href={URL2?} key={NUMBER}][/file]',
            '<a href="{URL2}">
			
					<figure style="text-align: center;border: 1px dotted blue;">
						<img border="0" src="./ext/MT/s3files/images/download.png" style="max-width:20%;" > 
						<figcaption> {TEXT} </figcaption>
						
						<figcaption style="font-style:italic;"> 54352354 </figcaption>
		
						
					</figure>
				
			 </a>'
			
        );
		

		
		$configurator->tags['image']->filterChain
            ->append(array(__CLASS__, 'sets3FileLink'))->addParameterByName('db')
            ->addParameterByName('request');
			
		$configurator->tags['file']->filterChain
            ->append(array(__CLASS__, 'sets3FileLink'))->addParameterByName('db')
            ->addParameterByName('request');
    }

	//set link to bb code to get img
	 static public function sets3FileLink(\s9e\TextFormatter\Parser\Tag $tag,$db,$request)
    {
		
		$name= urldecode($tag->getAttribute('name'));
		
			
		if(!$name) {
			return false;
		}
		
		
		//prendo i file da tabella
		$filesString=$request->variable('s3filelist','',0);
		
		//prendo i file quotati
		$filesString=$filesString.$request->variable('quoted','',0);
		
		//file_put_contents("debug.txt","\n    ".$name ,FILE_APPEND);

		//se ci sono
		if ($filesString) {
			
			//separo per file
			$rows = explode("*",$filesString);
			
			//per ogni file
			for ($j=1;$j<count($rows);$j++) {
				
				$rowFields = explode("#",$rows[$j]);
				
				$nameRowFile = $rowFields[0]; 
				
				$tagFile = $rowFields[3];
				
				$didascalia = $rowFields[4];
				
				//file_put_contents("debug.txt",$filesString."     ".$didascalia ,FILE_APPEND);
				
				//se il nome nel bb code è uguale al nome del file corrente
				
				
				//file_put_contents("debug.txt",$nameRowFile."     ".$name ,FILE_APPEND);
				if ($nameRowFile==$name) {
					
					//mi prendo l'id e mi trovo il link prendendolo dall servizio
					$id = $rowFields[1];
					
					$service_url = './ext/MT/s3files/download/linkAllegato.php?id='.$id.'&tag='.$tagFile;

					//file_put_contents("debug.txt",$service_url."   WER" ,FILE_APPEND);
					
					//controllo l'estensione, se non è un imagine ha il tag dwld altrimenti ha il tag image
					
					
					if ( $tagFile == "image") {
						
						$tag->setAttribute('name', $service_url);
						$tag->setAttribute('href',$service_url.'&link=1');
						$tag->setAttribute('didascalia',$didascalia);
						//per questo bb code ho finito e ritorno true
						return true;
						
					} else if ( $tagFile == "file" ) {
						
						//$curl = curl_init($service_url);
						//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						//$curl_response = curl_exec($curl);
						//if ($curl_response === false) {
						//	return false;
						//}
						//curl_close($curl);
						
						//$decoded = json_decode($curl_response,true);
						//file_put_contents("debug.txt","   fdsafsa" ,FILE_APPEND);
						$tag->setAttribute('name', $name);
						$tag->setAttribute('href',$service_url);
						return true;
					}
					
						
					
				}
				
				
			}
		}  
		
		//se sono qui è perchè c'è stato qualche errore o perchè è stato quotato un file e non ne sto tenendo traccia in quoted, in questo caso provo a prendere direttamente dall'id
		$id = $tag->getAttribute('key');
		$tagFile= $tag -> getName();
		if (!$id or !$tagFile) {
			return;
		}
		
		$service_url = './ext/MT/s3files/download/linkAllegato.php?id='.$id.'&tag='.$tagFile;
		if ( $tagFile == "IMAGE") {
						
			$tag->setAttribute('name', $service_url);
			$tag->setAttribute('href',$service_url.'&link=1');
			
			return true;
						
		} else if ( $tagFile == "FILE" ) {
						
				$tag->setAttribute('name', $name);
				$tag->setAttribute('href',$service_url);
				return true;
		}
		
		return false;
		
    }
	
	
	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'MT/s3files',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Event to modify uploaded file before submit to the post
	 *
	 * @param $event
	 */
	public function modify_uploaded_file($event)
	{
		if ($this->config['s3_is_enabled'])
		{
			$filedata = $event['filedata'];

			// Fullsize
			$key = $filedata['physical_filename'];
			$body = file_get_contents($this->phpbb_root_path . $this->config['upload_path'] . '/' . $key);
			$this->uploadFileToS3($key, $body, $filedata['mimetype']);
		}
	}

	/**
	 * Perform additional actions after attachment(s) deletion from the filesystem
	 *
	 * @param $event
	 */
	public function delete_attachments_from_filesystem_after($event)
	{
		if ($this->config['s3_is_enabled'])
		{
			foreach ($event['physical'] as $physical_file)
			{
				$this->s3_client->deleteObject([
					'Bucket' => $this->config['s3_bucket'],
					'Key'    => $physical_file['filename'],
				]);
			}
		}
	}

	/**
	 * Use this event to modify the attachment template data.
	 *
	 * This event is triggered once per attachment.
	 *
	 * @param $event
	 */
	public function parse_attachments_modify_template_data($event)
	{
		if ($this->config['s3_is_enabled'])
		{
			$block_array = $event['block_array'];
			$attachment = $event['attachment'];

			$key = 'thumb_' . $attachment['physical_filename'];
			$s3_link_thumb = '//' . $this->config['s3_bucket'] . '.s3.amazonaws.com/' . $key;
			$s3_link_fullsize = '//' . $this->config['s3_bucket'] . '.s3.amazonaws.com/' . $attachment['physical_filename'];
			$local_thumbnail = $this->phpbb_root_path . $this->config['upload_path'] . '/' . $key;

			if ($this->config['img_create_thumbnail'])
			{

				// Existence on local filesystem check. Just in case "Create thumbnail" was turned off at some point in the past and thumbnails weren't generated.
				if (file_exists($local_thumbnail))
				{

					// Existence on S3 check. Since this method runs on every page load, we don't want to upload the thumbnail multiple times.
					if (!$this->s3_client->doesObjectExist($this->config['s3_bucket'], $key))
					{

						// Upload *only* the thumbnail to S3.
						$body = file_get_contents($local_thumbnail);
						$this->uploadFileToS3($key, $body, $attachment['mimetype']);
					}
				}
				$block_array['THUMB_IMAGE'] = $s3_link_thumb;
				$block_array['U_DOWNLOAD_LINK'] = $s3_link_fullsize;
				
			}

			$block_array['U_INLINE_LINK'] = $s3_link_fullsize;
			$event['block_array'] = $block_array;
		}
		
	}

	/**
	 * Upload the attachment to the AWS S3 bucket.
	 *
	 * @param $key
	 * @param $body
	 * @param $content_type
	 */
	private function uploadFileToS3($key, $body, $content_type)
	{
		return $this->s3_client->upload($this->config['s3_bucket'], "$key", $body, 'public-read', ['params' => ['ContentType' => $content_type]]);
		
		
	}
	
	
	
	//find the files of this post and update the database, if necessary delete or save files in s3
	public function gets3Attachments($event) {
		
		//file_put_contents("debug.txt",$this->config['s3_is_enabled'],FILE_APPEND);
		
		if ($this->config['s3_is_enabled'])
		{
			$local_cache_path = 'ext/MT/s3files/cached/';

			$data = $event['data'];
		
			$newFilesToSave = array();
			
			$oldFilesToDelete = array();
			
			$filesInPostRows = array();
			
			$filesInPostIds = array();
			
			$filesInPostDidascalie = array();
			
			$oldFilesInPost = array();
			
			$oldFilesInPostHash = array();
			
			$stringFiles   = $this->request->variable('s3filelist','',0);
			
			
			//file_put_contents("debug.txt","    ".implode(" ",array_keys($filesInPostIdst)),FILE_APPEND);
			//costruisco i file che sono attualmente nel post
			if ($stringFiles) {
				
				//remove first *
				$stringFiles = substr($stringFiles,1);
				
				$filesInPostRows = explode("*",$stringFiles);

			}
			

			//costruisco l'array degli id e delle didascalie
			for ($j=0;$j<count($filesInPostRows);$j++ ) {
				$filesInPostRow=$filesInPostRows[$j];
				$filesInPostRowSplit= explode("#",$filesInPostRow);
				$fileInPostId=$filesInPostRowSplit[1];
				$didascalia=$filesInPostRowSplit[4];
				array_push($filesInPostIds,$fileInPostId);
				$filesInPostDidascalie[$fileInPostId]=$didascalia;
			}
			

			$sql = 'SELECT *
					FROM phpbb_s3
					WHERE post_id ='.$data['post_id'];
						
			$result = $this->db->sql_query($sql);
			
			
			//creo arrey dei file che erano sul db per questo post, come chiave c'è il nome del file, come valore l'id dell'allegato
			while ($row = $this->db->sql_fetchrow($result)){
				$oldFilesInPost[$row['id_allegato']]=$row['nome'];
				$oldFilesInPostHash[$row['id_allegato']]=$row['hash'];
			}
			$this->db->sql_freeresult($result);

			//save in S3 and database new files not in DB
			for ($j=0;$j<count($filesInPostIds);$j++ ) {
				$allegatoId=$filesInPostIds[$j];
				//di quelli nuovi se già il file non c'era lo salvo
				if (!in_array($allegatoId,array_keys($oldFilesInPost))) {
					
					$sql = 'SELECT *
					FROM phpbb_s3
					WHERE id_allegato='.$allegatoId ;
					$result=$this->db->sql_query($sql);
				
					while ($row = $this->db->sql_fetchrow($result)){
						$this->db->sql_freeresult($result);
						$hash= $row['hash'];
						$key = $row['hash'];
						$path = $this->phpbb_root_path.$local_cache_path.$hash;
						$body = file_get_contents($path);
						$result= $this->uploadFileToS3($key, $body,mime_content_type ( $path ));
						$sql = 'UPDATE phpbb_s3 SET link=\''.$result['ObjectURL'].'\', flag_orfano=0 ,post_id='.$data['post_id'].' ,didascalia=\''.$filesInPostDidascalie[$allegatoId].'\' where id_allegato='.$allegatoId ;
						$this->db->sql_query($sql);
						//remove from cache
						unlink($path);

						break;
					}
				} else {//altrimenti comunque aggiorno la didascalia
					$sql = 'UPDATE phpbb_s3 SET didascalia=\''.$filesInPostDidascalie[$allegatoId].'\' where id_allegato='.$allegatoId ;
					$this->db->sql_query($sql);
				}
			}
			
			
			
			
			//delete in s3 and database old files not in  new post
			for ($j=0;$j<count($oldFilesInPost);$j++ ) {
				
				if (!in_array(array_keys($oldFilesInPost)[$j],$filesInPostIds)) {
					
					$sql = 'delete from phpbb_s3 where id_allegato='.array_keys($oldFilesInPost)[$j];
					$this->db->sql_query($sql);
					//delete post in s3
					$this->s3_client->deleteObject([
						'Bucket' => $this->config['s3_bucket'],
						'Key'    => $oldFilesInPostHash[array_keys($oldFilesInPost)[$j]],
					]);
				}
			}
			
			

		}
		
	}
	
	//load s3 data for table
	public function loads3DataForTable($event) {
		
		//$post_dataa=$event['post_data'];
		/*  foreach (array_keys($post_dataa) as $item) {
			file_put_contents("debug.txt","\n ".$item."#:    ".$post_dataa[$item],FILE_APPEND);
		}   */
		//file_put_contents("debug.txt","            ".implode("###" ,$event['post_data']),FILE_APPEND);
		
		$isUserAdminOrdModerator= $this->auth->acl_getf_global('m_') || $this->auth->acl_getf_global('a_');

		$this->template->assign_var('s3_is_visible', $this->config['s3_is_enabled'] );
		
		
		
			
		//aggiungo quelli che avevo aggiunto prima nel caso avessi già degli elementi
		$listQuoted=$this->request->variable('quoted','',0);
		
		
		//aggiungo quelli che avevo aggiunto prima nel caso avessi già degli elementi
		$list=$this->request->variable('s3filelist','',0);
		
		
		//se ho degli elementi
		if ( ($list and strlen($list)>0) or ($listQuoted and strlen($listQuoted)>0) ) {
			if ($list) {
				$this->template->assign_var('S_SOME_VARIABLE', $list);
			}
			if ($listQuoted) {
				$this->template->assign_var('S_QUOTED_FILES', $listQuoted);
			}
			return;
		}
		
		//altrimenti potrei entrare per la prima volta nel post e devo vedere se ci sono già salvati ne db
		
		$olds3RowsInPost = "";
		
		$sql = 'SELECT *
					FROM phpbb_s3
					WHERE post_id ='.$event['post_id'].' AND flag_orfano=0';
						
		$result = $this->db->sql_query($sql);
	
		//creo arrei dei file che erano sul db per questo post, come chiave c'è il nome del file, come valore l'id dell'allegato
		while ($row = $this->db->sql_fetchrow($result)){
			if (!$row['didascalia']) {
				$row['didascalia']="";
			}
			$olds3RowsInPost=$olds3RowsInPost."*".$row['nome']."#".$row['id_allegato']."#".$row['size']."#".$row['tag']."#".$row['didascalia'];
		}
		
		$this->db->sql_freeresult($result);
		
		/* file_put_contents("debug.txt","    ".$event['mode'],FILE_APPEND);
		file_put_contents("debug.txt","    ".$event['post_id'],FILE_APPEND);
		file_put_contents("debug.txt","    ".$event['$olds3RowsInPost'],FILE_APPEND); */


		//se sono in modalità quote non devo aggiunggere nulla alla tabella ma comunque mi salvo i files quotati
		if ($event['mode']=='quote') {
			$this->template->assign_var('S_QUOTED_FILES', $olds3RowsInPost);
			$this->template->assign_var('S_SOME_VARIABLE', "");
			return;
		} 
		
		//altrimenti li butto nella tabella		
		$this->template->assign_var('S_SOME_VARIABLE', $olds3RowsInPost);
		

		
	}
	
	public function check_posting($event) {
	
			$filesInPostRows = array();
			
			$stringFiles   = $this->request->variable('s3filelist','',0);
			
			//file_put_contents("debug.txt",$stringFiles ,FILE_APPEND);
			//file_put_contents("debug.txt","    ".implode(" ",array_keys($filesInPostIdst)),FILE_APPEND);
			//costruisco i file che sono attualmente nel post
			if ($stringFiles) {
				
				//remove first *
				$stringFiles = substr($stringFiles,1);
				
				$filesInPostRows = explode("*",$stringFiles);

			}
			
			//massimo numero di foto qui!!
			$MAX_NUMBER_OF_PHOTO = 10;
			if (count($filesInPostRows)>$MAX_NUMBER_OF_PHOTO) {
				
				$error= $event['error'];
				array_push($error,'Errore: non si possono inserire più di 10 file, per proseguire eliminare i file in eccesso dalla interfaccia di caricamento.');
				$event['error']=$error;
				
			}
		
	
	}
	
	

	

}
