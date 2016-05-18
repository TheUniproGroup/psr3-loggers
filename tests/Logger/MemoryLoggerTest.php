<?php
/**
 * @file
 * Unit tests for the MemoryLogger class.
 */

namespace TheUniproGroup\Tests\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\Test\LoggerInterfaceTest;
use Specsavers\OrderProcessing\Infrastructure\Services\Log\MemoryLogger;

class MemoryLoggerTest extends LoggerInterfaceTest
{
    /**
     * @var MemoryLogger
     */
    private $logger;

    /**
     * @before
     */
    public function createLogger()
    {
        $this->logger = new MemoryLogger();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * This must return the log messages in order.
     *
     * The simple formatting of the messages is: "<LOG LEVEL> <MESSAGE>".
     *
     * Example ->error('Foo') would yield "error Foo".
     *
     * @return string[]
     */
    public function getLogs()
    {
        return $this->logger->flatLog();
    }

}
