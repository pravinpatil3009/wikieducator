
<?php



class Graph {
	static function onParserInit( Parser $parser ) {
		global $wgOut;
		$wgOut->addModules( 'ext.graph' );
		$parser->setHook( 'graph', array( __CLASS__, 'graphRender' ) ); 
		return true;
	}
	static function graphRender( $input, array $args, Parser $parser, PPFrame $frame ) {
		$ret  = '<!DOCTYPE html>';
		$ret .=	'<meta charset="utf-8">';
		$ret .= '<div id="div1">';
		$ret .= '<script src="http://d3js.org/d3.v3.min.js"></script>';
		$ret .= '</div>';
		$ret .= '</html>';

		return $ret;

	}
}
