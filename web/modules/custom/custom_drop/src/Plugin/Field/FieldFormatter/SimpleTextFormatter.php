<?php

namespace Drupal\custom_drop\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'custom_drop_simple_text' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_drop_simple_text",
 *   module = "custom_drop",
 *   label = @Translation("Custom drop text-based formatter"),
 *   field_types = {
 *     "custom_drop_sch"
 *   }
 * )
 */
class SimpleTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('@code', ['@code' => $item->value]),
      ];
    }

    return $elements;
  }

}
