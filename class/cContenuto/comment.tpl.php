<style>
#DivComment{
	position: absolute;
	top:100px;
	left:40px;
	background-color:#3b1251; 
	border-style:solid; 
	border-color: #b19bcf; 
	border-width:1px; 
	width:490px;
	height:220px; 
	padding-left:1.5em;
	padding-right:1.5em;
	padding-bottom:1.5em;
}
</style>
<div id="DivComment" style="display:none;">
<ul class="option_bar"><li class="option_bar"><a href="javascript:void(0);" onclick="javascript: Effect.Fade('DivComment', { duration: 0.5 });"><img src="img/pbf/close_swords.png"></a></li></ul>
<p class="form">
<form method="post" id="FormInsertComment" action="<!-- ACTION -->">

<input id="Form_input_idparent" type="hidden" name="txtParent" size="58" value="<!-- IDPARENT -->" readonly>

<label id="Form_label_title">Title: </label><br>
<input id="Form_input_title" type="text" name="txtTitle" size="58" value="<!-- TITLE -->">

<label id="Form_label_titolo">Comment: </label><br>
<textarea onkeypress="javascript: Form.Element.enable('cmd_Submit');" id="Form_input_comment" name="txtComment" cols="66" ><!-- COMMENT --></textarea>

<label id="Form_label_sign">Signature: </label><br>
<input id="Form_input_sign" type="text" name="txtSign" size="58" value="<!-- SIGN -->">

<p align="right"><input onclick="javascript: <!-- JS_SUBMIT -->" id="cmd_Submit" type="button" value="Save" name="cmd_submit" disabled></p>

</form>
</p>
</div>
