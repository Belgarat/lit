<script type='text/javascript'>

function pg_hide_div(aTab){
	aTab.each(function(div){div.style.display='none';});
}

function pg_tab_show(key){
	switch(key){
		case 'background':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='block';
			$('id_pg_notes').style.display='none';
			break;
		case 'general':
			$('id_pg_left').style.display='block';
			$('id_pg_center').style.display='block';
			$('id_pg_right').style.display='block';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='none';
			break;
		case 'skills':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='block';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='none';
			break;
		case 'talents':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='block';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='none';
			break;
		case 'language':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='block';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='none';
			break;
		case 'speels':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='block';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='none';
			break;
		case 'equipments':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='block';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='none';
			break;
		case 'notes':
			$('id_pg_left').style.display='none';
			$('id_pg_center').style.display='none';
			$('id_pg_right').style.display='none';
			$('id_pg_skills').style.display='none';
			$('id_pg_talents').style.display='none';
			$('id_pg_language').style.display='none';
			$('id_pg_speel').style.display='none';
			$('id_pg_property').style.display='none';
			$('id_pg_background').style.display='none';
			$('id_pg_notes').style.display='block';
			break;			
	}
}

function master_tab_show(key){
	switch(key){
		case 'manager_new_pg':
			$('id_pg_manager_pg_master').style.display='none';
                        $('id_pg_manager_search').style.display='none';
			$('id_pg_manager_new').style.display='block';
			break;
		case 'manager_search':
			$('id_pg_manager_pg_master').style.display='none';
                        $('id_pg_manager_search').style.display='block';
			$('id_pg_manager_new').style.display='none';
			break;
		case 'manager_general':
			$('id_pg_manager_pg_master').style.display='block';
                        $('id_pg_manager_search').style.display='none';
			$('id_pg_manager_new').style.display='none';
			break;
	}
}


</script>
