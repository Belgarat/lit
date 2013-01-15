<div id="Privatemessage" style="left:-200;display:none;">
<ul id="id_action_bar">
<li><a href="javascript:void(0);" onclick="javascript: Effect.Appear('id_msg_write', { duration: 0.5 });">Scrivi</a></li>
<li>Rubrica</li>
<li>Opzioni</li>
<li><a href="javascript:void(0);" onclick="javascript: Effect.Fade('Privatemessage', { duration: 0.5 });">Chiudi</a></li>
</ul>
<ul class="msg_folder">
<li class="msg_folder_title">Cartelle</li>
<!-- FOLDER -->
</ul>


<div id="id_msg_list">
<ul class="msg_list">
<li class="title">Messaggi</li>
<!-- LIST -->
</ul>
</div>

<div id="id_msg_show_area">
	<div id="msg_show_bar">
	</div>

	<div id="id_msg_show">
	<ul class="msg_show">
	<li id="msg_obj"><b>Oggetto:</b></li>
	<li id="msg_body"></li>
	<li id="msg_sign"></li>
	</ul>
	</div>
</div>

<form style="display:none;" id="id_msg_write">
<div id="id_msg_new_box" style="display:none;">
</div>
<label>Destinatario</label><br><input name="msg_new_dst" id="id_msg_new_dst" type="text" value=""><br>
<label>Oggetto</label><br><input name="msg_new_obj" id="id_msg_new_obj" type="text" value=""><br>
<label>Messaggio</label><br><textarea name="msg_new_body" rows="7" cols="50" id="id_msg_new_body"></textarea><br>
<label>Firma</label><br><input name="msg_new_sign" id="id_msg_new_sign" type="text" value=""><br>
<input type="button" value="Send" onClick="javascript:$('id_msg_new_box').show(); var form_msg_new = new Ajax.Updater('id_msg_new_box', 'src/ajax/PrivateMessage_send.php',{ method:'post', parameters: Form.serialize($('id_msg_write')) });">
<input type="button" value="Cancel" onClick="javascript: $('id_msg_write').reset(); $('id_msg_write').fade({duration: 0.5});">
</form>

</div>
