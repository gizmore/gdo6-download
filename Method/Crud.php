<?php
namespace GDO\Download\Method;

use GDO\Core\GDO;
use GDO\Date\Time;
use GDO\Download\GDO_Download;
use GDO\Download\Module_Download;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodCrud;
use GDO\Language\Trans;
use GDO\Mail\Mail;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
/**
 * Download CRUD form.
 * Sends approval mail.
 * 
 * @author gizmore
 * @version 6.10
 * @since 5.0
 * 
 * @see GDO_Download
 */
final class Crud extends MethodCrud
{
	public function gdoTable() { return GDO_Download::table(); }
	public function hrefList() { return href('Download', 'FileList'); }
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Download::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	protected function crudCreateTitle()
	{
		$this->title(t('ft_download_upload', [sitename()]));
	}
	
	public function createForm(GDT_Form $form)
	{
		$user = GDO_User::current();
		parent::createForm($form);
		if (!$user->hasPermission('staff'))
		{
			$form->removeField('dl_price');
		}
	}
	
	public function createFormButtons(GDT_Form $form)
	{
		parent::createFormButtons($form);
		$user = GDO_User::current();
		if ($user->isStaff())
		{
			if ($this->gdo && !$this->gdo->isAccepted())
			{
				$form->actions()->addField(GDT_Submit::make('accept'));
			}
		}
	}

	public function afterCreate(GDT_Form $form, GDO $gdo)
	{
		$user = GDO_User::current();
		if ($user->isStaff())
		{
			$gdo->saveVars(array(
				'dl_accepted' => Time::getDate(),
				'dl_acceptor' => GDO_User::system()->getID(),
			), false);
		}
		else
		{
			$this->onAcceptMail($form);
			return $this->message('msg_download_awaiting_accept');
		}
	}
	
	###################
	### Accept Mail ###
	###################
	private function onAcceptMail(GDT_Form $form)
	{
		$iso = Trans::$ISO;
		foreach (GDO_User::admins() as $admin)
		{
			Trans::$ISO = $admin->getLangISO();
			$this->onAcceptMailTo($form, $admin);
		}
		Trans::$ISO = $iso;
	}

	private function onAcceptMailTo(GDT_Form $form, GDO_User $user)
	{
		$dl = $this->gdo; $dl instanceof GDO_Download;

		# Sender
		$mail = Mail::botMail();
		
		# Body
		$username = $user->displayNameLabel();
		$sitename = sitename();
		$type = $dl->getType();
		$size = $dl->displaySize();
		$title = html($dl->getTitle());
		$info = $dl->displayInfo();
		$uploader = $dl->getCreator()->displayNameLabel();
		
		$link = GDT_Link::anchor(url('Download', 'Approve', "&id={$dl->getID()}&token={$dl->gdoHashcode()}"));
		$args = [$username, $sitename, $type, $size, $title, $info, $uploader, $link];
		$mail->setBody(tusr($user, 'mail_body_download_pending', $args));
		$mail->setSubject(tust($user, 'mail_subj_download_pending', [$sitename]));
		
		# Send
		$mail->sendToUser($user);
	}
}
