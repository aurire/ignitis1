<?php

namespace Drupal\custom_drop\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'custom_drop_simple' widget.
 *
 * @FieldWidget(
 *   id = "custom_drop_simple",
 *   module = "custom_drop",
 *   label = @Translation("Custom drop as dropdown"),
 *   field_types = {
 *     "custom_drop_sch"
 *   }
 * )
 */
class DropdownWidget extends WidgetBase {

  const API_ENDPOINT_POSTIT_LT_V2 = 'https://api.postit.lt/v2/';
  const CURL_TIMEOUT = 80;

  /**
   * @param $apiKey
   * @param $limit
   * @param string $group
   * @return array
   */
  private function getMunicipalitiesFromPostIt($apiKey, $limit, $group = 'municipality')
  {
    $limitForPage = $limit > 20 ? 20 : $limit;

    $ch = curl_init();
    $url = self::API_ENDPOINT_POSTIT_LT_V2;
    $municipalities = [];

    for ($i = 1; $i < 6; $i++) {
      $getUrl = $url . "?" . http_build_query(
          [
            'key' => $apiKey,
            'group' => $group,
            'municipality' => '',
            'page' => $i,
            'limit' => $limitForPage,
          ]
        );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $getUrl);
      curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);

      $response = curl_exec($ch);

      if (curl_error($ch)) {
        \Drupal::logger('custom_drop')->error('Curl Error while getting contents from API: '
          . curl_error($ch));
        curl_close($ch);

        return [];
      }

      $parsed = json_decode($response, true);
      if (!isset($parsed['status']) || 'success' !== $parsed['status']) {
        \Drupal::logger('custom_drop')->error('Got an error response from API: '
          . $response);

        return [];
      }
      $municipalitiesCurrent = array_column($parsed['data'], 'municipality', 'municipality');
      $municipalities = array_merge($municipalities, $municipalitiesCurrent);
      if (count($municipalities) > $limit || 0 === count($municipalitiesCurrent)) {
        break;
      }
    }
    curl_close($ch);
    if (count($municipalities) > $limit) {
      $municipalities = array_slice($municipalities, 0, $limit);
    }

    return array_merge(['' => ''], $municipalities);
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $settings = $this->getFieldSettings();

    $optionsList = $this->getMunicipalitiesFromPostIt($settings['apikey'], $settings['maxitems']);
    $form['#attached']['library'][] = 'custom_drop/custom_drop_lib';
    $form['#attached']['drupalSettings']['custom_drop']['fieldtohide'] = $settings['fieldtohide'];
    $element += [
      '#type' => 'select',
      '#title' => $this
        ->t('Select element'),
      '#options' => $optionsList,
      '#default_value' => $value,
    ];

    return ['value' => $element];
  }

  /**
   * Validate the field.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) === 0) {
      $form_state->setValueForElement($element, '');
      return;
    }
  }
}
