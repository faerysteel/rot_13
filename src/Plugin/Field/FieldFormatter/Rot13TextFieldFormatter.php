<?php

namespace Drupal\rot_13\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rot13text_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "rot13text_field_formatter",
 *   label = @Translation("ROT-13 Cypher"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *   }
 * )
 */
class Rot13TextFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $this->viewValue($item)];
    }

    return $elements;
  }

  /**
   * Convert one character under rot13.
   *
   * @param string $c
   *   Character to convert.
   * @return string
   *   The manipulated character.
   */
  protected function str_rot($char) {
    // In order for this to be a reciprocal cypher,
    // there needs to be exactly 26 characters.
    $letters = str_split('abcdefghijklmnopqrstuvwxyz');

    $c = $char;

    // Make sure it's an alpha character.
    if (ctype_alpha($char)) {
      // Check capitalization and save.
      $upper = ctype_upper($char);

      // Lowercase for our lookup.
      $c = strtolower($char);

      // Specifically one of the English alphabet.
      if (($key = array_search($c, $letters)) !== FALSE) {

        // Calculate alteration.
        $pos = ($key + 13) % 26;

        // Set the return value.
        $c = $letters[$pos];
      }

      // Back to uppercase if necessary.
      if ($upper) {
        $c = strtoupper($c);
      }
    }

    // This leaves non-English or non-alpha characters unchanged.
    return $c;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    $value = $item->value;
    $rot13_value = '';

    foreach(str_split($value) as $char) {
      $rot13_value .= $this->str_rot($char);
    }

    return nl2br(Html::escape($rot13_value));
  }

}
