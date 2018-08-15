<?php
namespace GDO\Download\Method;

use GDO\Download\GDO_Download;
use GDO\Download\GDO_DownloadToken;
use GDO\Form\GDT_Form;
use GDO\Payment\Payment_Order;
use GDO\User\GDO_User;
use GDO\Util\Common;

final class Order extends Payment_Order
{
	public function getOrderable()
	{
		$download = GDO_Download::table()->find(Common::getRequestString('id'));
		$user = GDO_User::current()->persistent();
		return GDO_DownloadToken::blank(array(
			'dlt_user' => $user->getID(),
			'dlt_download' => $download->getID(),
		));
	}
	
	public function execute()
	{
		return $this->initOrderable();
	}
	
	public function createForm(GDT_Form $form)
	{
		
	}
	
}
