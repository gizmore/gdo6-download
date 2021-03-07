<?php
namespace GDO\Download\Method;

use GDO\Core\GDOError;
use GDO\Core\Method;
use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\User\GDO_User;
use GDO\DB\GDT_Object;
use GDO\Core\GDT_Response;
use GDO\UI\GDT_Page;

/**
 * View a download for downloading or purchase.
 * @author gizmore
 * @version 6.10.1
 * @since 3.1.0
 */
final class View extends Method
{
    public function gdoParameters()
    {
        return [
            GDT_Object::make('id')->table(GDO_Download::table())->notNull(),
        ];
    }
    
	public function execute()
	{
		/** @var $dl GDO_Download **/
	    # File
		$dl = $this->gdoParameterValue('id');
		
		# Security
		$user = GDO_User::current();
		if (!$dl->canView($user))
		{
			throw new GDOError('err_gdo_not_found', [$dl->gdoHumanName(), $dl->getID()]);
		}
		# Render
		$module = Module_Download::instance();
		$tabs = $module->renderTabs();
		return $tabs->addHTML($dl->renderCard());
	}

}
