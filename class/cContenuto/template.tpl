<style>
#DivUpload{
	position: absolute;
	top:100px;
	left:40px;
	background-color:#3b1251; 
	border-style:solid; 
	border-color: #b19bcf; 
	border-width:1px; 
	width:490px;
	height:160px; 
	padding-left:1.5em;
	padding-right:1.5em;
	padding-bottom:1.5em;
}
</style>
<div id="DivUpload" style="display:none;">
<ul class="option_bar"><li class="option_bar"><a href="javascript:void(0);" onclick="javascript: Effect.Fade('DivUpload', { duration: 0.5 });"><img src="img/pbf/close_swords.png"></a></li></ul>
<p class="form">
<form method="post" id="FormUpload" action="<!-- ACTION -->" enctype="multipart/form-data">

<label id="Form_label_titolo">Descrizione immagine: </label><br>

<input id="Form_input_titolo" type="text" name="txtTitolo" size="58" value="<!-- TITLE -->">

<input id="Form_input_id" type="hidden" name="txtId" value="<!-- ID -->">

<input id="Form_input_percorso" type="hidden" name="txtPercorso" size="58" value="<!-- PERCORSO -->"><br>

<input id="Form_input_file" type="file" name="file" size="48"><br />

<input onclick="javascript: Form.Element.enable('cmd_Upload');" id="Form_input_type_imm" type="radio" name="type" value="2" /> Immagine

<input onclick="javascript: Form.Element.enable('cmd_Upload');" id="Form_input_type_att" type="radio" name="type" value="3" /> Allegato

<p align="right"><input id="cmd_Upload" type="submit" value="Upload" name="cmd_upload" disabled></p>
</form>
</p>
</div>
