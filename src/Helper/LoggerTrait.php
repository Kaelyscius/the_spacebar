<?php

namespace App\Helper;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     * @required //Pour dire à symfony qu'il faut aller le logger avant d'instancier la classe
     */
    public function setLooger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function logInfo(string $message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->info($message, $context);
        }
    }
}
