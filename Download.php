<?php
namespace GDO\Download;

use GDO\Category\GDT_Category;
use GDO\DB\Cache;
use GDO\DB\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\DB\GDT_CreatedAt;
use GDO\DB\GDT_CreatedBy;
use GDO\DB\GDT_DeletedAt;
use GDO\DB\GDT_DeletedBy;
use GDO\DB\GDT_EditedAt;
use GDO\DB\GDT_EditedBy;
use GDO\Date\GDT_DateTime;
use GDO\File\File;
use GDO\File\GDT_File;
use GDO\Payment\GDT_Money;
use GDO\Template\GDT_Template;
use GDO\Type\GDT_Int;
use GDO\Type\GDT_Message;
use GDO\Type\GDT_String;
use GDO\User\GDT_Level;
use GDO\User\GDT_User;
use GDO\User\User;
use GDO\Vote\GDT_VoteCount;
use GDO\Vote\GDT_VoteRating;
use GDO\Vote\WithVotes;
/**
 * A download is votable, likeable, purchasable.
 * 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 */
final class Download extends GDO
{
	#############
	### Votes ###
	#############
	use WithVotes;
	public function gdoVoteTable() { return DownloadVote::table(); }
	public function gdoVoteMin() { return 1; }
	public function gdoVoteMax() { return 5; }
	public function gdoVotesBeforeOutcome() { return Module_Download::instance()->cfgVotesOutcome(); }
	public function gdoVoteAllowed(User $user) { return $user->getLevel() >= $this->getLevel(); }
	
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
			GDT_AutoInc::make('dl_id'),
			GDT_String::make('dl_title')->notNull()->label('title'),
			GDT_Message::make('dl_info')->notNull()->label('info'),
			GDT_Category::make('dl_category'),
			GDT_File::make('dl_file')->notNull(),
			GDT_Int::make('dl_downloads')->unsigned()->notNull()->initial('0')->editable(false)->label('downloads'),
			GDT_Money::make('dl_price'),
			GDT_Level::make('dl_level')->notNull()->initial('0'),
			GDT_VoteCount::make('dl_votes'),
			GDT_VoteRating::make('dl_rating'),
			GDT_CreatedAt::make('dl_created'),
			GDT_CreatedBy::make('dl_creator'),
			GDT_DateTime::make('dl_accepted')->editable(false)->label('accepted_at'),
			GDT_User::make('dl_acceptor')->editable(false)->label('accepted_by'),
			GDT_EditedAt::make('dl_edited'),
			GDT_EditedBy::make('dl_editor'),
			GDT_DeletedAt::make('dl_deleted'),
			GDT_DeletedBy::make('dl_deletor'),
		);
	}
	
	##############
	### Bridge ###
	##############
	
	public function href_edit() { return href('Download', 'Crud', '&id='.$this->getID()); }
	public function href_view() { return href('Download', 'View', '&id='.$this->getID()); }

	public function gdoHashcode() { return self::gdoHashcodeS($this->getVars(['dl_id', 'dl_title', 'dl_category', 'dl_file', 'dl_created', 'dl_creator'])); }

	public function canView(User $user) { return ($this->isAccepted() && (!$this->isDeleted())) || $user->isStaff(); }
	public function canDownload(User $user)
	{
		if ($this->isPaid())
		{
			if (!DownloadToken::hasToken($user, $this))
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		if ($user->getLevel() < $this->getLevel())
		{
			return false;
		}
		if ($this->isDeleted() || (!$this->isAccepted()) )
		{
			return false;
		}
		return true;
	}
	
	public function canPurchase()
	{
		return $this->isPaid();
	}
	
	##############
	### Getter ###
	##############
	
	/**
	 * @return File
	 */
	public function getFile() { return $this->getValue('dl_file'); }
	public function getFileID() { return $this->getVar('dl_file'); }
	
	/**
	 * @return User
	 */
	public function getCreator() { return $this->getValue('dl_creator'); }
	public function getCreatorID() { return $this->getVar('dl_creator'); }
	public function getCreateDate() { return $this->getVar('dl_created'); }
	/**
	 * @return GDT_Message
	 */
	public function gdoMessage() { return $this->gdoColumn('dl_info'); }
	
	public function getDownloads() { return $this->getVar('dl_downloads'); }
	public function getRating() { return $this->getVar('dl_rating'); }
	public function getVotes() { return $this->getVar('dl_votes'); }
	
	public function getLevel() { return $this->getVar('dl_level'); }
	public function getPrice() { return $this->getVar('dl_price'); }
	public function displayPrice() { return "€".$this->getVar('dl_price'); }
	public function getType() { return $this->getFile()->getType(); }
	public function getTitle() { return $this->getVar('dl_title'); }
	public function displayInfo() { return $this->gdoMessage()->renderCell(); }
	public function displaySize() { return $this->getFile()->displaySize(); }
	
	public function isAccepted() { return $this->getVar('dl_accepted') !== null; }
	public function isDeleted() { return $this->getVar('dl_deleted') !== null; }
	public function isPaid() { return $this->getPrice() !== null; }
	##############
	### Render ###
	##############
	public function renderCard()
	{
	    return GDT_Template::responsePHP('Download', 'card/download.php', ['gdo' => $this]);
	}

	##############
	### Static ###
	##############
	public static function countDownloads()
	{
		if (false === ($cached = Cache::get('gwf_download_count')))
		{
			$cached = self::table()->countWhere("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
			Cache::set('gwf_download_count', $cached);
		}
		return $cached;
	}
	
	public function gdoAfterCreate()
	{
		Cache::unset('gwf_download_count');
	}
}
