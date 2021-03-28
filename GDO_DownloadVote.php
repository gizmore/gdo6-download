<?php
namespace GDO\Download;

use GDO\Vote\GDO_VoteTable;

/**
 * Vote table for downloads.
 * @author gizmore
 * @version 6.10.1
 * @since 6.0.0
 */
final class GDO_DownloadVote extends GDO_VoteTable
{
	public function gdoVoteObjectTable() { return GDO_Download::table(); }
	public function gdoVoteGuests() { return Module_Download::instance()->cfgGuestVotes(); }

}
