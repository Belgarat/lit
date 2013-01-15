<?php
class miniPollAdmin {

	var $results_page;
	var $max_questions;
	var $maxpoll;

	function miniPollAdmin() {
		$this->results_page = "test_poll_admin.php"; // name of file where this class is called
		$this->max_questions = 10;
	
	}
	
	function getLastPollId() {
		$sql = @mysql_query ("SELECT MAX(pollid) AS maxpoll FROM poll_desc");
		$row = @mysql_fetch_object($sql);
		$this->maxpoll = $row->maxpoll + 1;
		return $this->maxpoll;
	}
	
	function listPolls() {
		$sql = @mysql_query ("SELECT pollid, polltitle, status, timestamp FROM poll_desc ORDER BY timestamp DESC");
		echo "<table border=\"1\">
		<tr><td>id</td>
		<td>Poll title</td>
		<td>Date</td>
		<td>Status</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td></tr>";
		while ($row = @mysql_fetch_object($sql)) {
			echo "\t<tr><td>$row->pollid</td>
			<td>$row->polltitle</td>
			<td>$row->timestamp</td>
			<td>" . strtoupper($row->status) . "</td>
			<td><a href=\"$this->results_page?opt=activate&amp;pollid=$row->pollid\">activate</a></td>
			<td><a href=\"$this->results_page?opt=delete&amp;pollid=$row->pollid\">delete</a></td></tr>\r\n";
		}
		echo "</table>\r\n";
	}
	
	function newPollForm() {
		echo "<fieldset>
		<legend>Create new poll</legend>
		<form method=\"get\" name=\"form1\" method=\"post\" action=\"$this->results_page\"><br />\r\n";
		echo "Poll name: <input name=\"pollname\" type=\"text\" id=\"pollname\" class=\"formlook\" /><br />\r\n";
		for ($i = 1; $i <= $this->max_questions; $i ++) { 
		    echo "Question $i: <input name=\"q[$i]\" type=\"text\" id=\"q[$i]\" class=\"formlook\" /><br />\r\n"; 
		} 
		echo "<input type=\"submit\" name=\"Submit\" value=\"Create\" class=\"formlook\" /><br />\r\n</form><br />\r\n</fieldset>\r\n";
	}
	
	function createPoll($pollname, $q) {
		$this->getLastPollId();
		$insert_title = @mysql_query ("INSERT INTO poll_desc(pollid, polltitle, timestamp) VALUES ('$this->maxpoll', '$pollname', now())");
		for ($i = 1; $i <= count($q); $i ++) {
			$insert_questions = @mysql_query ("INSERT INTO poll_data(pollid, polltext, voteid) VALUES ('$this->maxpoll', '$q[$i]', '$i')");
		}
	
	}
	
	function activatePoll($pollid) {
		$deactivate_poll = @mysql_query ("UPDATE poll_desc SET status = '' WHERE status = 'active'");
		$activate_poll = @mysql_query ("UPDATE poll_desc SET status = 'active' WHERE pollid = '$pollid'");
		if (mysql_affected_rows() > 0) {
			echo "Poll successfully activated<br />\r\n";
		}
	}
	
	function deletePoll($pollid) {
		$delete_poll = @mysql_query ("DELETE FROM poll_desc WHERE pollid = '$pollid'");
		if (mysql_affected_rows() > 0) {
			$delete_poll_questions = @mysql_query ("DELETE FROM poll_data WHERE pollid = '$pollid'");
			echo "Poll successfully deleted.<br />\r\n";
		}
	}

}
?>