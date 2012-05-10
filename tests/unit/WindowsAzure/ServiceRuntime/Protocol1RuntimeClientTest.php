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
 * @package   Tests\Unit\WindowsAzure\ServiceRuntime
 * @author    Abdelrahman Elogeel <Abdelrahman.Elogeel@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      http://pear.php.net/package/azure-sdk-for-php
 */
namespace Tests\Unit\WindowsAzure\ServiceRuntime;

use Tests\Framework\TestResources;
use WindowsAzure\Utilities;
use WindowsAzure\ServiceRuntime\AcquireCurrentState;
use WindowsAzure\ServiceRuntime\ChunkedGoalStateDeserializer;
use WindowsAzure\ServiceRuntime\CurrentStatus;
use WindowsAzure\ServiceRuntime\FileInputChannel;
use WindowsAzure\ServiceRuntime\FileOutputChannel;
use WindowsAzure\ServiceRuntime\Protocol1RuntimeClient;
use WindowsAzure\ServiceRuntime\Protocol1RuntimeCurrentStateClient;
use WindowsAzure\ServiceRuntime\Protocol1RuntimeGoalStateClient;
use WindowsAzure\ServiceRuntime\XmlCurrentStateSerializer;
use WindowsAzure\ServiceRuntime\XmlRoleEnvironmentDataDeserializer;

/**
 * Unit tests for class Protocol1RuntimeClient.
 *
 * @category  Microsoft
 * @package   Tests\Unit\WindowsAzure\ServiceRuntime
 * @author    Abdelrahman Elogeel <Abdelrahman.Elogeel@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/azure-sdk-for-php
 */
