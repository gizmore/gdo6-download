<?php
namespace GDO\Download\Method;

use GDO\Download\Download;
use GDO\Download\DownloadToken;
use GDO\Form\GDT_Form;
use GDO\Payment\Payment_Order;
use GDO\User\User;
use GDO\Util\Common;

final class Order extends Payment_Order
{
	public function getOrderable()
	{
		$download = Download::table()->find(Common::getRequestString('id'));
		$user = User::current()->persistent();
		return DownloadToken::blank(array(
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
