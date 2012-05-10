<?php

/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP version 5
 *
 * @category  Microsoft
 * @package   Tests\Unit\WindowsAzure\Services\Blob\Models
 * @author    Abdelrahman Elogeel <Abdelrahman.Elogeel@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      http://pear.php.net/package/azure-sdk-for-php
 */
namespace Tests\Unit\WindowsAzure\Services\Blob\Models;
use WindowsAzure\Utilities;
use WindowsAzure\Services\Blob\Models\GetBlobMetadataResult;

/**
 * Unit tests for class GetBlobMetadataResult
 *
 * @category  Microsoft
 * @package   Tests\Unit\WindowsAzure\Services\Blob\Models
 * @author    Abdelrahman Elogeel <Abdelrahman.Elogeel@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/azure-sdk-for-php
 */
class GetBlobMetadataResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers WindowsAzure\Services\Blob\Models\GetBlobMetadataResult::getEtag
     */
    public function testGetEtag()
    {
        // Setup
        $getBlobMetadataResult = new GetBlobMetadataResult();
        $expected = '0x8CACB9BD7C6B1B2';
        $getBlobMetadataResult->setEtag($expected);
        
        // Test
        $actual = $getBlobMetadataResult->getEtag();
        
        // Assert
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers WindowsAzure\Services\Blob\Models\GetBlobMetadataResult::setEtag
     */
    public function testSetEtag()
    {
        // Setup
        $getBlobMetadataResult = new GetBlobMetadataResult();
        $expected = '0x8CACB9BD7C6B1B2';
        
        // Test
        $getBlobMetadataResult->setEtag($expected);
        
        // Assert
        $actual = $getBlobMetadataResult->getEtag();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers WindowsAzure\Services\Blob\Models\GetBlobMetadataResult::getLastModified
     */
    public function testGetLastModified()
    {
        // Setup
        $getBlobMetadataResult = new GetBlobMetadataResult();
        $expected = Utilities::rfc1123ToDateTime('Fri, 09 Oct 2009 21:04:30 GMT');
        $getBlobMetadataResult->setLastModified($expected);
        
        // Test
        $actual = $getBlobMetadataResult->getLastModified();
        
        // Assert
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers WindowsAzure\Services\Blob\Models\GetBlobMetadataResult::setLastModified
     */
    public function testSetLastModified()
    {
        // Setup
        $getBlobMetadataResult = new GetBlobMetadataResult();
        $expected = Utilities::rfc1123ToDateTime('Fri, 09 Oct 2009 21:04:30 GMT');
        
        // Test
        $getBlobMetadataResult->setLastModified($expected);
        
        // Assert
        $actual = $getBlobMetadataResult->getLastModified();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers WindowsAzure\Services\Blob\Models\GetBlobMetadataResult::setMetadata
     */
    public function testSetMetadata()
    {
        // Setup
        $container = new GetBlobMetadataResult();
        $expected = array('key1' => 'value1', 'key2' => 'value2');
        
        // Test
        $container->setMetadata($expected);
        
        // Assert
        $this->assertEquals($expected, $container->getMetadata());
    }
    
    /**
     * @covers WindowsAzure\Services\Blob\Models\GetBlobMetadataResult::getMetadata
     */
    public function testGetMetadata()
    {
        // Setup
        $container = new GetBlobMetadataResult();
        $expected = array('key1' => 'value1', 'key2' => 'value2');
        $container->setMetadata($expected);
        
        // Test
        $actual = $container->getMetadata();
        
        // Assert
        $this->assertEquals($expected, $actual);
    }
}

?>
