<?php
namespace GDO\Download;

use GDO\Category\GDO_Category;
use GDO\DB\Cache;
use GDO\DB\GDO;
use GDO\DB\GDO_AutoInc;
use GDO\DB\GDO_CreatedAt;
use GDO\DB\GDO_CreatedBy;
use GDO\DB\GDO_DeletedAt;
use GDO\DB\GDO_DeletedBy;
use GDO\DB\GDO_EditedAt;
use GDO\DB\GDO_EditedBy;
use GDO\Date\GDO_DateTime;
use GDO\File\File;
use GDO\File\GDO_File;
use GDO\Payment\GDO_Money;
use GDO\Template\GDO_Template;
use GDO\Type\GDO_Int;
use GDO\Type\GDO_Message;
use GDO\Type\GDO_String;
use GDO\User\GDO_Level;
use GDO\User\GDO_User;
use GDO\User\User;
use GDO\Vote\GDO_VoteCount;
use GDO\Vote\GDO_VoteRating;
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
			GDO_AutoInc::make('dl_id'),
			GDO_String::make('dl_title')->notNull()->label('title'),
			GDO_Message::make('dl_info')->notNull()->label('info'),
			GDO_Category::make('dl_category'),
			GDO_File::make('dl_file')->notNull(),
			GDO_Int::make('dl_downloads')->unsigned()->notNull()->initial('0')->editable(false)->label('downloads'),
			GDO_Money::make('dl_price'),
			GDO_Level::make('dl_level')->notNull()->initial('0'),
			GDO_VoteCount::make('dl_votes'),
			GDO_VoteRating::make('dl_rating'),
			GDO_CreatedAt::make('dl_created'),
			GDO_CreatedBy::make('dl_creator'),
			GDO_DateTime::make('dl_accepted')->editable(false)->label('accepted_at'),
			GDO_User::make('dl_acceptor')->editable(false)->label('accepted_by'),
			GDO_EditedAt::make('dl_edited'),
			GDO_EditedBy::make('dl_editor'),
			GDO_DeletedAt::make('dl_deleted'),
			GDO_DeletedBy::make('dl_deletor'),
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
	 * @return GDO_Message
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
	    return GDO_Template::responsePHP('Download', 'card/download.php', ['gdo' => $this]);
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
