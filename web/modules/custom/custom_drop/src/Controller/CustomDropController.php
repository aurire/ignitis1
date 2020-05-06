<?php
/**
 * @file
 * Contains \Drupal\custom_drop\Controller\CustomDropController.
 */
namespace Drupal\custom_drop\Controller;
class CustomDropController {
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello, Worldyti!'),
    );
  }
}
