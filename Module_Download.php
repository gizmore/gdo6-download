<?php
namespace GDO\Download;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Bar;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Int;
/**
 * Download module with automated payment processing.
 * 
 * @author gizmore
 * @see Module_Payment
 * @see GDO_Download
 * 
 * @since 3.0
 * @version 5.0
 */
final class Module_Download extends GDO_Module
{
	##############
	### Module ###
	##############
	public $module_priority = 70;
	public function onLoadLanguage() { return $this->loadLanguage('lang/download'); }
	public function getClasses() { return ['GDO\Download\GDO_Download', 'GDO\Download\GDO_DownloadVote', 'GDO\Download\GDO_DownloadToken']; }
	public function href_administrate_module() { return href('Download', 'Admin'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('dl_upload_guest')->initial('1'),
			GDT_Checkbox::make('dl_download_guest')->initial('1'),
			GDT_Checkbox::make('dl_votes')->initial('1'),
			GDT_Checkbox::make('dl_vote_guest')->initial('1'),
			GDT_Int::make('dl_votes_outcome')->unsigned()->initial('1'),
		);
	}
	public function cfgGuestUploads() { return $this->getConfigValue('dl_upload_guest'); }
	public function cfgGuestDownload() { return $this->getConfigValue('dl_download_guest'); }
	public function cfgVotesEnabled() { return $this->getConfigValue('dl_votes'); }
	public function cfgGuestVotes() { return $this->getConfigValue('dl_vote_guest'); }
	public function cfgVotesOutcome() { return $this->getConfigValue('dl_votes_outcome'); }

	##############
	### Render ###
	##############
	public function renderTabs()
	{
		return $this->templatePHP('tabs.php');
	}

	public function hookLeftBar(GDT_Bar $navbar)
	{
		$this->templatePHP('leftbar.php', ['navbar' => $navbar]);
	}
}
