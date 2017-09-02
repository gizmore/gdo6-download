<?php
namespace GDO\Download\Method;

use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\Table\MethodQueryCards;
use GDO\File\GDO_File;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;
/**
 * 
 * @author gizmore
 *
 */
final class FileList extends MethodQueryList
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
	    $userid = GDO_User::current()->getID();
	    return GDO_Download::table()->select('*, gdo_file.*, v.vote_value own_vote')->
	       joinObject('dl_file')->
	       join("LEFT JOIN gdo_downloadvote v ON v.vote_user = $userid AND v.vote_object = dl_id")->
	       where("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
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
