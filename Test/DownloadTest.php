<?php
namespace GDO\Download\Test;

use GDO\Tests\TestCase;
use GDO\Download\Method\Crud;
use GDO\Tests\MethodTest;
use GDO\Download\GDO_Download;
use function PHPUnit\Framework\assertEquals;

/**
 * Download also tests payment and voting.
 * @author gizmore
 */
final class DownloadTest extends TestCase
{
    public function testUpload()
    {
        $m = Crud::make();
        $p = [
            
        ];
        MethodTest::make()->method($m)->parameters($p)->execute();
        
        assertEquals(1, GDO_Download::table()->countWhere(), 'Test upload of a text file.');
    }
    
    public function testVoting()
    {
        
    }
    
    public function testDownload()
    {
        
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
