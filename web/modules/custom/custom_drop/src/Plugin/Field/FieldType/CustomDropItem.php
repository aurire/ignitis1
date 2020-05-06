<?php

namespace Drupal\custom_drop\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'custom_drop_sch' field type.
 *
 * @FieldType(
 *   id = "custom_drop_sch",
 *   label = @Translation("Custom Drop Label"),
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
}
