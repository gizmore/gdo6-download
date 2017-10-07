<?php 
use GDO\Download\GDO_Download;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
$navbar instanceof GDT_Bar;
$count = GDO_Download::countDownloads();
$navbar->addField(GDT_Link::make('a')->label('link_downloads', [$count])->href(href('Download', 'FileList')));
