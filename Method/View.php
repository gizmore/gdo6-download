<?php
namespace GDO\Download\Method;

use GDO\Core\GDOError;
use GDO\Core\Method;
use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\User\GDO_User;
use GDO\Util\Common;

final class View extends Method
{
	public function execute()
	{
		$user = GDO_User::current();
		$table = GDO_Download::table();
		$module = Module_Download::instance();
		$id = Common::getGetInt('id');
		$dl = $table->find($id);
		if (!$dl->canView($user))
		{
			throw new GDOError('err_gdo_not_found', [$table->gdoClassName(), $id]);
		}
		$tabs = $module->renderTabs();
		return $tabs->addHTML($dl->renderCard());
	}
}
