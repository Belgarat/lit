<div id="id_sheet_manager_body">
        <ul class="pg_menu">
                <li><a href="javascript:void(0);" onclick="javascript: $$('.pg_menu a').each(function(element){element.addClassName('reset');});this.style.color='white';master_tab_show('manager_general');">Pg master</a></li>
                <li><a href="javascript:void(0);" onclick="javascript: $$('.pg_menu a').each(function(element){element.addClassName('reset');});this.style.color='white';master_tab_show('manager_search');">Ricerca</a></li>
                <li><a href="javascript:void(0);" onclick="javascript: $$('.pg_menu a').each(function(element){element.addClassName('reset');});this.style.color='white';master_tab_show('manager_new_pg');">Creazione PG</a></li>
                <li>
                <a href="javascript:void(0);" onclick="javascript: Effect.Fade('id_sheet_manager');">Chiudi</a></li>
                <hr>
        </ul>
        <div id="id_pg_manager_pg_master">
                <!-- PG_MASTER_LIST -->
        </div>
        <div id="id_pg_manager_search">
            <form id="id_form_search" name="frm_search">
                <label>Cerca personaggi: </label>
                <input type='text' id='id_txt_search' name='txt_search'>
                <input type='button' id='id_btn_search' name='btn_search' value="Cerca">
                <!-- PG_MASTER_SEARCH -->
            </form>
        </div>                    
        <div id="id_pg_manager_new" style="display:none;">
                <form id="id_form_new_pg" name="frm_new_pg">
                <p><label>Master: </label>
                <select name="txt_master_id">
                        <!-- MASTERS_LIST -->
                </select></p>
                <p><label>Avventura: </label>
                <select name="txt_adventure_id">
                        <!-- ADVENTURES_LIST -->
                </select></p>
                <p><label>Utente: </label>
                <select name="txt_user_id">
                        <!-- USERS_LIST -->
                </select></p>
                <p><label>Nome personaggio: </label>
                <input type="text" name="txt_pg_name"></p>
                <p><label>Genere: </label>
                <select name="txt_pg_gender">
                        <option value="M">Maschio</option>
                        <option value="F">Femmina</option>
                </select></p>
                <p><label>Et&agrave: </label>
                <input type="text" name="txt_pg_age"></p>
                <p><label>Descrizione fisica: </label>
                <textarea rows="15" cols="90" name="txt_pg_descr"></textarea></p>
                <p><input type="button" value="Crea" onClick="javascript:$('id_pg_manager_message').show(); var form_new_pg = new Ajax.Updater('id_pg_manager_message', 'src/ajax/cPg_pg_manage_create.php',{ method:'post', parameters: Form.serialize($('id_form_new_pg')) });">
                <input type="button" value="Cancel" onClick="javascript: $('id_form_new_pg').reset();"></p>
                </form>
        </div>
        <div id="id_pg_manager_message" style="display: none;">
        </div>
</div>
<div id="id_sheet_manager_bottom"></div>