class Protocol1RuntimeClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers WindowsAzure\ServiceRuntime\Protocol1RuntimeClient::__construct
     */
    public function testConstruct()
    {
        // Setup
        $protocol1RuntimeCurrentStateClient =
            new Protocol1RuntimeCurrentStateClient(null, null);
        
        $protocol1RuntimeGoalStateClient = 
            new Protocol1RuntimeGoalStateClient(null, null, null, null);
        
        $endpoint = 'endpoint';
        
        $protocol1RuntimeClient = new Protocol1RuntimeClient(
            $protocol1RuntimeGoalStateClient,
            $protocol1RuntimeCurrentStateClient,
            $endpoint);
        
        // Test
        $this->assertInstanceOf('\WindowsAzure\\ServiceRuntime\\Protocol1RuntimeClient',
            $protocol1RuntimeClient);
    }
    
    /**
     * @covers WindowsAzure\ServiceRuntime\Protocol1RuntimeClient::getCurrentGoalState
     */
    public function testGetCurrentGoalState()
    {
        // Setup
        $rootDirectory = 'root';

        \vfsStreamWrapper::register();
        \vfsStreamWrapper::setRoot(new \vfsStreamDirectory($rootDirectory));

        $roleEnvironmentFileName = 'roleEnvironment';
        $roleEnvironmentFileContent = '<?xml version="1.0" encoding="utf-8"?>' .
            '<RoleEnvironment xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
            'xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
            '<Deployment id="deploymentId" emulated="false" />' .
            '<CurrentInstance id="id3" roleName="roleName" faultDomain="4" updateDomain="5">' .
            '</CurrentInstance>' .
            '<Roles />' .
            '</RoleEnvironment>';

        $roleEnvironmentFile = \vfsStream::newFile($roleEnvironmentFileName);
        $roleEnvironmentFile->setContent($roleEnvironmentFileContent); 

        \vfsStreamWrapper::getRoot()->addChild($roleEnvironmentFile);

        $goalStateFileName = 'goalstate';
        $goalStateFileContent = '<?xml version="1.0" encoding="utf-8"?>' .
            '<GoalState xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
            'xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
            '<Incarnation>1</Incarnation>' .
            '<ExpectedState>Started</ExpectedState>' .
            '<RoleEnvironmentPath>' .
            \vfsStream::url($rootDirectory . '/' . $roleEnvironmentFileName) .
            '</RoleEnvironmentPath>' .
            '<CurrentStateEndpoint>none</CurrentStateEndpoint>' .
            '<Deadline>9999-12-31T23:59:59.9999999</Deadline>' .
            '</GoalState>';

        $goalStateFileContent = dechex(strlen($goalStateFileContent)) . "\n" . $goalStateFileContent;

        $file = \vfsStream::newFile($goalStateFileName);
        $file->setContent($goalStateFileContent);

        \vfsStreamWrapper::getRoot()->addChild($file);

        $protocol1RuntimeCurrentStateClient =
            new Protocol1RuntimeCurrentStateClient(null, null);

        $goalStateDeserializer       = new ChunkedGoalStateDeserializer();
        $roleEnvironmentDeserializer = new XmlRoleEnvironmentDataDeserializer();
        $inputChannel                = new FileInputChannel();

        $protocol1RuntimeGoalStateClient =
            new Protocol1RuntimeGoalStateClient(
                $protocol1RuntimeCurrentStateClient,
                $goalStateDeserializer,
                $roleEnvironmentDeserializer,
                $inputChannel
            );

        $endpoint = \vfsStream::url($rootDirectory . '/' . $goalStateFileName);

        $protocol1RuntimeClient = new Protocol1RuntimeClient(
            $protocol1RuntimeGoalStateClient,
            $protocol1RuntimeCurrentStateClient,
            $endpoint
        );

        // Test
        $this->assertInstanceOf(
            '\WindowsAzure\\ServiceRuntime\\GoalState',
            $protocol1RuntimeClient->getCurrentGoalState()
        );
    }
    
   /**
     * @covers WindowsAzure\ServiceRuntime\Protocol1RuntimeClient::getRoleEnvironmentData
     */
    public function testGetRoleEnvironmentData()
    {
        // Setup
        $rootDirectory = 'root';

        \vfsStreamWrapper::register();
        \vfsStreamWrapper::setRoot(new \vfsStreamDirectory($rootDirectory));

        $roleEnvironmentFileName = 'roleEnvironment';
        $roleEnvironmentFileContent = '<?xml version="1.0" encoding="utf-8"?>' .
            '<RoleEnvironment xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
            'xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
            '<Deployment id="deploymentId" emulated="false" />' .
            '<CurrentInstance id="id3" roleName="roleName" faultDomain="4" updateDomain="5">' .
            '</CurrentInstance>' .
            '<Roles />' .
            '</RoleEnvironment>';

        $roleEnvironmentFile = \vfsStream::newFile($roleEnvironmentFileName);
        $roleEnvironmentFile->setContent($roleEnvironmentFileContent); 

        \vfsStreamWrapper::getRoot()->addChild($roleEnvironmentFile);

        $goalStateFileName = 'goalstate';
        $goalStateFileContent = '<?xml version="1.0" encoding="utf-8"?>' .
            '<GoalState xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
            'xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
            '<Incarnation>1</Incarnation>' .
            '<ExpectedState>Started</ExpectedState>' .
            '<RoleEnvironmentPath>' .
            \vfsStream::url($rootDirectory . '/' . $roleEnvironmentFileName) .
            '</RoleEnvironmentPath>' .
            '<CurrentStateEndpoint>none</CurrentStateEndpoint>' .
            '<Deadline>9999-12-31T23:59:59.9999999</Deadline>' .
            '</GoalState>';

        $goalStateFileContent = dechex(strlen($goalStateFileContent)) . "\n" . $goalStateFileContent;

        $file = \vfsStream::newFile($goalStateFileName);
        $file->setContent($goalStateFileContent);

        \vfsStreamWrapper::getRoot()->addChild($file);

        $protocol1RuntimeCurrentStateClient =
            new Protocol1RuntimeCurrentStateClient(null, null);

        $goalStateDeserializer       = new ChunkedGoalStateDeserializer();
        $roleEnvironmentDeserializer = new XmlRoleEnvironmentDataDeserializer();
        $inputChannel                = new FileInputChannel();

        $protocol1RuntimeGoalStateClient =
            new Protocol1RuntimeGoalStateClient(
                $protocol1RuntimeCurrentStateClient,
                $goalStateDeserializer,
                $roleEnvironmentDeserializer,
                $inputChannel
            );

        $endpoint = \vfsStream::url($rootDirectory . '/' . $goalStateFileName);

        $protocol1RuntimeClient = new Protocol1RuntimeClient(
            $protocol1RuntimeGoalStateClient,
            $protocol1RuntimeCurrentStateClient,
            $endpoint
        );

        // Test
        $this->assertInstanceOf(
            '\WindowsAzure\\ServiceRuntime\\RoleEnvironmentData',
            $protocol1RuntimeClient->getRoleEnvironmentData()
        );
    }
    
    /**
     * @covers WindowsAzure\ServiceRuntime\Protocol1RuntimeClient::setCurrentState
     */
    public function testSetCurrentState()
    {
        // Setup
        $rootDirectory = 'root';
        $fileName = 'test';
        $fileContents = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<CurrentState>' .
            '<StatusLease ClientId="clientId">' .
            '<Acquire>' .
            '<Incarnation>1</Incarnation>' .
            '<Status>Recycle</Status>' .
            '<Expiration>2000-01-01T00:00:00.0000000Z</Expiration>' .
            '</Acquire>' .
            '</StatusLease>' .
            '</CurrentState>';
        
        \vfsStreamWrapper::register(); 
        \vfsStreamWrapper::setRoot(new \vfsStreamDirectory($rootDirectory));
        
        $file = \vfsStream::newFile($fileName);
        \vfsStreamWrapper::getRoot()->addChild($file);
        
        $serializer = new XmlCurrentStateSerializer();
        $fileOutputChannel = new FileOutputChannel();

        $protocol1RuntimeCurrentStateClient =
            new Protocol1RuntimeCurrentStateClient(
                $serializer,
                $fileOutputChannel
            );

        $protocol1RuntimeCurrentStateClient->setEndpoint(
            \vfsStream::url($rootDirectory . '/' . $fileName)
        );
        
        $goalStateDeserializer       = new ChunkedGoalStateDeserializer();
        $roleEnvironmentDeserializer = new XmlRoleEnvironmentDataDeserializer();
        $inputChannel                = new FileInputChannel();

        $protocol1RuntimeGoalStateClient =
            new Protocol1RuntimeGoalStateClient(
                $protocol1RuntimeCurrentStateClient,
                $goalStateDeserializer,
                $roleEnvironmentDeserializer,
                $inputChannel
            );

        $endpoint = 'endpoint';
        $protocol1RuntimeClient = new Protocol1RuntimeClient(
            $protocol1RuntimeGoalStateClient,
            $protocol1RuntimeCurrentStateClient,
            $endpoint
        );
        
        // Test
        $recycleState = new AcquireCurrentState(
            'clientId',
            1,
            CurrentStatus::RECYCLE,
            new \DateTime('2000-01-01', new \DateTimeZone('UTC'))
        );
        
        $protocol1RuntimeClient->setCurrentState($recycleState);
        
        $fileInputChannel = new FileInputChannel();
        $fileInputStream = $fileInputChannel->getInputStream(
            \vfsStream::url($rootDirectory . '/' . $fileName)
        );
        
        $inputChannelContents = stream_get_contents($fileInputStream);
        $this->assertEquals($fileContents, $inputChannelContents);
    }
}

?>