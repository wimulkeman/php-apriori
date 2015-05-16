<?php
/**
 * Created by IntelliJ IDEA.
 * User: wimulkeman
 * Date: 16-5-2015
 * Time: 16:17
 */

namespace Bearwulf\DataMining\Apriori;


use Bearwulf\DataMining\Apriori\Data\Input;
use Bearwulf\DataMining\Apriori\Data\Output;
use Bearwulf\DataMining\Apriori\Data\Transaction;

class Threshold
{
    private $inputData;
    private $outputData;
    private $transaction;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->inputData = new Input($configuration);
        $this->outputData = new Output($configuration);
        $this->transaction = new Transaction($configuration);
    }

    public function createThreshold()
    {
        $this->inputData->flushThresholdItems();

        $transactionItems = array();

        foreach ($this->outputData->getDataSetRecord() as $record) {
            foreach ($this->transaction->getTransactionItems($record) as $item) {
                if (in_array($item, $transactionItems)) {
                    continue;
                }

                $transactionItems[] = $item;
            }
        }

        foreach ($transactionItems as $itemId) {
            $itemCount = 0;
            foreach ($this->outputData->getDataSetRecord() as $record) {
                foreach ($this->transaction->getTransactionItems($record) as $item) {
                    if ($item != $itemId) {
                        continue;
                    }

                    $itemCount ++;
                }
            }

            if ($itemCount >= $this->projectConfiguration->getMinimumThreshold()) {
                $this->inputData->addThresholdOnItemIdAndCount($itemId, $itemCount);
            }
        }
    }
}