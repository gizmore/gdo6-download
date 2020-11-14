<?php /** @var \GDO\Download\GDO_Download $download **/
/**
 * This is the default download list item template.
 * It has no html at all, so it should be compatible with all themes :)
 */
use GDO\UI\GDT_Button;
use GDO\UI\GDT_ListItem;
use GDO\UI\GDT_Paragraph;
use GDO\User\GDO_User;
use GDO\UI\GDT_Container;
use GDO\UI\GDT_Label;
use GDO\Vote\GDT_VoteSelection;
use GDO\UI\GDT_Bar;

# ListItem
$li = GDT_ListItem::make()->gdo($download);

$li->creatorHeader($download->gdoColumn('dl_title'));

# Content
$content = GDT_Container::make()->vertical();
$content->addField($download->gdoColumn('dl_info'));
if ($download->isPaid())
{
    $content->addField($download->gdoColumn('dl_price'));
}

$li->content(GDT_Paragraph::make()->textRaw($download->displayInfoText()));

# Subtext
$subc = GDT_Bar::make()->horizontal()->css('width', 'fit-content');
$subc->addField(GDT_VoteSelection::make()->gdo($download));
$subc->addField($download->gdoColumn('dl_rating'));
$subc->addField($download->gdoColumn('dl_votes'));
$subc->addField($download->gdoColumn('dl_downloads'));
$subc->addField(GDT_Label::make('downloads'));
$li->subtext($subc);

# Actions
$li->actions()->addFields(array(
	GDT_Button::make('btn_download')->href($download->href_view())->icon('download'),
	GDT_Button::make('btn_edit')->href($download->href_edit())->icon('edit')->disabled(!$download->canEdit(GDO_User::current())),
));

# Render
echo $li->render();
