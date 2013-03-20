<?php
/**
 * Cache the output of any MODx Element using a configurable cacheHandler
 *
 * @author Jason Coward <jason@modx.com>
 * @version 1.0.1-dev
 * @since October 24, 2010
 * @package getcache
 */
$output = '';

if (empty($element) || empty($elementClass)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "getClass requires an element and elementClass property to be set");
    return $output;
}

$properties = $scriptProperties;
/* Unset these to prevent filters from being applied to the element being processed
 * See http://bugs.modx.com/issues/2609
 */
unset($properties['filter_commands']);
unset($properties['filter_modifiers']);

if (empty($cacheKey)) $cacheKey = $modx->getOption('cache_resource_key', null, 'resource');
if (empty($cacheHandler)) $cacheHandler = $modx->getOption('cache_resource_handler', null, $modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'));
if (!isset($cacheExpires)) $cacheExpires = (integer) $modx->getOption('cache_resource_expires', null, $modx->getOption(xPDO::OPT_CACHE_EXPIRES, null, 0));
if (empty($cacheElementKey)) $cacheElementKey = $modx->resource->getCacheKey() . '/' . md5($modx->toJSON($properties) . $modx->toJSON($modx->request->getParameters()));
$cacheOptions = array(
    xPDO::OPT_CACHE_KEY => $cacheKey,
    xPDO::OPT_CACHE_HANDLER => $cacheHandler,
    xPDO::OPT_CACHE_EXPIRES => $cacheExpires,
);

$cached = $modx->cacheManager->get($cacheElementKey, $cacheOptions);
if (!isset($cached['properties']) || !isset($cached['output'])) {
    $elementObj = $modx->getObject($elementClass, array('name' => $element));
    if ($elementObj) {
        $elementObj->setCacheable(false);
        if (!empty($properties['toPlaceholder'])) {
            $elementObj->process($properties);
            $output = $modx->getPlaceholder($properties['toPlaceholder']);
        } else {
            $output = $elementObj->process($properties);
        }

        if ($modx->getCacheManager()) {
            $cached = array('properties' => $properties, 'output' => $output);
            $modx->cacheManager->set($cacheElementKey, $cached, $cacheExpires, $cacheOptions);
        }
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR, "getCache could not find requested element {$element} of class {$elementClass}");
    }
} else {
    $properties = $cached['properties'];
    $output = $cached['output'];
}
$modx->setPlaceholders($properties, $properties['namespace']);
if (!empty($properties['toPlaceholder'])) {
    $modx->setPlaceholder($properties['toPlaceholder'], $output);
    $output = '';
}

return $output;
?>