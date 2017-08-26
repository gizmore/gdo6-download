<?php
namespace GDO\Download\Method;

use GDO\DB\GDO;
use GDO\Date\Time;
use GDO\Download\Download;
use GDO\Download\Module_Download;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodCrud;
use GDO\Language\Trans;
use GDO\Mail\Mail;
use GDO\UI\GDO_Link;
use GDO\User\User;
/**
 * Download form.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 * @see Download
 */
final class Crud extends MethodCrud
{
	public function gdoTable() { return Download::table(); }
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
	
	public function createForm(GDO_Form $form)
	{
		$user = User::current();
		parent::createForm($form);
		if (!$user->hasPermission('staff'))
		{
			$form->removeField('dl_price');
		}
	}
	
	public function createFormButtons(GDO_Form $form)
	{
		parent::createFormButtons($form);
		$user = User::current();
		if ($user->isStaff())
		{
			if ($this->gdo && !$this->gdo->isAccepted())
			{
				$form->addField(GDO_Submit::make('accept'));
			}
		}
	}

	public function afterCreate(GDO_Form $form, GDO $gdo)
	{
		$user = User::current();
		if ($user->isStaff())
		{
			$gdo->saveVars(array(
				'dl_accepted' => Time::getDate(),
				'dl_acceptor' => User::SYSTEM_ID,
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
	private function onAcceptMail(GDO_Form $form)
	{
		$iso = Trans::$ISO;
		foreach (User::admins() as $admin)
		{
			Trans::$ISO = $admin->getLangISO();
			$this->onAcceptMailTo($form, $admin);
		}
		Trans::$ISO = $iso;
	}

	private function onAcceptMailTo(GDO_Form $form, User $user)
	{
		$dl = $this->gdo; $dl instanceof Download;

		# Sender
		$mail = new Mail();
		$mail->setSender(GWF_BOT_NAME);
		$mail->setSenderName(GWF_BOT_NAME);
		
		# Body
		$username = $user->displayNameLabel();
		$sitename = sitename();
		$type = $dl->getType();
		$size = $dl->displaySize();
		$title = html($dl->getTitle());
		$info = $dl->displayInfo();
		$uploader = $dl->getCreator()->displayNameLabel();
		
		$link = GDO_Link::anchor(url('Download', 'Approve', "&id={$dl->getID()}&token={$dl->gdoHashcode()}"));
		$args = [$username, $sitename, $type, $size, $title, $info, $uploader, $link];
		$mail->setBody(t('mail_body_download_pending', $args));
		$mail->setSubject(t('mail_subj_download_pending', [$sitename]));
		
		# Send
		$mail->sendToUser($user);
	}
}
