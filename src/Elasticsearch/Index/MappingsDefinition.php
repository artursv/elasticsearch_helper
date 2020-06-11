<?php

namespace Drupal\elasticsearch_helper\Elasticsearch\Index;

use Drupal\elasticsearch_helper\Elasticsearch\DefinitionBase;
use Drupal\elasticsearch_helper\Elasticsearch\ObjectTrait;

/**
 * Elasticsearch index mappings definition.
 *
 * Mappings definition instance consists only of field definitions.
 *
 * Example:
 *
 *   Elasticsearch index plugins must return mapping definition using
 *   getIndexMappings() method.
 *
 *   To create a mapping definition, use the following code:
 *
 *     $mapping_definition = MappingsDefinition::create()
 *       ->addProperty('id', FieldDefinition::create('integer'))
 *       ->addProperty('uuid', FieldDefinition::create('keyword'))
 *       ->addProperty('entity_type', FieldDefinition::create('keyword'))
 *       ->addProperty('label', FieldDefinition::create('text'));
 *
 *   Optionally, you can pass an array of properties:
 *
 *     $properties = [
 *       'id' => FieldDefinition::create('integer'),
 *       'uuid' => FieldDefinition::create('keyword'),
 *       'entity_type' => FieldDefinition::create('keyword'),
 *       'label' => FieldDefinition::create('text'),
 *     ];
 *
 *     $mapping_definition = MappingsDefinition::create()
 *       ->addProperties($properties);
 */
class MappingsDefinition extends DefinitionBase {

  use ObjectTrait;

  /**
   * Mapping properties.
   *
   * @var \Drupal\elasticsearch_helper\Elasticsearch\Index\FieldDefinition[]
   */
  protected $properties = [];

  /**
   * Adds a property.
   *
   * Properties are fields of an object.
   *
   * @param $field_name
   * @param \Drupal\elasticsearch_helper\Elasticsearch\Index\FieldDefinition $field
   *
   * @return self
   */
  public function addProperty($field_name, FieldDefinition $field) {
    $this->properties[$field_name] = $field;

    return $this;
  }

  /**
   * Adds properties to the mapping.
   *
   * @param \Drupal\elasticsearch_helper\Elasticsearch\Index\FieldDefinition[] $properties
   *
   * @return self
   */
  public function addProperties(array $properties) {
    $this->properties = array_merge($this->properties, $properties);

    return $this;
  }

  /**
   * Returns properties.
   *
   * @return \Drupal\elasticsearch_helper\Elasticsearch\Index\FieldDefinition[]
   */
  public function getProperties() {
    return $this->properties;
  }

  /**
   * Returns mapping definition as an array.
   *
   * @return array
   */
  public function toArray() {
    $result = $this->getOptions();

    // Add properties.
    foreach ($this->getProperties() as $field_name => $property) {
      $result['properties'][$field_name] = $property->toArray();
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \InvalidArgumentException
   */
  protected function validateOptions(array $options = []) {
    if (isset($options['properties'])) {
      throw new \InvalidArgumentException(t('Properties should be added using @method method.', [
        '@method' => 'addProperty()',
      ]));
    }
  }

}