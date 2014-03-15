<?php
$wgResourceModules['ext.mypage'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Mypage',
	'styles' => 'resources/mypage.css'
);
$wgAutoloadClasses['Mypage'] = $IP . '/extensions/Mypage/mypage_body.php';
$wgHooks['ParserFirstCallInit'][] = 'Mypage::onParserInit';

