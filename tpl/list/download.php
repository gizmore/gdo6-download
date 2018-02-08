<?php /** @var \GDO\Download\GDO_Download $download **/
use GDO\Profile\GDT_ProfileLink;
use GDO\UI\GDT_Menu;
use GDO\UI\GDT_Button;
?>
<div class="gdt-list-item">
  <div><?=GDT_ProfileLink::make()->forUser($download->getCreator())->render()?></div>
  <div class="gdt-content">
    <h3><?=$download->displayTitle()?></h3>
    <h4>
      <?=t('li_download_count', [$download->getDownloads()])?>
      <?=t('li_download_price', [$download->displayPrice()])?>
    </h4>
    <p><?=$download->displayInfoText()?></p>
  </div>
  <div class="gdt-actions">
<?php
$menu = GDT_Menu::make();
$menu->addField(GDT_Button::make('btn_download')->href($download->href_view()));
echo $menu->render();
?>
  </div>
</div>
