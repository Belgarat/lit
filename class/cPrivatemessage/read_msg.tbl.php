<div id="msg_show_bar">
<ul>
<li><a href="javascript:void(0);" onclick="javascript: $('id_msg_new_dst').value='<!-- DSTNAME -->';$('id_msg_new_obj').value='Re: <!-- OBJ -->';Effect.Appear('id_msg_write', { duration: 0.5 });">Rispondi</a></li>
<li>Archivia</li>
<li><a id="id_test" href="javascript: void(0);" onClick="javascript: delete_message('id_msg_list','src/ajax/PrivateMessage_delete.php','get','idmsg=<!-- IDMSG -->');">Elimina</a></li>
</ul>
</div>
<div id="id_msg_show">
<ul class="msg_show">
<li id="msg_obj"><b>Oggetto:</b><!-- OBJ --></li>
<li id="msg_body"><!-- BODY --></li>
<li id="msg_sign"><!-- SIGN --></li>
</ul>
</div>
