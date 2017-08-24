<?php
namespace GDO\Download\Method;

use GDO\Core\GDO_Hook;
use GDO\Core\Method;
use GDO\Download\Download;
use GDO\User\User;
use GDO\Util\Common;

final class File extends Method
{
	public function execute()
	{
		$user = User::current();
		$id = Common::getGetString('id', 'id');
		$download = Download::table()->findById($id);
		if (!$download->canDownload($user))
		{
			Download::notFoundException(html($id));
		}
		
		$download->increase('dl_downloads');
		
		GDO_Hook::call('DownloadFile', $user, $download);
		
		return method('GWF', 'GetFile')->executeWithId($download->getFileID());
	}
}
