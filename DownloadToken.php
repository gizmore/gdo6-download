<?php
namespace GDO\Download;

use GDO\DB\GDO;
use GDO\DB\GDT_CreatedAt;
use GDO\DB\GDT_CreatedBy;
use GDO\DB\GDT_Object;
use GDO\Payment\Orderable;
use GDO\Payment\PaymentModule;
use GDO\Template\GDT_Template;
use GDO\Template\Message;
use GDO\Type\GDT_Token;
use GDO\User\GDT_User;
use GDO\User\User;
/**
 * Purchasable download token. 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 */
final class DownloadToken extends GDO implements Orderable
{
	#############
	### Order ###
	#############
	public function getOrderCancelURL(User $user) { return url('Download', 'FileList'); }
	public function getOrderSuccessURL(User $user) { return url('Download', 'View', 'id='.$this->getDownloadID()); }
	public function getOrderTitle(string $iso) { return tiso($iso, 'card_title_downloadtoken', [html($this->getDowload()->getTitle())]); }
	public function getOrderPrice() { return $this->getDowload()->getPrice(); }
	public function canPayOrderWith(PaymentModule $module) { return true; }
	public function onOrderPaid()
	{
		$this->insert();
		return new Message('msg_download_purchased');
	}

	###########
	### GDO ###
	###########
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDT_User::make('dlt_user')->primary(),
			GDT_Object::make('dlt_download')->table(Download::table())->primary(),
			GDT_Token::make('dlt_token')->notNull(),
			GDT_CreatedAt::make('dlt_created'),
			GDT_CreatedBy::make('dlt_creator'),
		);
	}
	
	/**
	 * @return User
	 */
	public function getUser() { return $this->getValue('dlt_user'); }
	public function getUserID() { return $this->getVar('dlt_user'); }

	/**
	 * @return Download
	 */
	public function getDowload() { return $this->getValue('dlt_download'); }
	public function getDowloadID() { return $this->getVar('dlt_download'); }
	
	/**
	 * @return User
	 */
	public function getCreatedBy() { return $this->getValue('dlt_creator'); }
	public function getCreatedAt() { return $this->getVar('dlt_created'); }
	public function getToken() { return $this->getVar('dlt_token'); }
	
	public static function hasToken(User $user, Download $dl)
	{
		return self::table()->select('1')->where("dlt_user={$user->getID()} AND dlt_download={$dl->getID()}")->first()->exec()->fetchValue() === '1';
	}

	##############
	### Render ###
	##############
	public function renderCard()
	{
	    return GDT_Template::responsePHP('Download', 'card/download_token.php', ['gdo' => $this]);
	}
	
}
