<?php

/**
 * @file
 * Contains Drupal\custom_timezone\Form\CustomTimeZoneForm.
 */

namespace Drupal\custom_timezone\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CustomTimeZoneForm.
 *
 * @package Drupal\custom_timezone\Form
 */
class CustomTimeZoneForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_timezone.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_timezone.settings');
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];
    
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];
    $timezone_options = [   'America/Chicago' => 'America/Chicago',
                            'America/New_York' => 'America/New_York',
                            'Asia/Tokyo' => 'Asia/Tokyo',
                            'Asia/Dubai' => 'Asia/Dubai',
                            'Asia/Kolkata' => 'Asia/Kolkata',
                            'Europe/Amsterdam' => 'Europe/Amsterdam',
                            'Europe/Oslo' => 'Europe/Oslo',
                            'Europe/London' => 'Europe/London'
                            ];
    $form['timezone'] = [
      '#title' => t('Select Timezone'),
      '#type' => 'select',
      '#description' => 'Select Timezone.',
      '#options' => $timezone_options,
      '#default_value' => $config->get('timezone'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('custom_timezone.settings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
  }

}