<?php
/**
 * @file
 * Custom logger for storing log messages in memory.
 */

namespace TheUniproGroup\Logger;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MemoryLogger implements LoggerInterface
{
    private $ticksStart = null;
    private $auditTrail = array();

    /**
     * {@inheritdoc}
     */
    public function getLog()
    {
        return $this->auditTrail;
    }

    public function flatLog()
    {
        return array_map(function (array $logItem) {
            return $logItem['level'] . ' ' . strtr(
              $logItem['description'],
              array_reduce(array_keys($logItem['data']), function ($carry, $data) use ($logItem) {
                  $carry['{' . $data . '}'] = $logItem['data'][$data];
                  return $carry;
              }, array()));
        }, $this->auditTrail);
    }

    public function flush()
    {
        $this->auditTrail = array();
        $this->ticksStart = null;
    }

    /**
     * Gets the time passed since the first log message was added.
     *
     * @return float
     *   The offset, in milliseconds.
     */
    private function getTimestampOffset()
    {
        return round((microtime(true) - $this->ticksStart) * 1000);
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        if (!in_array($level, array(
          LogLevel::EMERGENCY,
          LogLevel::ALERT,
          LogLevel::CRITICAL,
          LogLevel::DEBUG,
          LogLevel::ERROR,
          LogLevel::INFO,
          LogLevel::NOTICE,
          LogLevel::WARNING
        ))) {
            throw new InvalidArgumentException();
        }

        if ($this->ticksStart == null) {
            $this->ticksStart = microtime(true);
        }

        $this->auditTrail[] = array(
          'level' => $level,
          'timestamp' => $this->getTimestampOffset(),
          'id' => isset($context['type']) ? $context['type'] : 'Unknown',
          'description' => $message,
          'data' => $context,
        );
    }
}
