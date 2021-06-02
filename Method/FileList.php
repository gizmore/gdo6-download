<?php
namespace GDO\Download\Method;

use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\File\GDO_File;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;
use GDO\Table\GDT_Table;

/**
 * Download overview.
 * @author gizmore
 * @version 6.10.4
 * @since 3.0.0
 */
final class FileList extends MethodQueryList
{
    public function getTitle()
    {
        $count = GDO_Download::countDownloads();
        return t('link_downloads', [$count]);
    }
    
    protected function setupTitle(GDT_Table $table)
    {
        $count = GDO_Download::countDownloads();
        $table->title('link_downloads', [$count]);
    }
    
	public function isGuestAllowed()
	{
		return Module_Download::instance()->cfgGuestDownload();
	}
	
	public function beforeExecute()
	{
	    Module_Download::instance()->renderTabs();
	}
	
	public function gdoTable()
	{
		return GDO_Download::table();
	}
	
	public function getQuery()
	{
		$userid = GDO_User::current()->getID();
		return GDO_Download::table()->select('gdo_download.*, gdo_file.*, v.vote_value own_vote')->
		   joinObject('dl_file')->
		   join("LEFT JOIN gdo_downloadvote v ON v.vote_user = $userid AND v.vote_object = dl_id")->
		   where("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
	}
	
	public function gdoHeaders()
	{
		$gdo = GDO_Download::table();
		$file = GDO_File::table();
		return array(
// 			GDT_EditButton::make(),
// 			$gdo->gdoColumn('dl_id'),
			$gdo->gdoColumn('dl_title'),
			$file->gdoColumn('file_size'),
			$gdo->gdoColumn('dl_downloads'),
			$gdo->gdoColumn('dl_price'),
			$gdo->gdoColumn('dl_votes'),
			$gdo->gdoColumn('dl_rating'),
// 			GDT_Button::make('view'),
		);
	}
}
