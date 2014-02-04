<?php
/**
 * @package getcache
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'namespace',
        'desc' => 'An execution namespace that serves as a prefix for placeholders set by a specific instance of the getCache snippet.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    )
    ,array(
        'name' => 'element',
        'desc' => 'The name of a MODx Element that will be called by the getCache snippet instance.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    )
    ,array(
        'name' => 'elementClass',
        'desc' => 'The class of the MODx Element that will be called by the getCache snippet instance.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'modSnippet',
    )
    ,array(
        'name' => 'cacheKey',
        'desc' => 'The key identifying a cache handler for getCache to use.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    )
    ,array(
        'name' => 'cacheHandler',
        'desc' => 'The class of cache handler for getCache to use.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    )
    ,array(
        'name' => 'cacheExpires',
        'desc' => 'How many seconds the output of the Element is to be cached by getPage; 0 means indefinitely or until the cache items are purposely cleared.',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
    )
    ,array(
        'name' => 'cacheElementKey',
        'desc' => 'An optional explicit key to use to cache the output. Otherwise, the key is uniquely generated based on the Resource it is executing on, the properties being passed to the Element, and the $_REQUEST parameters passed when it executed.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    )
    ,array(
        'name' => 'toPlaceholder',
        'desc' => 'The name of a placeholder the Element being called is setting it\'s output into. This allows getCache to support Snippets that do not directly return their output.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    )
    ,array(
        'name' => 'cacheReset',
        'desc' => 'If true the current cache of the element is deleted and created new.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    )
);

return $properties;