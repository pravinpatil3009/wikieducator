<?php
$wgResourceModules['ext.poll'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Poll',
	'styles' => 'resources/poll.css',
	'scripts' => 'resources/poll.js',
);
$wgAutoloadClasses['Poll'] = $IP . '/extensions/Poll/poll_body.php';
$wgHooks['ParserFirstCallInit'][] = 'Poll::onParserInit';
$wgAjaxExportList[] = 'Poll::submitVote';

