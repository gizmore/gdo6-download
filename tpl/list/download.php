<?php /** @var \GDO\Download\GDO_Download $download **/
/**
 * This is the default download list item template.
 * It has no html at all, so it should be compatible with all themes :)
 */
use GDO\Profile\GDT_ProfileLink;
use GDO\UI\GDT_Button;
use GDO\UI\GDT_ListItem;
use GDO\UI\GDT_Paragraph;
use GDO\UI\GDT_Headline;
use GDO\User\GDO_User;

# ListItem
$li = GDT_ListItem::make();

# Image
$li->image(GDT_ProfileLink::make()->forUser($download->getCreator()));

# Title
$li->title($download->gdoColumn('dl_title'));

# Subtitle
$subtitle = t('li_download_count', [$download->getDownloads()]);
$subtitle .= '&nbsp;&nbsp;';
$subtitle .= t('li_download_price', [$download->displayPrice()]);
$li->subtitle(GDT_Headline::withHTML($subtitle)->level(4));

# Subtext
$li->subtext(GDT_Paragraph::withHTML($download->displayInfoText()));

# Actions
$li->actions()->addFields(array(
	GDT_Button::make('btn_download')->href($download->href_view()),
	GDT_Button::make('btn_edit')->href($download->href_edit())->disabled(!$download->canEdit(GDO_User::current())),
));

# Render
echo $li->render();
