<?php
use GDO\Download\Download;
use GDO\UI\GDO_Button;
use GDO\User\User;

$gdo instanceof Download;
$file = $gdo->getFile(); ?>
<?php
$user = User::current();
?>
<md-card class="gdo-download">
  <md-card-title>
    <md-card-title-text>
      <span class="md-headline">
        <div>“<?= html($gdo->getTitle()); ?>” - <?= $gdo->getCreator()->renderCell(); ?></div>
        <div class="gdo-card-date"><?= l($gdo->getCreateDate()); ?></div>
      </span>
    </md-card-title-text>
  </md-card-title>
  <gdo-div></gdo-div>
  <md-card-content flex>
    <div><?= t('name'); ?>: <?= html($file->getName()); ?></div>
    <div><?= t('type'); ?>: <?= $file->getType(); ?></div>
    <div><?= t('size'); ?>: <?= $file->displaySize(); ?></div>
    <div><?= t('downloads'); ?>: <?= $gdo->getDownloads(); ?></div>
    <div><?= t('votes'); ?>: <?= $gdo->gdoColumn('dl_votes')->gdo($gdo)->renderCell(); ?></div>
    <div><?= t('rating'); ?>: <?= $gdo->gdoColumn('dl_rating')->gdo($gdo)->renderCell(); ?></div>
    <?php if ($gdo->isPaid()) : ?>
    <div><?= t('price'); ?>: <?= $gdo->displayPrice(); ?></div>
    <?php endif; ?>
    <hr>
    <?= $gdo->displayInfo(); ?>
  </md-card-content>
  <gdo-div></gdo-div>
  <md-card-actions layout="row" layout-align="end center">
<?php
if ($gdo->canDownload($user))
{
	echo GDO_Button::make('download')->icon('file_download')->href(href('Download', 'File', '&id='.$gdo->getID()))->renderCell();
}
elseif ($gdo->canPurchase($user))
{
	echo GDO_Button::make('purchase')->icon('attach_money')->href(href('Download', 'Order', '&id='.$gdo->getID()))->renderCell();
}
else
{
	
}

?>
  </md-card-actions>
</md-card>
