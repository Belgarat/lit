<SCRIPT>
	var test = new Ajax.PeriodicalUpdater('main_no_news', '../src/ajax/cNoticeboard_show.php', {method: 'post', encoding: 'UTF-8',frequency:20, decay:2, onCreate: function(resp) {resp.transport.overrideMimeType("text/html; charset=UTF-8");}});
</SCRIPT>

<h3 style="text-align:left;">NOTICEBOARD</h3>
<div id="main_noticeboard">
<div id="Notice5"><p class="notice_title_top"><!-- BOARD5_TITLE --></p><!-- BOARD5 --></div>
<div id="Notice1"><ul class="notice"><li class="notice_title"><!-- BOARD1_TITLE --></li><li class="content_board"><!-- BOARD1 --></li></ul></div>
<div id="Notice2"><ul class="notice"><li class="notice_title"><!-- BOARD2_TITLE --></li><li class="content_board"><!-- BOARD2 --></li></ul></div>
<div id="Notice3"><ul class="notice"><li class="notice_title"><!-- BOARD3_TITLE --></li><li class="content_board"><!-- BOARD3 --></li></ul></div>
<div id="Notice4"><ul class="notice"><li class="notice_title"><!-- BOARD4_TITLE --></li><li class="content_board"><!-- BOARD4 --></li></ul></div>
<div id="Notice6"><p class="notice_title_bottom"><!-- BOARD6_TITLE --></p><!-- BOARD6 --></div>
</div>
