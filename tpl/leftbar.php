<?php 
use GDO\Download\Download;
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;
$navbar instanceof GDT_Bar;
$count = Download::countDownloads();
$navbar->addField(GDT_Link::make('a')->label('link_downloads', [$count])->href(href('Download', 'FileList')));
