<?php
namespace GDO\Download\Method;

use GDO\Admin\MethodAdmin;
use GDO\Download\Download;
use GDO\Table\MethodQueryTable;

final class Admin extends MethodQueryTable
{
	use MethodAdmin;

	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		$response = parent::execute();
		return $this->renderNavBar('Download')->add($response);
	}
	
	public function getQuery()
	{
		return Download::table()->select();
	}
}
