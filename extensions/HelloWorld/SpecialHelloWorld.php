<?php
/**
 * This file defines the SpecialHelloWorld class which handles the functionality for the 
 * HelloWorld special page (Special:HelloWorld).
 *
 * @file
 * @ingroup Extensions
 * @author Ryan Kaldari
 */
class SpecialHelloWorld extends SpecialPage {
	
	/**
	 * Initialize the special page.
	 * In this case, we're just calling the parent class's constructor with the name of the special
	 * page and no extra parameters.
	 */
	public function __construct() {
		parent::__construct( 'HelloWorld' );
	}
	
	/**
	 * Define what happens when the special page is loaded by the user.
	 * @param $sub string The subpage, if any
	 */
	public function execute( $sub ) {
		global $wgOut;
		$wgResourceModules['ext.HelloWorld'] = array(
			'scripts' => 'ext.HelloWorld.js',
			'dependencies' => array(
				'jquery.tablesorter',
			),
			'position' => 'bottom',
			'localBasePath' => dirname(__FILE__). '/modules',
			'remoteExtPath' => 'HelloWorld/modules'
		);
		$wgOut->addModules( 'ext.HelloWorld' );

		$google_plus_id = '+JimTittsler';
		$appKey = 'AIzaSyDrilWYAwLtWt_iwylzUGOAdCVpbb46rBo';
		$streams = json_decode(file_get_contents('https://www.googleapis.com/plus/v1/people/' . $google_plus_id . '/activities/public?key='. $appKey));
		$wgOut->addHTML('<table class="tablesorter" id="mytable" border="1">');
		$wgOut->addHTML('<tr><th>Link</th><th>Title</th></tr>');
		$i=0;
		foreach ($streams->items as $item) {
			if($i==5) break;
			//			$abc = $item->title;
/*			echo $item->title .'<br />'.
				date('F jS Y @ H:i:s',strtotime($item->published)) .'<br />'.
				$item->object->content .
'<br /><p>---------EOF----------</p>';*/
			$abc = $item->url;
			$abd = $item->title;
			$wgOut->addHTML('<tr>');
			$wgOut->addHTML('<td>');
			$wgOut->addWikitext($abc);
			$wgOut->addHTML('</td>');
			$wgOut->addHTML('<td>');
			$wgOut->addWikitext($abd);
			$wgOut->addHTML('</td>');
			$wgOut->addHTML('</tr>');
			$i++;
		}
		/**
		 *  * Move the [edit] link from the opposite edge to the side of the heading title itself
		 *   *
		 *    * @source: http://www.mediawiki.org/wiki/Snippets/Editsection_inline
		 *     * @rev: 4
		 *      */


		$wgOut->addHTML('</table>');
		// Output the title of the page
		$wgOut->setPageTitle( wfMessage( 'helloworld' ) );
		// Output a hello world message as the content of the page
		//		$wgOut->addWikiMsg( 'helloworld-hello' );
		//		$wgOut->addWikiMsg( $stream->items);
	}

}
