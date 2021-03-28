<?php
namespace GDO\Download\Method;

use GDO\Core\GDT_Hook;
use GDO\Core\Method;
use GDO\Download\GDO_Download;
use GDO\User\GDO_User;
use GDO\File\Method\GetFile;
use GDO\DB\GDT_Object;

final class File extends Method
{
    public function gdoParameters()
    {
        return [
            GDT_Object::make('id')->table(GDO_Download::table())->notNull(),
        ];
    }
    
	public function execute()
	{
		$user = GDO_User::current();
		$download = $this->gdoParameterValue('id');
		if (!$download->canDownload($user))
		{
			GDO_Download::notFoundException($download->getID());
		}
		
		$download->increase('dl_downloads');
		
		GDT_Hook::callHook('DownloadFile', $user, $download);
		
		return GetFile::make()->executeWithId($download->getFileID());
	}

}
