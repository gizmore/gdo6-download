<?php
namespace GDO\Download\Method;

use GDO\Core\GDT_Hook;
use GDO\Core\Method;
use GDO\Download\GDO_Download;
use GDO\User\GDO_User;
use GDO\Util\Common;
use GDO\File\Method\GetFile;

final class File extends Method
{
	public function execute()
	{
		$user = GDO_User::current();
		$id = Common::getGetString('id', 'id');
		$download = GDO_Download::table()->findById($id);
		if (!$download->canDownload($user))
		{
			GDO_Download::notFoundException(html($id));
		}
		
		$download->increase('dl_downloads');
		
		GDT_Hook::callHook('DownloadFile', $user, $download);
		
		return GetFile::make()->executeWithId($download->getFileID());
	}
}
