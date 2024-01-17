<?php

namespace Drupal\custom_search\Plugin\search_api\processor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;
use Drupal\search_api\SearchApiException;

/**
 * @SearchApiProcessor(
 *   id = "custom_search_authors",
 *   label = @Translation("Authors"),
 *   description = @Translation("Provide Author names based on field_authors."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 *
 * Note that "locked = true" means the fields this processor makes available is
 * available on all data sources.
 */
class Authors extends ProcessorPluginBase {

  public const PLUGIN_ID = 'custom_search_authors';
  public const FIELD_NAME = 'field_authors';

  /**
   * {@inheritDoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Urban Authors'),
        'description' => $this->t('Provide Author names based on field_authors.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties[self::PLUGIN_ID] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * {@inheritDoc}
   */
  public function addFieldValues(ItemInterface $item) {
    // Only do this for entities.
    try {
      $entity = $item->getOriginalObject()->getValue();
    }
    catch (SearchApiException $e) {
      return;
    }
    if (!($entity instanceof EntityInterface)) {
      return;
    }

    // Add this property.
    $fields = $item->getFields();
    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($fields, NULL, self::PLUGIN_ID);

    $field_value = $this->getFieldValue($entity, self::FIELD_NAME);
    if ($field_value) {
      foreach ($fields as $field) {
        foreach ($field_value as $value) {
          $field->addValue($value);
        }
      }
    }

  }

  private function getFieldValue(EntityInterface $entity, string $field) {
    if (
      $entity->hasField($field) &&
      !$entity->{$field}->isEmpty()
    ) {
      // Name formatter.
      /** @var \Drupal\name\NameFormatter $name_formatter */
      $name_formatter = \Drupal::service('name.formatter');
      /** @var \Drupal\taxonomy\Entity\Term $term */
      try {
        $return = [];
        foreach ($entity->{$field} as $item) {
          if ($item->entity !== NULL) {
            $name = $item->entity->field_name->getValue()[0];
            $name_rendered = (string) $name_formatter->format($name);
            $return[] = $name_rendered;
          }
        }
        return $return;
      } catch (\Exception $ex) {
        // Do nothing.
      }
    }
    return NULL;
  }

}
