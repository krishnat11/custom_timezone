<?php

namespace Drupal\custom_timezone\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Component\Datetime\TimeInterface;

/**
 * Class CurrentTime.
 */
class CurrentTime {
    
    protected $config;
    protected $dateFormatter;
    protected $time;
    public function __construct(ConfigFactory $config,DateFormatterInterface $date_formatter,TimeInterface $time) {
        $this->config = $config;
        $this->dateFormatter = $date_formatter;
        $this->time = $time;
    }
    
    public static function create(ContainerInterface $container) {
        return new static(
          $container->get('config.factory'),
          $container->get('date.formatter'),
          $container->get('datetime.time')
        );
    }
    
    function getCurrentTime(){
        $config = $this->config->get('custom_timezone.settings');
        $currentTimeZone = $config->get('timezone');
        $date = $this->time->getCurrentTime();
        $date_output = $this->dateFormatter->format($date, 'custom', 'j F Y - h:ia', $currentTimeZone, '');
        return $date_output;
    }
    function getCurrentLocation(){
        $currentCountry = 'NA';
        $currentCity = 'NA';
        $config = $this->config->get('custom_timezone.settings');
        $currentCountry = $config->get('country');
        $currentCity = $config->get('city');
        $data['currentCity'] = $currentCity;
        $data['currentCountry'] = $currentCountry;
        return $data;
    }
}
