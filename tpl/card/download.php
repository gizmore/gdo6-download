<?php
/**
 * This template file (download card) uses only code to arrange the outcome.
 * This way there is no need for an additional template.
 * However you can override the template and can even introduce logic.
 */
use GDO\UI\GDT_Button;
use GDO\User\GDO_User;
use GDO\UI\GDT_Card;
use GDO\Download\GDO_Download;

/** @var $gdo GDO_Download **/

// $file = $gdo->getFile();

$user = GDO_User::current();

// Card with title
$card = GDT_Card::make('gdo-download')->gdo($gdo);

$card->creatorHeader($gdo->gdoColumn('dl_title'));
// $card->subtitle($gdo->gdoMessage());

// Card content
$card->addFields(array(
    $gdo->gdoColumn('dl_file'),
//     $file->gdoColumn('file_type')->gdo($gdo->getFile()),
    $gdo->gdoColumn('dl_downloads'),
    $gdo->gdoColumn('dl_votes'),
    $gdo->gdoColumn('dl_rating'),
	
));
if ($gdo->isPaid())
{
	$card->addField($gdo->gdoColumn('dl_price'));
}

// Card actions
if ($gdo->canDownload($user))
{
	$card->actions()->addField(
		GDT_Button::make('download')->icon('download')->href(href('Download', 'File', '&id='.$gdo->getID()))
	);
}
elseif ($gdo->canPurchase($user))
{
	$card->actions()->addField(
		GDT_Button::make('purchase')->icon('money')->href(href('Download', 'Order', '&id='.$gdo->getID()))
	);
}

// Render
echo $card->render();
