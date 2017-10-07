<?php
use GDO\Download\GDO_Download;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;

$bar = GDT_Bar::make();
$count = GDO_Download::countDownloads();
$bar->addFields(array(
	GDT_Link::make('link_downloads')->href(href('Download', 'FileList'))->label('link_downloads', [$count]),
	GDT_Link::make('link_upload')->href(href('Download', 'Crud')),
));
echo $bar->renderCell();
