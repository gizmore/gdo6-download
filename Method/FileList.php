<?php
namespace GDO\Download\Method;

use GDO\Download\Download;
use GDO\Download\Module_Download;
use GDO\Table\MethodQueryCards;
use GDO\File\File;
/**
 * 
 * @author gizmore
 *
 */
final class FileList extends MethodQueryCards
{
	public function isGuestAllowed()
	{
		return Module_Download::instance()->cfgGuestDownload();
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Download::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	public function gdoTable()
	{
		return Download::table();
	}
	
	public function gdoQuery()
	{
		return Download::table()->select('*, gwf_file.*')->joinObject('dl_file')->where("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
	}
	
	public function gdoFilters()
	{
		$gdo = Download::table();
		$file = File::table();
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
