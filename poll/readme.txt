***********************************************************************************
Simple Mini Poll class library (SimPoll)

Author: Ilir Fekaj
Contact: tebrino@hotmail.com
Date: January 9, 2004
Version: 1.0
Latest version: http://www.free-midi.org/scripts/
Demo: http://www.free-midi.org

This easy-to-use class library enables you to set up your own survey system in just few minutes.
Package includes dynamically generated form (with number of total votes), detailed result
page, view of old polls, administration page, check for repeated votes.
It's free for all purposes, just please don't claim you wrote it and if you like it
and find it useful please leave link on results page.
If you have any problems, please feel free to contact me.
Also if you use it, please send me the page URL.

INSTRUCTIONS:

1. Execute these queries on your database:

# Table structure for table `poll_check`

CREATE TABLE `poll_check` (
  `pollid` int(11) NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `time` varchar(14) NOT NULL default ''
) TYPE=MyISAM COMMENT='';

# --------------------------------------------------------

# Table structure for table `poll_data`

CREATE TABLE `poll_data` (
  `pollid` int(11) NOT NULL default '0',
  `polltext` varchar(50) NOT NULL default '',
  `votecount` int(11) NOT NULL default '0',
  `voteid` int(11) NOT NULL default '0',
  `status` varchar(6) default NULL
) TYPE=MyISAM COMMENT='';

# --------------------------------------------------------

# Table structure for table `poll_desc`

CREATE TABLE `poll_desc` (
  `pollid` int(11) NOT NULL default '0',
  `polltitle` varchar(100) NOT NULL default '',
  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `votecount` mediumint(9) NOT NULL default '0',
  `STATUS` varchar(6) default NULL,
  PRIMARY KEY (`pollid`)
) TYPE=MyISAM COMMENT='';

# --------------------------------------------------------


2. Set up database connection parameters
* Note that if you wish to skip steps bellow you can start example files included in this package

3. Paste this code on page where you wish poll to appear:

<?php

include_once ("includes/miniPoll.class.php");

$test = new miniPoll;

$test->pollForm();

?>
* Note that database connection must be set before calling code above


4. Paste this code on page where you wish poll results to appear:

<?php

include_once ("includes/miniPoll.class.php");

$test = new miniPoll;

if (isset($_GET['poll']) && is_numeric($_GET['pollid'])) {
	$pollid = $_GET['pollid'];

	if (isset($_GET['voteid']) && is_numeric($_GET['voteid'])) {
		$voteid = $_GET['voteid'];
		$test->processPoll($pollid, $voteid);
	}

}
if (isset($_GET['pollid'])) {
	$pollid = $_GET['pollid'];
	$test->pollResults($pollid);
}

?>
* Note that database connection must be set before calling code above
** Note that you can place poll form and poll results on same page


5. Paste this code on poll admin page:

<?php

include_once ("includes/miniPollAdmin.class.php");

$test = new miniPollAdmin;

$test->newPollForm();

if (isset($_GET['opt'])) {
	$opt = $_GET['opt'];
	$pollid = $_GET['pollid'];
	if ($opt == 'activate') {
		$test->activatePoll($pollid);
	}
	if ($opt == 'delete') {
		$test->deletePoll($pollid);
	}

}

echo "<br />";
if (isset($_GET['q'])) {
	$pollname = $_GET['pollname'];
	$q = $_GET['q'];
	$test->createPoll($pollname, $q);
}
$test->listPolls();

?>
* Note that database connection must be set before calling code above

6. Set up these parameters in miniPoll.class.php:
	$this->results_page = "test_poll_results.php"; - page where you display results
7. Set up these parameters in miniPollAdmin.class.php:
	$this->results_page = "test_poll_admin.php"; - name of admin page file
8. Optionally you can change layout of form and results page
9. Create new poll by starting admin page
10. Activate your poll by clicking ACTIVATE
11. Enjoy and send me your comments


***********************************************************************************