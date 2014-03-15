<?php
$wgResourceModules['ext.graph'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Graph',
	'styles' => 'resources/graph.css',
	'scripts' => 'resources/graph.js',
);
$wgAutoloadClasses['Graph'] = $IP . '/extensions/Graph/graph_body.php';
$wgHooks['ParserFirstCallInit'][] = 'Graph::onParserInit';

