<?php
namespace GDO\Download;
use GDO\Vote\VoteTable;
final class DownloadVote extends VoteTable
{
	public function gdoVoteObjectTable() { return Download::table(); }
}
