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
 * s3files ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\MT\s3files\acp\main_module',
			'title'		=> 'ACP_s3_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_DEMO',
					'auth'	=> 'ext_MT/s3files && acl_a_board',
					'cat'	=> array('ACP_s3_TITLE')
				),
			),
		);
	}
}
