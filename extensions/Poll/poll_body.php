
<?php



class Poll {
	static function onParserInit( Parser $parser ) {
		global $wgOut;
		$wgOut->addModules( 'ext.poll' );
		$parser->setHook( 'poll', array( __CLASS__, 'pollRender' ) ); 
		return true;
	}
	static function pollRender( $input, array $args, Parser $parser, PPFrame $frame ) {
		global $wgUser;
		// Database
		$dbr = wfGetDB( DB_SLAVE );
		// // User
		$user = $wgUser->getName();
		// // Get data
		$rs = $dbr->select(
			'poll',
			'poll_date',
			array('poll_user' => $user),
			__METHOD__,
			array( 'ORDER BY' => 'poll_date DESC' )
		);
		if ( $rs->numRows() == 0 ) {
			$recentDate = '(unknown)';
		} else {
			$row = $rs->fetchRow();
			$recentDate = $row['poll_date'];
		}
		$ret .= '<table class="wtable bordered">';
		$ret .= '<tr><th>Please fill the form</th>';
		$ret .= '<th></th></tr>';
		$ret .= '<tr>';
		$ret .= '<td>Feedback</td>';
		$ret .= '<td><input id="inp001" type="text" /></td>';
		$ret .= '</tr>';
		$ret .= '<tr>';
		$ret .= '<td>Previous feedback given on:</td>';
		$ret .= "<td id='dat001'>$recentDate</td>";
		$ret .= '</tr>';
//		$ret .= '<tr>';
//		$ret .= '<td>Clear history</td>';
//		$ret .= '<td><input id="chk001" type="checkbox" /></td>';
//		$ret .= '</tr>';
		$ret .= '<tr>';
		$ret .= '<td>';
		$ret .= '<input id="btn001" type="submit" value="Submit"/>';
		$ret .= '</td>';
		$ret .= '</tr>';
		$ret .= '</table>';

		$rd = $dbr->select(
			'poll',
			array('poll_user','poll_feedback','poll_date'),
			array('poll_user' => $user),
			__METHOD__,
			array('ORDER BY' => 'poll_date DESC')
		);
		
		$ret .= '<table class="wtable1 bordered"> ';
		$ret .= '<tr><th>Your Feedback</th>';
		$ret .= '<th>Timestamp</th></tr>';
		$ret .= '<tbody id="myTBODY">';
		foreach ($rd as $item) {
			$ret .= '<tr>';
			$ret .= "<td>$item->poll_feedback</td>";
			$ret .= "<td>$item->poll_date</td>";
			$ret .= '</tr>';
		}
		$ret .= '<tbody></table>';



		return $ret;
	}
	public static function submitVote($feedback,$clear){
		global $wgUser;
		wfErrorLog( "submitVote() called text=" . $feedback . " clear=" . $clear . "\n",
			'/tmp/poll001.log' );
		$dbw = wfGetDB( DB_MASTER );
		$user = $wgUser->getName();

		if($clear== 'true'){
			$deleteQuery = $dbw->delete(
				'poll',
				array(
					'poll_user' => $user
					)
				);
			$dbw->commit();
			$ret = '(none)';
		}
		else{
		$insertQuery = $dbw->insert(
			'poll',
			array(
				'poll_user' => $user,
				'poll_feedback' => $feedback,
				'poll_clear' => ($clear== 'true' ? 1 : 0 ),
				'poll_date' => wfTimestampNow()
				)
			);
		$dbw->commit();
		$ret = '{"time":"'.wfTimestamp( TS_DB, time() ).'","feedback":"'.$feedback.'"}';		
		
		}
		return $ret;

	}
}
