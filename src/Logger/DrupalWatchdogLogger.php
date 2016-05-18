<?php
/**
 * @file
 * Custom logger for logging to Drupal's dblog.
 */

namespace TheUniproGroup\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class DrupalWatchdogLogger extends AbstractLogger {

  public static $levelMap = array(
    LogLevel::EMERGENCY => WATCHDOG_EMERGENCY,
    LogLevel::ALERT => WATCHDOG_ALERT,
    LogLevel::CRITICAL => WATCHDOG_CRITICAL,
    LogLevel::ERROR => WATCHDOG_ERROR,
    LogLevel::WARNING => WATCHDOG_WARNING,
    LogLevel::NOTICE => WATCHDOG_NOTICE,
    LogLevel::INFO => WATCHDOG_INFO,
    LogLevel::DEBUG => WATCHDOG_DEBUG,
  );

  private $type = 'PSR-3';

  /**
   * Sets the type of watchdog entries created by this Psr3Watchdog instance.
   *
   * If not set, 'PSR-3' is used.
   *
   * @param string $type
   *   The category to which this message belongs. Can be any string, but
   *   the general practice is to use the name of the module calling watchdog().
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    $drupal_level = DrupalWatchdogLogger::$levelMap[$level];
    $minimum_drupal_level = DrupalWatchdogLogger::$levelMap[variable_get("unipro_psr3_watchdog_min_severity_{$this->type}", LogLevel::WARNING)];
    if ($drupal_level > $minimum_drupal_level) {
      return;
    }
    $watchdog_message = array($message);
    foreach ($context as $key => $value) {
      $watchdog_message[] = ucwords($key) . ': ' . $value;
    }
    watchdog($this->type, implode(', ', $watchdog_message), array(), $drupal_level);
  }
}
