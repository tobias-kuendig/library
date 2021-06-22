<?php namespace October\Rain\Element\Form;

use IteratorAggregate;
use ArrayIterator;

/**
 * FieldsetDefinition
 *
 * @package october\element
 * @author Alexey Bobkov, Samuel Georges
 */
class FieldsetDefinition implements IteratorAggregate
{
    /**
     * @var string defaultTab is default tab label to use when none is specified
     */
    public $defaultTab = 'Misc';

    /**
     * @var boolean suppressTabs if set to TRUE, fields will not be displayed in tabs
     */
    public $suppressTabs = false;

    /**
     * @var array Raw field configuration.
     */
    public $config;

    /**
     * @var array fields is a collection of panes fields to these tabs
     */
    protected $fields = [];

    /**
     * evalConfig from an array and apply them to the object
     */
    protected function evalConfig(array $config): void
    {
        if (array_key_exists('defaultTab', $config)) {
            $this->defaultTab = $config['defaultTab'];
        }

        if (array_key_exists('suppressTabs', $config)) {
            $this->suppressTabs = $config['suppressTabs'];
        }
    }

    /**
     * useConfig
     */
    public function useConfig(array $config): FieldsetDefinition
    {
        $this->config = $config;

        $this->evalConfig($config);

        return $this;
    }

    /**
     * addField to the collection of tabs
     */
    public function addField($name, FieldDefinition $field)
    {
        $this->fields[$name] = $field;
    }

    /**
     * removeField from all tabs by name
     * @param string $name
     * @return boolean
     */
    public function removeField($name)
    {
        if (isset($this->fields[$name])) {
            unset($this->fields[$name]);
            return true;
        }

        return false;
    }

    /**
     * hasFields returns true if any fields have been registered for these tabs
     * @return bool
     */
    public function hasFields()
    {
        return count($this->fields) > 0;
    }

    /**
     * getFields returns an array of the registered fields, includes tabs in format
     * array[tab][field]
     * @return array
     */
    public function getFields()
    {
        $fieldsTabbed = [];

        foreach ($this->fields as $name => $field) {
            $tabName = $field->tab ?: $this->defaultTab;
            $fieldsTabbed[$tabName][$name] = $field;
        }

        return $fieldsTabbed;
    }

    /**
     * getAllFields returns an array of the registered fields, without tabs
     * @return array
     */
    public function getAllFields()
    {
        return $this->fields;
    }

    /**
     * getIterator gets an iterator for the items
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator(
            $this->suppressTabs
                ? $this->getAllFields()
                : $this->getFields()
        );
    }
}
