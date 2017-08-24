<?php 
use GDO\Download\Download;
use GDO\Template\GDO_Bar;
use GDO\UI\GDO_Link;

$navbar instanceof GDO_Bar;
$count = Download::countDownloads();
$navbar->addField(GDO_Link::make('a')->label('link_downloads', [$count])->href(href('Download', 'List')));
