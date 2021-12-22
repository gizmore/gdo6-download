<?php
namespace GDO\Download\Test;

use GDO\Tests\TestCase;
use GDO\Download\Method\Crud;
use GDO\Tests\MethodTest;
use GDO\Download\GDO_Download;
use function PHPUnit\Framework\assertEquals;
use GDO\Tests\Module_Tests;
use GDO\Vote\Method\Up;
use GDO\Download\GDO_DownloadVote;
use GDO\Download\Module_Download;
use function PHPUnit\Framework\assertMatchesRegularExpression;
use GDO\Core\Website;
use GDO\Download\Method\File;

/**
 * Download also tests payment and voting.
 * @author gizmore
 */
final class DownloadTest extends TestCase
{
    public function testUpload()
    {
        $path = Module_Tests::instance()->filePath(
            'Test/data/upload.txt');
        $this->fakeFileUpload('dl_file', 'upload.txt', $path);
        $m = Crud::make();
        $p = [
            'dl_title' => 'Upload test',
            'dl_info' => 'A test file for upload',
            'dl_level' => '0',
        ];
        $r = MethodTest::make()->method($m)->parameters($p);
        $r = $r->execute('create');
        $out = $r->renderHTML();
        
        $path = Module_Tests::instance()->filePath(
            'Test/data/01_BAND_SCHEIBE_VORFALL_-_INTRO_4.mp3');
        $this->fakeFileUpload('dl_file', 'ranzintro.mp3', $path);
        $m = Crud::make();
        $p = [
            'dl_title' => 'RanzIntro',
            'dl_info' => 'Some punk music',
            'dl_level' => '1',
        ];
        MethodTest::make()->method($m)->parameters($p)->execute('create');
        
        
        assertEquals(2, GDO_Download::table()->countWhere(), 'Test upload of an mp3 file.');
    }
    
    public function testVoting()
    {
        # Vote
        $dl = GDO_Download::findById('1');
        $m = Up::make();
        $p = [
            'gdo' => GDO_DownloadVote::table()->gdoClassName(),
            'id' => $dl->getID(),
            'rate' => '5',
        ];
        MethodTest::make()->method($m)->getParameters($p)->execute();
        $this->assert200('Assert voting works');
        assertEquals(5, $dl->getVoteRating(), 'Assert vote outcome is 5 on download.');
        
        # Revote
        $m = Up::make();
        $p = [
            'gdo' => GDO_DownloadVote::table()->gdoClassName(),
            'id' => $dl->getID(),
            'rate' => '4',
        ];
        MethodTest::make()->method($m)->getParameters($p)->execute();
        assertEquals(4, $dl->getVoteRating(), 'Assert vote outcome is 4 after revote.');
        
        # Blockers
        $this->userGaston();
        Module_Download::instance()->saveConfigVar('dl_vote_guest', '0');
        MethodTest::make()->method($m)->getParameters($p)->execute();
        assertMatchesRegularExpression('/Guests are not allowed/', Website::$TOP_RESPONSE->render(), 'Check Guest block.');
        
        Module_Download::instance()->saveConfigVar('dl_vote_guest', '1');
        MethodTest::make()->method($m)->getParameters($p)->execute();
        assertMatchesRegularExpression('/recently/', Website::$TOP_RESPONSE->render(), 'Check IP vote block.');
    }
    
    public function testDownload()
    {
        $dl = GDO_Download::findById('1');
        $m = File::make();
        $p = ['id' => $dl->getID()];
        MethodTest::make()->method($m)->getParameters($p)->execute();
        $this->assert200('Test if download does work.');
    }
    
    public function testDelete()
    {
        
    }
    
    public function testUnlock()
    {
        
    }
    
    public function testPayment()
    {
        
    }
    
}
