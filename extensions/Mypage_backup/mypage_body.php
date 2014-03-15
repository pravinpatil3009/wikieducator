
<?php



class Mypage {
	static function onParserInit( Parser $parser ) {
		global $wgOut;
		$wgOut->addModules( 'ext.mypage' );
		$parser->setHook( 'mypage', array( __CLASS__, 'mypageRender' ) ); 
		return true;
	}
	static function mypageRender( $input, array $args, Parser $parser, PPFrame $frame ) {
		$attr = array();
		foreach( $args as $name => $value )
			$attr[] = htmlspecialchars( $name ) .','.htmlspecialchars( $value );
		$gid = explode(',',$attr[0]);
		$ggid = $gid[1];
		$tid = explode('+',$attr[1]);
		$ttid = $tid[1];
		$gappkey = 'AIzaSyDrilWYAwLtWt_iwylzUGOAdCVpbb46rBo';
		$streams = json_decode(file_get_contents('https://www.googleapis.com/plus/v1/people/' . $ggid . '/activities/public?key='. $gappkey));
		$streams1 = json_decode(file_get_contents('https://www.googleapis.com/plus/v1/people/'. $ggid . '?'.$gappkey));
		$ret = '<div id="columns">';
		foreach($streams->items as $item){
			$ret .= "<figure>";
			foreach($item->object->attachments as $attachment){
				$img = $attachment->image->url;
				$ret .= "<img src='$img' alt>";
			}
			$ret .= "<figcaption>".$item->title."<br />".$item->object->content."</figcaption>";
			$ret .="</figure>";			
		}
		$ret .= '</div>';
		$ret .= '<div id="columns1">';
		$ret .= "<a class='twitter-timeline' href='https://twitter.com/$ttid' data-widget-id='444787449160429569'>Tweets by @PravinP14951122</a>";
		$http = "http";
		$https = "https";
		$ret .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>";
		$ret .= '</div>';	
		return $ret;
		

		
	}
}
