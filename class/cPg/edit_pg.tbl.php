<div id="id_edit_pg">
	<div style="margin: auto; position: absolute; width:'150%'; height:'150%'; background: transparent;z-index:5000;" id="id_working"></div>
	<div id="id_edit_pg_header"></div>
	<div id="id_edit_pg_body">
		<ul class="pg_menu">
			<li><a href="javascript:void(0);" onclick="javascript: pg_hide_div($$('div.div_edit_pg'));$('id_edit_pg_general').appear();">Generale</a></li>
			<li style="display:<!-- master_permission -->"><a href="javascript:void(0);" onclick="javascript: pg_hide_div($$('div.div_edit_pg'));$('id_edit_pg_master').appear();">Master</a></li>
			<li style="display:<!-- master_permission -->"><a href="javascript:void(0);" onclick="javascript: pg_hide_div($$('div.div_edit_pg'));$('id_edit_pg_note').appear();">Note</a></li>
			<li><a href="javascript:void(0);" onclick="javascript: $('id_edit_pg').hide();udiv = new Ajax.Updater('id_PluginBoard','src/ajax/cPg_pg_show.php', { method: 'post', parameters: 'id=<!-- LI_ID -->'});">Chiudi</a></li>
			<hr>
		</ul>
		<div class="div_edit_pg" id="id_edit_pg_general">			
			<form name="form_edit_pg_base" id="id_form_edit_pg_base">
			<input class="edit_pg_button" type="button" onclick="javascript: new Ajax.Updater('id_PluginBoard','src/ajax/cPg_edit_pg.php',{method: 'post', parameters: $('id_form_edit_pg_base').serialize(), onCreate:AjaxWorkingOn });" name="btn_edit_pg_base" value="Salva"><br />
			<!-- id --><br />
			<label style="color:yellow;font-weight:bold;" class="edit_pg_label"><!-- messaggio --></label><br />
			<label class="edit_pg_label">Nome pg: </label><!-- username --><br />
			<label class="edit_pg_label">Photo: </label><!-- photo --><br />
			<label class="edit_pg_label">Et&agrave;: </label><!-- eta --><br />
			<label class="edit_pg_label">Sesso: </label><select name="_Sesso"><!-- sesso --></select><br />
			<label class="edit_pg_label">Descrizione: </label><textarea name="_Descrizione" cols="70" rows="5"><!-- descrizione --></textarea><br />
			<label class="edit_pg_label">Background: </label><textarea name="_bg" cols="70" rows="10"><!-- bg --></textarea><br />
			<label class="edit_pg_label">Razza: </label><select name="_Razza"><!-- race --></select><br />
			<label class="edit_pg_label">Allineamento: </label><select name="_Allineamento"><!-- align --></select><br />
			<label class="edit_pg_label">Multiclasse: </label><!-- biclasse --><br />
			<label class="edit_pg_label">Classe primaria: </label><select name="_Classe1"><!-- class1 --></select><br />
			<label class="edit_pg_label">Classe secondaria: </label><select name="_Classe2"><!-- class2 --></select><br />
			<label class="edit_pg_label">Livello: </label><!-- lvl_primario --> / <!-- lvl_secondario --><br />
			<label class="edit_pg_label">Esperienza: </label><!-- xp_primario --> / <!-- xp_secondario --><br />
			<label class="edit_pg_label">Nascondi classe: </label><!-- hide_class --> Esperienza: <!-- hide_xp --> Livello: <!-- hide_level --> Background: <!-- hide_bg --><br />			
			</form>
		</div>
		<div class="div_edit_pg" id="id_edit_pg_master" style="display:none;">			
			<form name="form_edit_pg_master" id="id_form_edit_pg_master">
			<!-- id_pg_master --><br />
			<label class="edit_pg_label">Avventura: </label><select name="_IdAvv"><!-- adventures_list --></select><br />
			<label class="edit_pg_label">Aggancia personaggio: </label><!-- id_attach_master --><br /><br />
			<input type="button" onclick="javascript: divpg = new Ajax.Updater('id_PluginBoard','src/ajax/cPg_edit_pg.php',{method: 'post', parameters: $('id_form_edit_pg_master').serialize() });" name="btn_edit_pg_master" value="Salva"><br />
			</form>
		</div>
		<div class="div_edit_pg" id="id_edit_pg_note" style="display:none;">
			<label class="edit_pg_label">Note: </label><textarea name="_Note" cols="70" rows="10"><!-- note --></textarea><br />
		</div>
	</div>
	<div id="id_edit_pg_bottom"></div>
</div>
