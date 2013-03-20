<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$componentPath = $modx->getOption('getcache.core_path', null, $modx->getOption('core_path').'components/getcache/');

$modx->lexicon->load('getcache:default');

/* handle request */
$path = $modx->getOption('processorsPath', null, $componentPath . 'processors/');
$modx->request->handleRequest(array(
   'processors_path' => $path,
   'location' => '',
));
