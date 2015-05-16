<?php
/**
 * This file is part of the Bearwulf package.
 *
 * (c) Wim Ulkeman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For Contributing on the code, please view the CONTRIBUTING file that was
 * distrubuted with this source code
 */

namespace Bearwulf\DataMining\Apriori;

use Bearwulf\DataMining\Apriori\Data\Input;
use Bearwulf\DataMining\Apriori\Data\Output;
use Bearwulf\DataMining\Apriori\Data\Transaction;

class Support
{
    private $inputData;
    private $outputData;
    private $transaction;
    private $totalNumberOfTransactions = 0;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->inputData = new Input($configuration);
        $this->outputData = new Output($configuration);
        $this->transaction = new Transaction($configuration);

        $this->totalNumberOfTransactions = $this->transaction->getNumberOfTransactions();
    }

    public function createSupport()
    {
        $this->inputData->flushSupportItems();
        $this->setSupportForSingleItems();
    }

    public function setSupportForSingleItems()
    {
        foreach ($this->outputData->getThresholdItem() as $item) {
            $support = $item->count / $this->totalNumberOfTransactions;
            if (! $support >= $this->projectConfiguration->getMinimumSupport()) {
                continue;
            }

            $this->inputData->addSupportOnItemIdAndSupport($item->itemId, $support);
        }
    }
}