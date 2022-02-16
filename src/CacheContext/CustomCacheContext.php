<?php

namespace Drupal\custom_timezone\CacheContext;

use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Component\Datetime\TimeInterface;

class CustomCacheContext implements CacheContextInterface {

    protected $config;
    protected $dateFormatter;
    protected $time;
    public function __construct(ConfigFactory $config,DateFormatterInterface $date_formatter,TimeInterface $time) {
        $this->config = $config;
        $this->dateFormatter = $date_formatter;
        $this->time = $time;
    }
  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Custom time cache context');
  }
  /**
  * {@inheritdoc}
  */
  public function getContext() {
      $config = $this->config->get('custom_timezone.settings');
      $currentTimeZone = $config->get('timezone');
      $date = $this->time->getCurrentTime();
      $date_output = $this->dateFormatter->format($date, 'custom', 'j F Y - h:ia', $currentTimeZone, '');
      return $date_output;
  }
  /**
  * {@inheritdoc}
  */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }
}