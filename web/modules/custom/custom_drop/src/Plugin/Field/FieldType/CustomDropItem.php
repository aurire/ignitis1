<?php

namespace Drupal\custom_drop\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'custom_drop_sch' field type.
 *
 * @FieldType(
 *   id = "custom_drop_sch",
 *   label = @Translation("Custom Dropdown field type"),
 *   module = "custom_drop",
 *   description = @Translation("Demonstrates a Custom Drop."),
 *   default_widget = "custom_drop_simple",
 *   default_formatter = "custom_drop_simple_text"
 * )
 */
class CustomDropItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('CustomDropValue'));

    return $properties;
  }

  /**
   * @return array
   */
  public static function defaultFieldSettings() {
    $fieldSettings = [
        'apikey' => 'ELO1xno7R6KF0XyegyzU',
        'maxitems' => 30,
        'fieldtohide' => 'field-miestas',
      ] + parent::defaultFieldSettings()
    ;

    return $fieldSettings;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state)
  {
    $element = [];
    $element['apikey'] = [
      '#title' => $this->t('Api KEY'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('apikey'),
    ];

    $element['maxitems'] = [
      '#title' => $this->t('Max number of items'),
      '#type' => 'number',
      '#default_value' => $this->getSetting('maxitems'),
    ];

    $element['fieldtohide'] = [
      '#title' => $this->t('Field to hide'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('fieldtohide'),
    ];

    return $element;
  }
}
