<?php
use GDO\Download\Download;
use GDO\Template\GDO_Bar;
use GDO\UI\GDO_Link;

$bar = GDO_Bar::make();
$count = Download::countDownloads();
$bar->addFields(array(
	GDO_Link::make('link_downloads')->href(href('Download', 'FileList'))->label('link_downloads', [$count]),
	GDO_Link::make('link_upload')->href(href('Download', 'Crud')),
));
echo $bar->renderCell();
