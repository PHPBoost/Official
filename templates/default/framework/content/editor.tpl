<div id="div1"></div>

		<script type="text/javascript">
		<!--
		var displayed = new Array();
		displayed['{FIELD}'] = false;
		function XMLHttpRequest_preview(field)
		{
			if( XMLHttpRequest_preview.arguments.length == 0 )
 			    field = '{FIELD}';

			{TINYMCE_TRIGGER}
			var contents = document.getElementById(field).value;
			
			if( contents != "" )
			{
				if( !displayed[field] ) 
					Effect.BlindDown('xmlhttprequest_preview' + field, { duration: 0.5 });
					
				if( document.getElementById('loading_preview' + field) )
					document.getElementById('loading_preview' + field).style.display = 'block';
				displayed[field] = true;			

				new Ajax.Request(
					'{PATH_TO_ROOT}/kernel/framework/ajax/content_xmlhttprequest.php',
					{
						method: 'post',
						parameters: {
							token: '{TOKEN}',
							path_to_root: '{PHP_PATH_TO_ROOT}',
							editor: '{EDITOR_NAME}',
							page_path: '{PAGE_PATH}',  
							contents: contents,
							ftags: '{FORBIDDEN_TAGS}'
						 },
						onSuccess: function(response)
						{
							document.getElementById('xmlhttprequest_preview' + field).innerHTML = response.responseText;
							if( document.getElementById('loading_preview' + field) )
								document.getElementById('loading_preview' + field).style.display = 'none';
						}
					}
				);
			}	
			else
				alert("{L_REQUIRE_TEXT}");
		}
		function insertTinyMceContent(content)
		{ 
			# IF C_BBCODE_TINYMCE_MODE #
			tinyMCE.execCommand('mceInsertContent', false, content, {skip_undo : 1});
			# ENDIF #
		}
		-->
		</script>
		<div style="position:relative;display:none;" id="loading_preview{FIELD}"><div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div></div>
		<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_preview{FIELD}"></div>
		
		# IF C_BBCODE_TINYMCE_MODE #			
		<script language="javascript" type="text/javascript">
		<!--
		tinyMCE.init({
			mode : "exact",
			elements : "{FIELD}", 
			theme : "advanced",
			language : "fr",
			content_css : "{PATH_TO_ROOT}/templates/{THEME}/theme/tinymce.css",
			theme_advanced_buttons1 : "{THEME_ADVANCED_BUTTONS1}", 
			theme_advanced_buttons2 : "{THEME_ADVANCED_BUTTONS2}", 
			theme_advanced_buttons3 : "{THEME_ADVANCED_BUTTONS3}",
			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "center", 
			theme_advanced_statusbar_location : "bottom",
			plugins : "table,flash,searchreplace,inlinepopups,fullscreen,emotions",
			extended_valid_elements : "font[face|size|color|style],span[class|align|style],a[href|name]",
			theme_advanced_resize_horizontal : false, 
			theme_advanced_resizing : true
		});
		-->
		</script>
		
			# IF C_UPLOAD_MANAGEMENT #
				<div style="float:right;margin-left:5px;">
					<a style="font-size: 10px;" title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd={IDENTIFIER}', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>
				</div>
			# ENDIF #
		
		# ENDIF #
		
		# IF C_BBCODE_NORMAL_MODE #
		# IF C_EDITOR_NOT_ALREADY_INCLUDED #
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/bbcode.js"></script>
		# ENDIF #
		<script type="text/javascript">
		<!--
		function bbcode_color(field)
		{
			var i;
			var br;
			var contents;
			var color = new Array(
			'black', 'maroon', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
			'#800000', 'orange', '#808000', 'green', '#008080', 'blue', '#666699', '#808080',
			'red', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
			'pink', '#FFCC00', 'yellow', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
			'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', 'white');							
			
			contents = '<table style="border-collapse:collapse;margin:auto;"><tr>';
			for(i = 0; i < 40; i++)
			{
				br = (i+1) % 8;
				br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
				contents += '<td style="padding:2px;">'+
					'<a onclick="insertbbcode(\'[color='+ color[i]+ ']\', \'[/color]\', field);"'+
					'class="bbcode_hover"><span style="background:' + color[i] +
					';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>'
					+ br;								
			}
			alert(document.getElementById('bbcolorcontents'));
			document.getElementById('bbcolor'+field).innerHTML = contents + '</tr></table>';
		}
		
		function bbcode_table(field)
		{
			var cols = document.getElementById('bb_cols'+field).value;
			var lines = document.getElementById('bb_lines'+field).value;
			var head = document.getElementById('bb_head'+field).checked;
			var code = '';
			
			if( cols >= 0 && lines >= 0 )
			{
				var colspan = cols > 1 ? ' colspan="' + cols + '"' : '';
				var pointor = head ? (59 + colspan.length) : 22;
				code = head ? '[table]\n\t[row]\n\t\t[head' + colspan + 
					']{L_TABLE_HEAD}[/head]\n\t[/row]\n' : '[table]\n';
				
				for(var i = 0; i < lines; i++)
				{
					code += '\t[row]\n';
					for(var j = 0; j < cols; j++)
						code += '\t\t[col][/col]\n';
					code += '\t[/row]\n';
				}				
				code += '[/table]';
				
				insertbbcode(code.substring(0, pointor), code.substring(pointor, code.length), field);
			}
		}
		function bbcode_list(field)
		{
			var elements = document.getElementById('bb_list'+field).value;
			var ordered_list = document.getElementById('bb_ordered_list'+field).checked;
			if( elements <= 0 )
				elements = 1;
			
			var pointor = ordered_list ? 19 : 11;
			
			code = '[list' + (ordered_list ? '=ordered' : '') + ']\n';
			for(var j = 0; j < elements; j++)
				code += '\t[*]\n';
			code += '[/list]';
			insertbbcode(code.substring(0, pointor), code.substring(pointor, code.length), field);
		}
		
		function bbcode_url(field, prompt_str)
		{
			var url = prompt(prompt_str);
			if( url != null && url != '' )
				insertbbcode('[url=' + url + ']', '[/url]', field);
			else
				insertbbcode('[url]', '[/url]', field);
		}

		var my_table = Builder.node('table', {'style':'margin:4px;margin-left:auto;margin-right:auto;'}, [
							Builder.node('tr', { }, [
								Builder.node('td', { }, [
									Builder.node('table', {'class':'bbcode'}, [
										Builder.node('tr', { }, [
											Builder.node('td', {'id': 'td1'}, '')
										]) ]) ]) ]) ]);
										

		path = '{PATH_TO_ROOT}/templates/{THEME}/';
		field = '{FIELD}';

		function balise(attrs)
		{
			return new Element('img', {'src': path+'images/form/'+attrs.fname, 'alt': attrs.alt, 'class':attrs.classe, 'title':attrs.title, 'onclick':attrs.onclick});
		}
		
		function balise2(attrs)
		{
			alert(attrs.bbcode);
			begin = '[' + attrs.bbcode + ']';
			end = '[/' + attrs.bbcode + ']';
			var fn = function() { insertbbcode(begin, end, field); };
			return new Element('img', {'src': path+'images/form/'+attrs.fname, 'alt': attrs.alt, 'class':attrs.classe, 'title':attrs.title, 'onclick':fn});
		}
		
		var separator = {'fname':'separate.png', 'alt':'', 'title':'', 'bbcode':'', 'field':'', 'classe':'', 'onclick':''};
		$('div1').insert(balise(separator));
		var smiley = {'fname':'smileys.png', 'alt':'{L_BB_SMILEYS}', 'title':'{L_BB_SMILEYS}', 'classe':'bbcode_hover', 'onclick': function() {bb_display_block('1', field);}};
		$('div1').insert(balise(smiley));
		$('div1').insert(balise(separator));
		
		var block1 = [
			{'fname':'bold.png', 'alt':'{L_BB_BOLD}', 'title':'{L_BB_BOLD}', 'classe':'bbcode_hover', 'bbcode': 'b'},
			{'fname':'italic.png', 'alt':'{L_BB_ITALIC}', 'title':'{L_BB_ITALIC}', 'classe':'bbcode_hover', 'bbcode': 'i'},
			{'fname':'underline.png', 'alt':'{L_BB_UNDERLINE}', 'title':'{L_BB_UNDERLINE}', 'classe':'bbcode_hover', 'bbcode': 'u'},
			{'fname':'strike.png', 'alt':'{L_BB_STRIKE}', 'title':'{L_BB_STRIKE}', 'classe':'bbcode_hover', 'bbcode': 's'}
		];
		
		block1.each( function(value) {
			$('div1').insert(balise2(value));
		});
		$('div1').insert(balise(separator));
		
		alert(my_table);
		
		var editor_menu = '\
		<table style="margin:4px;margin-left:auto;margin-right:auto;">\
			<tr>\
				<td>\
					<table class="bbcode">\
						<tr>\
							<td style="padding:1px;">\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<div style="position:relative;z-index:100;margin-left:-50px;bottom:2px;float:left;display:none;" id="bb_block1{FIELD}">\
									<div class="bbcode_block" style="width:130px;" onmouseout="bb_display_block(\'1\', \'{FIELD}\');">\
									</div>\
								</div>\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/smileys.png"  {AUTH_SMILEYS} alt="{L_BB_SMILEYS}" onclick="bb_display_block(\'1\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_SMILEYS}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/bold.png" class="bbcode_hover" {AUTH_B} onclick="{DISABLED_B}insertbbcode(\'[b]\', \'[/b]\', \'{FIELD}\');" alt="{L_BB_BOLD}" title="{L_BB_BOLD}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/italic.png" class="bbcode_hover" {AUTH_I} onclick="{DISABLED_I}insertbbcode(\'[i]\', \'[/i]\', \'{FIELD}\');" alt="{L_BB_ITALIC}" title="{L_BB_ITALIC}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/underline.png" class="bbcode_hover" {AUTH_U} onclick="{DISABLED_U}insertbbcode(\'[u]\', \'[/u]\', \'{FIELD}\');" alt="{L_BB_UNDERLINE}" title="{L_BB_UNDERLINE}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/strike.png" class="bbcode_hover" {AUTH_S} onclick="{DISABLED_S}insertbbcode(\'[s]\', \'[/s]\', \'{FIELD}\');" alt="{L_BB_STRIKE}" title="{L_BB_STRIKE}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block2{FIELD}">\
									<div style="margin-left:110px;" class="bbcode_block" onmouseout="bb_display_block(\'2\', \'{FIELD}\');">\
										<select id="title{FIELD}" onchange="insertbbcode_select(\'title\', \'[/title]\', \'{FIELD}\')">\
											<option value="" selected="selected" disabled="disabled">{L_TITLE}</option>\
											<option value="1">{L_TITLE}1</option>\
											<option value="2">{L_TITLE}2</option>\
											<option value="3">{L_TITLE}3</option>\
											<option value="4">{L_TITLE}4</option>\
										</select>\
									</div>\
								</div>\						
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/title.png" {AUTH_TITLE} alt="{L_BB_TITLE}" onclick="bb_display_block(\'2\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_TITLE}" />\
								\
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block3{FIELD}">\
									<div style="margin-left:135px;" class="bbcode_block" onmouseout="bb_display_block(\'3\', \'{FIELD}\');">\
										<select id="blocks{FIELD}" onchange="insertbbcode_select2(\'blocks\', \'{FIELD}\')">\
											<option value="" selected="selected" disabled="disabled">{L_CONTAINER}</option>\
											<option value="block">{L_BLOCK}</option>\
											<option value="fieldset">{L_FIELDSET}</option>\
										</select>\
									</div>\
								</div>\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/subtitle.png" {AUTH_BLOCK} alt="{L_BB_CONTAINER}" onclick="bb_display_block(\'3\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_CONATINER}" />\
								\
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block4{FIELD}">\
									<div style="margin-left:160px;" class="bbcode_block" onmouseout="bb_display_block(\'4\', \'{FIELD}\');">\
										<select id="style{FIELD}" onchange="insertbbcode_select(\'style\', \'[/style]\', \'{FIELD}\')">\
											<option value="" selected="selected" disabled="disabled">{L_STYLE}</option>\
											<option value="success">{L_SUCCESS}</option>\
											<option value="question">{L_QUESTION}</option>\
											<option value="notice">{L_NOTICE}</option>\
											<option value="warning">{L_WARNING}</option>\
											<option value="error">{L_ERROR}</option>\
										</select>\
									</div>\
								</div>\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/style.png" {AUTH_STYLE} alt="{L_BB_STYLE}" onclick="bb_display_block(\'4\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_STYLE}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" class="bbcode_hover" {AUTH_URL} onclick="{DISABLED_URL}bbcode_url_{FIELD}();" alt="{L_BB_URL}" title="{L_BB_URL}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/image.png" class="bbcode_hover" {AUTH_IMG} onclick="{DISABLED_IMG}insertbbcode(\'[img]\', \'[/img]\', \'{FIELD}\');" alt="{L_BB_IMG}" title="{L_BB_IMG}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/quote.png" class="bbcode_hover" {AUTH_QUOTE} onclick="{DISABLED_QUOTE}insertbbcode(\'[quote]\', \'[/quote]\', \'{FIELD}\');" alt="{L_BB_QUOTE}" title="{L_BB_QUOTE}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/hide.png" class="bbcode_hover" {AUTH_HIDE} onclick="{DISABLED_HIDE}insertbbcode(\'[hide]\', \'[/hide]\', \'{FIELD}\');" alt="{L_BB_HIDE}" title="{L_BB_HIDE}" />\
								\
								<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block9{FIELD}">\
									<div class="bbcode_block" style="margin-left:-220px;width:180px;" onmouseout="bb_hide_block(\'9\', \'{FIELD}\', 0);">\
										<p><label style="font-size:10px;font-weight:normal">* {L_LINES} <input size="3" type="text" class="text" name="bb_list{FIELD}" id="bb_list{FIELD}" maxlength="3" value="3" /></label></p>\
										<p><label style="font-size:10px;font-weight:normal">{L_ORDERED_LIST} <input size="3" type="checkbox" name="bb_ordered_list{FIELD}" id="bb_ordered_list{FIELD}" /></label></p>\
										<p style="text-align:center;"><a class="small_link" href="javascript:bbcode_list_{FIELD}();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/list.png" alt="{L_BB_LIST}" title="{L_BB_LIST}" class="valign_middle" /> {L_INSERT_LIST}</a></p>\
									</div>\
								</div>\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/list.png" {AUTH_LIST} alt="{L_BB_LIST}" onclick="bb_display_block(\'9\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_LIST}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block5{FIELD}">\
									<div id="bbcolor{FIELD}" class="bbcode_block" style="margin-left:-170px;background:white;" onmouseout="bb_hide_block(\'5\', \'{FIELD}\', 0);">\
									</div>\
								</div>\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" {AUTH_COLOR} alt="{L_BB_COLOR}" onclick="bb_display_block(\'5\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_COLOR}" />\
								\
								<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block6{FIELD}">\
									<div style="margin-left:-120px;" class="bbcode_block" onmouseout="bb_hide_block(\'6\', \'{FIELD}\', 0);">\
										<select id="size{FIELD}" onchange="insertbbcode_select(\'size\', \'[/size]\', \'{FIELD}\')">\
											<option value="" selected="selected" disabled="disabled">{L_SIZE}</option>\
											<option value="5">5</option>\
											<option value="10">10</option>\
											<option value="15">15</option>\
											<option value="20">20</option>\
											<option value="25">25</option>\
											<option value="30">30</option>\
											<option value="35">35</option>\
											<option value="40">40</option>\
											<option value="45">45</option>\
										</select>\
									</div>\
								</div>\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/size.png" {AUTH_SIZE} alt="{L_BB_SIZE}" onclick="bb_display_block(\'6\', \'{FIELD}\');" class="bbcode_hover" title="{L_BB_SIZE}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/minus.png" style="cursor: pointer;cursor:hand;" onclick="textarea_resize(\'{FIELD}\', -100, \'height\');textarea_resize(\'xmlhttprequest_preview\', -100, \'height\');" alt="{L_BB_SMALL}" title="{L_BB_SMALL}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" style="cursor: pointer;cursor:hand;" onclick="textarea_resize(\'{FIELD}\', 100, \'height\');textarea_resize(\'xmlhttprequest_preview\', 100, \'height\');" alt="{L_BB_LARGE}" title="{L_BB_LARGE}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/more.png" alt="" class="bbcode_hover" onclick="show_bbcode_div(\'bbcode_more{FIELD}\', 1);" />\
							</td>\
						</tr>\
					</table>\
					<table class="bbcode2" id="bbcode_more{FIELD}">\
						<tr>\
							<td style="width:100%;padding:1px;">\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/left.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode(\'[align=left]\', \'[/align]\', \'{FIELD}\');" alt="{L_BB_LEFT}" title="{L_BB_LEFT}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/center.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode(\'[align=center]\', \'[/align]\', \'{FIELD}\');" alt="{L_BB_CENTER}" title="{L_BB_CENTER}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/right.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode(\'[align=right]\', \'[/align]\', \'{FIELD}\');" alt="{L_BB_RIGHT}" title="{L_BB_RIGHT}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/justify.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode(\'[align=justify]\', \'[/align]\', \'{FIELD}\');" alt="{L_BB_JUSTIFY}" title="{L_BB_JUSTIFY}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/float_left.png" class="bbcode_hover" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode(\'[float=left]\', \'[/float]\', \'{FIELD}\');" alt="{L_BB_FLOAT_LEFT}" title="{L_BB_FLOAT_LEFT}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/float_right.png" class="bbcode_hover" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode(\'[float=right]\', \'[/float]\', \'{FIELD}\');" alt="{L_BB_FLOAT_RIGHT}" title="{L_BB_FLOAT_RIGHT}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/sup.png" class="bbcode_hover" {AUTH_SUP} onclick="{DISABLED_SUP}insertbbcode(\'[sup]\', \'[/sup]\', \'{FIELD}\');" alt="{L_BB_SUP}" title="{L_BB_SUP}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/sub.png" class="bbcode_hover" {AUTH_SUB} onclick="{DISABLED_SUB}insertbbcode(\'[sub]\', \'[/sub]\', \'{FIELD}\');" alt="{L_BB_SUB}" title="{L_BB_SUB}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/indent.png" class="bbcode_hover" {AUTH_INDENT} onclick="{DISABLED_INDENT}insertbbcode(\'[indent]\', \'[/indent]\', \'{FIELD}\');" alt="{L_BB_INDENT}" title="{L_BB_INDENT}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/table.png" {AUTH_TABLE} alt="{L_BB_TABLE}" title="{L_BB_TABLE}" />\
                                \
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/flash.png" class="bbcode_hover" {AUTH_SWF} onclick="{DISABLED_SWF}insertbbcode(\'[swf=425,344]\', \'[/swf]\', \'{FIELD}\');" alt="{L_BB_SWF}" title="{L_BB_SWF}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/movie.png" class="bbcode_hover" {AUTH_MOVIE} onclick="{DISABLED_MOVIE}insertbbcode(\'[movie=100,100]\', \'[/movie]\', \'{FIELD}\');" alt="{L_BB_MOVIE}" title="{L_BB_MOVIE}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/sound.png" class="bbcode_hover" {AUTH_SOUND} onclick="{DISABLED_SOUND}insertbbcode(\'[sound]\', \'[/sound]\', \'{FIELD}\');" alt="{L_BB_SOUND}" title="{L_BB_SOUND}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/code.png" {AUTH_CODE} alt="{L_BB_CODE}" />\
								\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/math.png" class="bbcode_hover" {AUTH_MATH} onclick="{DISABLED_MATH}insertbbcode(\'[math]\', \'[/math]\', \'{FIELD}\');" alt="{L_BB_MATH}" title="{L_BB_MATH}" />\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/html.png" class="bbcode_hover" {AUTH_HTML} onclick="{DISABLED_HTML}insertbbcode(\'[html]\', \'[/html]\', \'{FIELD}\');" alt="{L_BB_HTML}" title="{L_BB_HTML}" />\
							</td>\
							<td style="width:3px;">\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />\
							</td>\
							<td style="padding:0px 2px;width:22px;">\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/help.png" alt="{L_BB_HELP}" />\
							</td>\
						</tr>\
					</table>\
				</td>\
				<td style="vertical-align:top;padding-left:8px;padding-top:5px;">\
					# IF C_UPLOAD_MANAGEMENT #\
					<a title="{L_BB_UPLOAD}" href="#" onclick="window.open(\'{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd={FIELD}&amp;edt={EDITOR_NAME}\', \'\', \'height=500,width=720,resizable=yes,scrollbars=yes\');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>\
					# ENDIF #\
				</td>\
			</tr>\
		</table>';
		
		-->
		</script>
		# ENDIF #
