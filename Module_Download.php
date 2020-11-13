<?php
namespace GDO\Download;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Page;
use GDO\Links\GDO_Link;

/**
 * Download module with automated payment processing.
 * 
 * - Paid downloads
 * - User uploads
 * - Votes and likes
 * 
 * @author gizmore
 * @see Module_Payment
 * @see GDO_Download
 * 
 * @version 6.10
 * @since 3.0
 */
final class Module_Download extends GDO_Module
{
	##############
	### Module ###
	##############
	public $module_priority = 70;
	public function onLoadLanguage() { return $this->loadLanguage('lang/download'); }
	public function getClasses() { return [GDO_Download::class, GDO_DownloadVote::class, GDO_DownloadToken::class]; }
	public function href_administrate_module() { return href('Download', 'Admin'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return [
			GDT_Checkbox::make('dl_upload_guest')->initial('1'),
			GDT_Checkbox::make('dl_download_guest')->initial('1'),
			GDT_Checkbox::make('dl_votes')->initial('1'),
		    GDT_Checkbox::make('dl_vote_guest')->initial('1'),
		    GDT_Checkbox::make('dl_hook_left_bar')->initial('1'),
		    GDT_Int::make('dl_votes_outcome')->unsigned()->initial('1'),
		];
	}
	public function cfgGuestUploads() { return $this->getConfigValue('dl_upload_guest'); }
	public function cfgGuestDownload() { return $this->getConfigValue('dl_download_guest'); }
	public function cfgVotesEnabled() { return $this->getConfigValue('dl_votes'); }
	public function cfgGuestVotes() { return $this->getConfigValue('dl_vote_guest'); }
	public function cfgHookLeftBar() { return $this->getConfigValue('dl_hook_left_bar'); }
	public function cfgVotesOutcome() { return $this->getConfigValue('dl_votes_outcome'); }

	##############
	### Render ###
	##############
	public function renderTabs()
	{
		return $this->responsePHP('tabs.php');
	}
	
	public function onInitSidebar()
	{
// 	    if ($this->cfgHookLeftBar())
	    {
	        $count = GDO_Link::getCounter();
	        $link = GDT_Link::make()->label('link_downloads', [$count])->href(href('Download', 'FileList'));
	        GDT_Page::$INSTANCE->leftNav->addField($link);
	    }
	}

}
