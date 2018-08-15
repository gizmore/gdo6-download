<?php
namespace GDO\Download;
use GDO\Vote\GDO_VoteTable;
final class GDO_DownloadVote extends GDO_VoteTable
{
	public function gdoVoteObjectTable() { return GDO_Download::table(); }
}
