<?php
use GDO\Download\Download;
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;

$bar = GDT_Bar::make();
$count = Download::countDownloads();
$bar->addFields(array(
	GDT_Link::make('link_downloads')->href(href('Download', 'FileList'))->label('link_downloads', [$count]),
	GDT_Link::make('link_upload')->href(href('Download', 'Crud')),
));
echo $bar->renderCell();
