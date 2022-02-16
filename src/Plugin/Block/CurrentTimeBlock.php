<?php

namespace Drupal\custom_timezone\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_timezone\Services\CurrentTime;
use Drupal\Core\Cache\Cache;

/**
 * Provides a block with a current timezone time.
 *
 * @Block(
 *   id = "custom_current_time_block",
 *   admin_label = @Translation("Current Time"),
 * )
 */
class CurrentTimeBlock extends BlockBase implements ContainerFactoryPluginInterface  {
    
  protected $customtime;

  /**
   * @var Drupal\Core\Config\ConfigFactory
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentTime $customtime) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->customtime = $customtime;
   
  }
    
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('custom_timezone_service')
    
    );
  }
   
  /**
  * {@inheritdoc}
  */ 
  public function build() {
     $curentTimeData['curentTime'] = $this->customtime->getCurrentTime();
     $curentLocation = $this->customtime->getCurrentLocation();
     $curentTimeData['currentCity'] = $curentLocation['currentCity'];
     $curentTimeData['currentCountry'] = $curentLocation['currentCountry'];
     return [
            '#theme' => "custom-timezone",
            '#currentData' => $curentTimeData,
            '#cache' => [
              'contexts' => [
                'custom_current_time',
              ],
            ],
          ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(
      parent::getCacheContexts(),
      ['custom_current_time']
      );
  }


  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['custom_current_time_block'] = $form_state->getValue('custom_current_time_block');
  }
}