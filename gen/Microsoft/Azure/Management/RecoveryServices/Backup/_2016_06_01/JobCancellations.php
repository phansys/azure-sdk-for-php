<?php
namespace Microsoft\Azure\Management\RecoveryServices\Backup\_2016_06_01;
final class JobCancellations
{
    /**
     * @param \Microsoft\Rest\ClientInterface $_client
     */
    public function __construct(\Microsoft\Rest\ClientInterface $_client)
    {
        $this->_Trigger_operation = $_client->createOperation('JobCancellations_Trigger');
    }
    /**
     * Cancels the job. This is an asynchronous operation. To know the status of the cancellation, call the GetCancelOperationResult API.
     * @param string $vaultName
     * @param string $resourceGroupName
     * @param string $jobName
     */
    public function trigger(
        $vaultName,
        $resourceGroupName,
        $jobName
    )
    {
        return $this->_Trigger_operation->call([
            'vaultName' => $vaultName,
            'resourceGroupName' => $resourceGroupName,
            'jobName' => $jobName
        ]);
    }
    /**
     * @var \Microsoft\Rest\OperationInterface
     */
    private $_Trigger_operation;
}