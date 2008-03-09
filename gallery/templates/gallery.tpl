		<script type="text/javascript" src="{MODULE_DATA_PATH}/images/js/prototype.js"></script>
		<script type="text/javascript" src="{MODULE_DATA_PATH}/images/js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="{MODULE_DATA_PATH}/images/js/lightbox.js"></script>

		<script type="text/javascript">
		<!--		
		function Confirm_file() {
			return confirm("{L_CONFIRM_DEL_FILE}");
		}			
		var previous_path_pics = '';
		function display_pics(id, path, type)
		{
			document.getElementById('pics_max').innerHTML = '';					
			if( previous_path_pics != path )
			{	
				document.getElementById('pics_max').innerHTML = '<img src="' + path + '" alt="" /></a>';	
				previous_path_pics = path;
			}
		}
		function display_pics_popup(path, width, height)
		{
			width = parseInt(width);
			height = parseInt(height);
			if( height == 0 )
				height = screen.height - 150;
			if( width == 0 )
				width = screen.width - 200;
			window.open(path, '', 'width='+(width+17)+', height='+(height+17)+', location=no, status=no, toolbar=no, scrollbars=1, resizable=yes');
		}
		function display_rename_file(id, previous_name, previous_cut_name)
		{
			if( document.getElementById('fi' + id) )
			{	
				document.getElementById('fi_' + id).style.display = 'none';
				document.getElementById('fi' + id).style.display = 'inline';
				document.getElementById('fi' + id).innerHTML = '<input size="27" type="text" name="fiinput' + id + '" id="fiinput' + id + '" class="text" value="' + previous_name + '" onblur="rename_file(\'' + id + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
				document.getElementById('fiinput' + id).focus();
			}
		}	
		function rename_file(id_file, previous_cut_name)
		{
			var name = document.getElementById("fiinput" + id_file).value;
			var regex = /\/|\\|\||\?|<|>|\"/;

			if( regex.test(name) ) //interdiction des caract�res sp�ciaux dans la nom.
			{
				alert("{L_FILE_FORBIDDEN_CHARS}");	
				document.getElementById('fi_' + id_file).style.display = 'inline';
				document.getElementById('fi' + id_file).style.display = 'none';
			}
			else
			{
				document.getElementById('img' + id_file).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

				data = "id_file=" + id_file + "&name=" + name + "&previous_name=" + previous_cut_name;
				var xhr_object = xmlhttprequest_init('xmlhttprequest.php?rename_pics=1');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '0' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi_' + id_file).style.display = 'inline';
						document.getElementById('fi_' + id_file).innerHTML = xhr_object.responseText;
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
						document.getElementById('img' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '0' )
						document.getElementById('img' + id_file).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, data);
			}
		}
		function pics_aprob(id_file, aprob)
		{
			document.getElementById('img' + id_file).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

			data = 'id_file=' + id_file;
			var xhr_object = xmlhttprequest_init('xmlhttprequest.php?aprob_pics=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
				{	
					var img_aprob;
					if( xhr_object.responseText == 0 )
						img_aprob = 'unaprob.png';
					else
						img_aprob = 'aprob.png';
					
					document.getElementById('img' + id_file).innerHTML = '';
					if( document.getElementById('img_aprob' + id_file) )
						document.getElementById('img_aprob' + id_file).src = '../templates/{THEME}/images/{LANG}/' + img_aprob;
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '-1' )
					document.getElementById('img' + id_file).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		
		var delay = 2000; //D�lai apr�s lequel le bloc est automatiquement masqu�, apr�s le d�part de la souris.
		var timeout;
		var displayed = false;
		var previous = '';
		var started = false;
		
		//Affiche le bloc.
		function pics_display_block(divID)
		{
			if( timeout )
				clearTimeout(timeout);
			
			if( document.getElementById(previous) )
			{		
				document.getElementById(previous).style.display = 'none';
				started = false
			}	

			if( document.getElementById('move' + divID) )
			{			
				document.getElementById('move' + divID).style.display = 'block';
				previous = 'move' + divID;
				started = true;
			}
		}
		//Cache le bloc.
		function pics_hide_block(idfield, stop)
		{
			if( stop && timeout )
				clearTimeout(timeout);
			else if( started )
				timeout = setTimeout('pics_display_block()', delay);
		}
		
		{ARRAY_JS}
		var start_thumb = {START_THUMB};
		//Miniatures d�filantes.
		function display_thumbnails(direction)
		{			
			if( direction == 'left' )
			{	
				if( start_thumb > 0 )
				{
					start_thumb--;
					if( start_thumb == 0 )
						document.getElementById('display_left').innerHTML = '';
					else
						document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><img src="../templates/{THEME}/images/left.png" class="valign_middle" alt="" /></a>';
					document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><img src="../templates/{THEME}/images/right.png" class="valign_middle" alt="" /></a>';
				}
				else
					return;
			}
			else if( direction == 'right' )
			{
				if( start_thumb <= {MAX_START} )
				{
					start_thumb++;
					if( start_thumb == ({MAX_START} + 1) )
						document.getElementById('display_right').innerHTML = '';
					else
						document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><img src="../templates/{THEME}/images/right.png" class="valign_middle" alt="" /></a>';
					document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><img src="../templates/{THEME}/images/left.png" class="valign_middle" alt="" /></a>';
				}
				else
					return;
			}	
			
			var j = 0;
			for(var i = 0; i <= {NBR_PICS}; i++)
			{
				if( document.getElementById('thumb' + i) ) 
				{
					var key_left = start_thumb + j;
					var key_right = start_thumb + j;
					if( direction == 'left' && array_pics[key_left] )							
					{	
						document.getElementById('thumb' + i).innerHTML = '<a href="gallery' + array_pics[key_left]['link'] + '"><img src="pics/thumbnails/' + array_pics[key_left]['path'] + '" alt="" /></a>';
						j++;
					}
					else if( direction == 'right' && array_pics[key_right] ) 
					{
						document.getElementById('thumb' + i).innerHTML = '<a href="gallery' + array_pics[key_right]['link'] + '"><img 	src="pics/thumbnails/' + array_pics[key_right]['path'] + '" alt="" /></a>';				
						j++;
					}
				}
			}
		}
		
		var note_max = {NOTE_MAX};
		var array_note = new Array();		
		var timeout = null;
		var on_img = 0;
		function select_stars(divid, note)
		{
			var star_img;
			var decimal;
			for(var i = 1; i <= note_max; i++)
			{
				star_img = 'stars.png';
				if( note < i )
				{							
					decimal = i - note;
					if( decimal >= 1 )
						star_img = 'stars0.png';
					else if( decimal >= 0.75 )
						star_img = 'stars1.png';
					else if( decimal >= 0.50 )
						star_img = 'stars2.png';
					else
						star_img = 'stars3.png';
				}
	
				if( document.getElementById(divid + '_stars' + i) )
					document.getElementById(divid + '_stars' + i).src = '../templates/{THEME}/images/' + star_img;
			}
		}
		function out_div(divid, note)
		{
			if( timeout == null )
				timeout = setTimeout('select_stars(' + divid + ', ' + note + ');on_img = 0;', '50');
		}		
		function over_div()
		{
			if( on_img == 0 )
				on_img = 1;
			clearTimeout(timeout);
			timeout = null;
		}
		function send_note(id_file, idcat, note)
		{
			var regex = /\/|\\|\||\?|<|>|\"/;
			var get_nbrnote;
			var get_note;
			
			document.getElementById('img' + id_file).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

			data = "id_file=" + id_file + "&note=" + note + "&idcat=" + idcat;
			var xhr_object = xmlhttprequest_init('xmlhttprequest.php?note_pics=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{	
					document.getElementById('img' + id_file).innerHTML = '';
					if( xhr_object.responseText == '-1' )
						alert("{L_ALREADY_VOTED}");
					else
					{	
						eval(xhr_object.responseText);
						array_note[id_file] = get_note;
						select_stars(id_file, get_note);
						if( document.getElementById(id_file + '_note') )
							document.getElementById(id_file + '_note').innerHTML = '(' + get_nbrnote + ' ' + ((get_nbrnote > 1) ? '{L_VOTES}' : '{L_VOTE}') + ')';
					}				
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('img' + id_file).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		-->
		</script> 

		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		<br />	
		# ENDIF #

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<a href="gallery.php{SID}">{L_GALLERY}</a> &raquo; {U_GALLERY_CAT_LINKS} {ADD_PICS}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				<div style="margin-bottom:50px;">
					<div class="dynamic_menu" style="float:right;margin-right:55px;">
						<ul>
							<li onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
								<h5 style="margin-right:20px;"><img src="../wiki/templates/images/contribuate.png" class="valign_middle" alt="" /> {L_DISPLAY}</h5>
								<ul id="smenu1">
									<li>{U_BEST_VIEWS}</li>
									<li>{U_BEST_NOTES}</li>
								</ul>
							</li>
							<li onmouseover="show_menu(2, 0);" onmouseout="hide_menu(0);">
								<h5 style="margin-right:20px;"><img src="../wiki/templates/images/tools.png" class="valign_middle" alt="" /> {L_ORDER_BY}</h5>
								<ul id="smenu2">
									# START order #
									<li>{order.ORDER_BY}</li>
									# END order #
								</ul>
							</li>
							<li onmouseover="show_menu(3, 0);" onmouseout="hide_menu(0);">
								<h5 style="margin-right:5px;"><img src="../wiki/templates/images/tools.png" class="valign_middle" alt="" /> {L_DIRECTION}</h5>
								<ul id="smenu3">
									<li>{U_ASC}</li>
									<li>{U_DESC}</li>	
								</ul>
							</li>
						</ul>
					</div>
				</div>
				
				# IF C_GALLERY_CATS #
				<div class="block_top">{L_CATEGORIES} {EDIT_CAT}</div>
				<div class="row1" style="margin-bottom:25px;border-top:none;">
					<table style="width:100%">
						# START cat_list #
						{cat_list.OPEN_TR}								
						<td style="vertical-align:bottom;text-align:center;width:{COLUMN_WIDTH_CATS}%;margin:15px 0px;">
							<a href="gallery{cat_list.U_CAT}">{cat_list.IMG}</a>
							<br />
							<a href="gallery{cat_list.U_CAT}">{cat_list.CAT}</a> {cat_list.EDIT}
							<br />
							<span class="text_small">{cat_list.DESC}</span> 
							<br />
							{cat_list.LOCK} <span class="text_small">{cat_list.L_NBR_PICS}</span>
						</td>	
						{cat_list.CLOSE_TR}
						# END cat_list #
					
						# START end_table_cats #
							{end_table_cats.TD_END}
						{end_table_cats.TR_END}
						# END end_table_cats #
					</table>
				</div>
				# ENDIF #
				
				
				# IF C_GALLERY_PICS #
				<div class="block_top">{GALLERY} {EDIT}</div>
				<div class="row1" style="margin-bottom:25px;border-top:none;clear:both;">
					<p style="text-align:center" id="pics_max">{PAGINATION_PICS}</p>				
					
					# IF C_GALLERY_PICS_MAX #
						<p style="text-align:center;padding:15px 0px;">{IMG_MAX}</p>
						<div style="margin:auto;width:400px;height:32px;padding:0;" class="row2">
							<span style="float:left">&nbsp;&nbsp;&nbsp;{U_PREVIOUS}</span>
							<span style="float:right">{U_NEXT}&nbsp;&nbsp;&nbsp;</span>
						</div>
						<br />
						<table style="border-collapse:collapse;margin:auto;width:100%" class="row2">
							<tr>
								<th colspan="2">
									{L_INFORMATIONS}
								</th>
							</tr>
							<tr>
								<td class="row2 text_small" style="width:50%;border:none;padding:4px;">
									<strong>{L_NAME}:</strong> {NAME}
								</td>
								<td class="row2 text_small" style="border:none;padding:4px;">
									<strong>{L_POSTOR}:</strong> {POSTOR}
								</td>
							</tr>
							<tr>										
								<td class="row2 text_small" style="border:none;padding:4px;">
									<strong>{L_VIEWS}:</strong> {VIEWS}
								</td>
								<td class="row2 text_small" style="border:none;padding:4px;">
									<strong>{L_ADD_ON}:</strong> {DATE}
								</td>
							</tr>
							<tr>										
								<td class="row2 text_small" style="border:none;padding:4px;">
									<strong>{L_DIMENSION}:</strong> {DIMENSION}
								</td>
								<td class="row2 text_small" style="border:none;padding:4px;">
									<strong>{L_SIZE}:</strong> {SIZE} {L_KB}
								</td>
							</tr>
							<tr>										
								<td class="row2 text_small" style="border:none;padding:4px;">
									<strong>{L_NOTE}:</strong> {NOTE}
								</td>
								<td class="row2 text_small" style="border:none;padding:4px;vertical-align:top">
									<strong>{L_COM}:</strong> {COM}
								</td>
							</tr>
							
							# IF C_GALLERY_PICS_MODO #
							<tr>										
								<td colspan="2" class="row2 text_small" style="border:none;padding:4px;">
									&nbsp;&nbsp;&nbsp;<span id="fihref{ID}"><a href="javascript:display_rename_file('{ID}', '{RENAME}', '{RENAME_CUT}');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" class="valign_middle" /></a></span>
									
									<a href="gallery{U_DEL}" onClick="javascript:return Confirm_file();" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" class="valign_middle" /></a> 
						
									<div style="position:absolute;z-index:100;margin-top:95px;float:left;display:none;" id="move{ID}">
										<div class="bbcode_block" style="width:190px;overflow:auto;" onmouseover="pics_hide_block({ID}, 1);" onmouseout="pics_hide_block({ID}, 0);">
											<div style="margin-bottom:4px;"><strong>{L_MOVETO}</strong>:</div>
											<select class="valign_middle" name="{ID}cat" onchange="document.location = 'gallery{U_MOVE}">
												{CAT}
											</select>
											<br /><br />
										</div>
									</div>
									<a href="javascript:pics_display_block({ID});" onmouseover="pics_hide_block({ID}, 1);" onmouseout="pics_hide_block({ID}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
									
									
									<a href="javascript:pics_aprob({ID});" title="{L_APROB_IMG}"><img id="img_aprob{ID}" src="../templates/{THEME}/images/{IMG_APROB}" alt="{L_APROB_IMG}" title="{L_APROB_IMG}" class="valign_middle" /></a>
									&nbsp;<span id="img{ID}"></span>
								</td>
							</tr>
							# ENDIF #						
						</table>					
						<br /><br />					
						<table class="module_table" style="width:100%;">
							<tr>
								<th colspan="{COLSPAN}">
									{L_THUMBNAILS}
								</th>
							</tr>
							<tr>
								<td class="row2" style="width:50px;text-align:center">
									{U_LEFT_THUMBNAILS}
								</td>
								
								# START list_preview_pics #
									{list_preview_pics.PICS}
								# END list_preview_pics #
								
								<td class="row2" style="width:50px;text-align:center">
									{U_RIGHT_THUMBNAILS}
								</td>
							</tr>
						</table>
					# INCLUDE handle_com #
					# ENDIF #
					
					<table style="margin:auto;width:96%">
						# START pics_list #
							{pics_list.OPEN_TR}
							<td style="width:{COLUMN_WIDTH_PICS}%;text-align:center;padding:15px 0px;vertical-align:middle" class="text_small">
								<div style="margin-bottom:5px;" id="pics{pics_list.ID}"><a class="small_link" href="{pics_list.U_DISPLAY}">{pics_list.IMG}</a></div>
								{pics_list.NAME}
								{pics_list.POSTOR}
								{pics_list.VIEWS}
								{pics_list.COM}
								{pics_list.NOTE}
									
								<div style="width:180px;margin:auto;">										
									# IF C_GALLERY_MODO #
									<span id="fihref{pics_list.ID}"><a href="javascript:display_rename_file('{pics_list.ID}', '{pics_list.RENAME}', '{pics_list.RENAME_CUT}');" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" class="valign_middle" /></a></span>
									
									<a href="gallery{pics_list.U_DEL}" onClick="javascript:return Confirm_file();" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" class="valign_middle" /></a>
									
									<div style="position:relative;margin:auto;width:170px;display:none;float:right" onmouseover="pics_hide_block({pics_list.ID}, 1);" onmouseout="pics_hide_block({pics_list.ID}, 0);" id="move{pics_list.ID}">
										<div style="position:absolute;z-index:100;margin-top:90px;">
											<div class="bbcode_block" style="width:170px;overflow:auto;">
												<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
												<select class="valign_middle" name="{pics_list.ID}cat" onchange="document.location = 'gallery{pics_list.U_MOVE}">
													{pics_list.CAT}
												</select>
												<br /><br />
											</div>
										</div>
									</div>
									<a href="javascript:pics_display_block({pics_list.ID});" onmouseover="pics_hide_block({pics_list.ID}, 1);" onmouseout="pics_hide_block({pics_list.ID}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
										
									<a href="javascript:pics_aprob({pics_list.ID});" title="{pics_list.L_APROB_IMG}"><img id="img_aprob{pics_list.ID}" src="../templates/{THEME}/images/{pics_list.IMG_APROB}" alt="{pics_list.L_APROB_IMG}" title="{pics_list.L_APROB_IMG}" class="valign_middle" /></a>
									# ENDIF #											
									<span id="img{pics_list.ID}"></span>										
								</div>
							</td>
							{pics_list.CLOSE_TR}
						# END pics_list #
						
						# START end_table #
							{end_table.TD_END}
							
						{end_table.TR_END}
						# END end_table #
						
					</table>
					<p style="text-align:center">{PAGINATION_PICS}</p>			
				</div>
				# ENDIF #
					
				<p style="text-align:center;padding-top:15px;" class="text_small">{L_TOTAL_IMG}</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_strong">
					<a href="gallery.php{SID}">{L_GALLERY}</a> &raquo; {U_GALLERY_CAT_LINKS} {ADD_PICS}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
		</div>
		