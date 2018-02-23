<?php
/**
 * This template file (download card) uses only code to arrange the outcome.
 */
use GDO\Download\GDO_Download;
use GDO\UI\GDT_Button;
use GDO\User\GDO_User;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Paragraph;
use GDO\Vote\GDT_VoteSelection;
$gdo instanceof GDO_Download;
$file = $gdo->getFile(); ?>
<?php
$user = GDO_User::current();

// Card with title
$card = GDT_Card::make('gdo-download')->gdo($gdo);
$card->withCreator();
$card->withCreated();
$card->title($gdo->displayTitle());
$card->subtitle($gdo->displayInfoText());

// Card content
$card->addFields(array(
	GDT_Paragraph::withHTML(sprintf("%s: %s", t('name'), $file->displayName())),
	GDT_Paragraph::withHTML(sprintf("%s: %s", t('type'), $file->getType())),
	GDT_Paragraph::withHTML(sprintf("%s: %s", t('downloads'), $gdo->getDownloads())),
	GDT_Paragraph::withHTML(sprintf("%s: %s", t('votes'), $gdo->gdoColumn('dl_votes')->gdo($gdo)->renderCell())),
	GDT_Paragraph::withHTML(sprintf("%s: %s %s", t('rating'), $gdo->gdoColumn('dl_rating')->gdo($gdo)->renderCell(), GDT_VoteSelection::make()->gdo($gdo)->renderForm())),
	
));
if ($gdo->isPaid())
{
	$card->addField(GDT_Paragraph::withHTML(sprintf('%s: %s', t('price'), $gdo->displayPrice())));
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
