<?php
/**
 *
 * @package       phpBB Extension - S3
 * @copyright (c) 2017 Daniele Tavernelli
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace MT\s3files\migrations;

class release_1_0_2 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\MT\s3files\migrations\release_0_1_0'];
	}

	public function update_data()
	{
		return [
			['config.add', ['s3_is_enabled', 0]],
		];
	}
}
