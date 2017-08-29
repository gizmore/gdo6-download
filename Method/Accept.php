<?php
namespace GDO\Download\Method;

use GDO\Core\Method;
use GDO\Date\Time;
use GDO\Download\GDO_Download;
use GDO\Util\Common;

final class Accept extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
	    $table = GDO_Download::table();
		$id = Common::getGetString('id', '0');
		if ( (!($download = $table->find($id, false))) || 
			 ($download->gdoHashcode() !== Common::getGetString('token')) )
		{
			return $this->error('err_gdo_not_found', [$table->gdoClassName(), get_called_class(), html($id)]);
		}
		$download->saveVars(array(
			'dl_accepted' => Time::getDate(),
			'dl_acceptor' => Common::getGetInt('by'),
		));
	}
}
