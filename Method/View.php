<?php
namespace GDO\Download\Method;

use GDO\Core\GDOError;
use GDO\Core\GDT_Response;
use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\User\GDO_User;
use GDO\DB\GDT_Object;
use GDO\Core\GDT_ResponseCard;
use GDO\UI\MethodCard;
use GDO\UI\GDT_CardView;

/**
 * View a download for downloading or purchase.
 *
 * @author gizmore
 * @version 6.10.6
 * @since 3.1.0
 */
final class View extends MethodCard
{
	public function gdoTable()
	{
		return GDO_Download::table();
	}
	
// 	public function gdoParameters()
// 	{
// 		return [
// 			GDT_Object::make('id')->table(GDO_Download::table())->notNull(),
// 		];
// 	}

	public function beforeExecute()
	{
		$module = Module_Download::instance();
		$module->renderTabs();
	}

	public function execute()
	{
		/** @var $dl GDO_Download **/
		# File
		$dl = $this->getObject();

		# Security
		$user = GDO_User::current();
		if (!$dl->canView($user))
		{
			throw new GDOError('err_gdo_not_found',
				[
					$dl->gdoHumanName(),
					$dl->getID()
				]);
		}
		
		# Render
		return GDT_Response::makeWith(GDT_CardView::make()->gdo($dl));
	}
	
}
