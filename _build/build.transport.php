<?php
/**
 * getCache build script
 *
 * @package getcache
 * @subpackage build
 * @version 1.0.0-pl
 * @author Jason Coward <jason@modx.com>
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$root = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'lexicon' => $root . '_build/lexicon/',
    'properties' => $root . '_build/properties/',
    'source_core' => $root . 'core/components/getcache',
);
unset($root);

/* package defines */
define('PKG_NAME','getCache');
define('PKG_VERSION','1.0.0');
define('PKG_RELEASE','pl');
define('PKG_LNAME',strtolower(PKG_NAME));

// override with your own defines here (see build.config.sample.php)
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_LNAME,PKG_VERSION,PKG_RELEASE);
//$builder->registerNamespace(PKG_LNAME,false,true);

/* create snippet object */
$snippet= $modx->newObject('modSnippet');
$snippet->set('name',PKG_NAME);
$snippet->set('description', '<b>' . PKG_VERSION . '-' . PKG_RELEASE . '</b> A generic caching snippet for caching the output of any MODx Element using a configurable cache handler.');
$snippet->set('category', 0);
$snippet->set('snippet', file_get_contents($sources['source_core'] . '/snippet.getcache.php'));
$properties = include $sources['properties'].'properties.getcache.php';
$snippet->setProperties($properties);

/* package in snippet */
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
);
$vehicle = $builder->createVehicle($snippet, $attributes);
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$builder->putVehicle($vehicle);
unset($properties,$snippet,$vehicle);

/* load lexicon strings */
//$builder->buildLexicon($sources['lexicon']);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['source_core'] . '/docs/license.txt'),
    'readme' => file_get_contents($sources['source_core'] . '/docs/readme.txt'),
    'changelog' => file_get_contents($sources['source_core'] . '/docs/changelog.txt'),
));

/* zip up the package */
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO, "Package Built Successfully.");
$modx->log(modX::LOG_LEVEL_INFO, "Execution time: {$totalTime}");
exit();
