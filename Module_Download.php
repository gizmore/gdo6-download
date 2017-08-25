<?php
namespace GDO\Download;

use GDO\Core\Module;
use GDO\Template\GDO_Bar;
use GDO\Type\GDO_Checkbox;
use GDO\Type\GDO_Int;
/**
 * Download module with automated payment processing.
 * 
 * @author gizmore
 * @see Module_Payment
 * @see Download
 * 
 * @since 3.0
 * @version 5.0
 */
final class Module_Download extends Module
{
	##############
	### Module ###
	##############
	public $module_priority = 70;
	public function onLoadLanguage() { return $this->loadLanguage('lang/download'); }
	public function getClasses() { return ['GDO\Download\Download', 'GDO\Download\DownloadVote', 'GDO\Download\DownloadToken']; }
	public function href_administrate_module() { return href('Download', 'Admin'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('dl_upload_guest')->initial('1'),
			GDO_Checkbox::make('dl_download_guest')->initial('1'),
			GDO_Checkbox::make('dl_votes')->initial('1'),
			GDO_Checkbox::make('dl_vote_guest')->initial('1'),
			GDO_Int::make('dl_votes_outcome')->unsigned()->initial('3'),
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

	public function hookLeftBar(GDO_Bar $navbar)
	{
		$this->templatePHP('leftbar.php', ['navbar' => $navbar]);
	}
}
