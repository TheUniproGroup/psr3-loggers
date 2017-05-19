<?php
/**
 * @file
 * Custom logger for logging via Drupal Drush.
 */

namespace TheUniproGroup\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class DrupalDrushLogger extends AbstractLogger {
  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    $drush_message = array($message);
    foreach ($context as $key => $value) {
      $drush_message[] = ucwords($key) . ': ' . $value;
    }
    drush_print(implode(', ', $drush_message));
  }
}
