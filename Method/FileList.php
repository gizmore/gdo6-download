<?php
namespace GDO\Download\Method;

use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\Table\MethodQueryCards;
use GDO\File\GDO_File;
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
	    return GDO_Download::table();
	}
	
	public function gdoQuery()
	{
	    return GDO_Download::table()->select('*, gdo_file.*')->joinObject('dl_file')->where("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
	}
	
	public function gdoFilters()
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
