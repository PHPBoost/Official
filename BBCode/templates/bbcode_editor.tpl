<script>
<!--
var displayed = new Array();
displayed[${escapejs(FIELD)}] = false;
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	var contents = $(field).value;
	var preview_field = 'xmlhttprequest-preview' + field;

	if( contents != "" )
	{
		if(!displayed[field])
			jQuery("#" + preview_field).slideDown(500);

		jQuery('#loading_preview' + field).show();

		displayed[field] = true;

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/content_xmlhttprequest.php",
			type: "post",
			data: {
				token: '{TOKEN}',
				path_to_root: '{PHP_PATH_TO_ROOT}',
				editor: 'BBCode',
				page_path: '{PAGE_PATH}',
				contents: contents,
				ftags: '{FORBIDDEN_TAGS}'
			},
			success: function(returnData){
				jQuery('#' + preview_field).html(returnData);

				jQuery('#loading_preview' + field).hide();
			},
			error: function(e){
				alert(e);
			}
		});
	}
	else
		alert("{L_REQUIRE_TEXT}");
}
-->
</script>

<div style="position:relative;display:none;" id="loading_preview{FIELD}">
	<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;">
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>

<div style="display:none;" class="xmlhttprequest-preview" id="xmlhttprequest-preview{FIELD}"></div>

# IF C_EDITOR_NOT_ALREADY_INCLUDED #
	<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>
# ENDIF #

<div class="bbcode expand">
	<div class="bbcode-containers">

		<ul class="bbcode-container">
			<li class="bbcode-elements">
				<a href="javascript:bb_display_block('1', '{FIELD}');" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_SMILEYS}">
					<i class="fa bbcode-icon-smileys" {AUTH_SMILEYS}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block1{FIELD}">
					<div class="bbcode-block" style="width:140px;" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
						# START smileys #
							<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');return false;" class="bbcode-hover" title="{smileys.CODE}"><img src="{smileys.URL}" alt="{smileys.CODE}"></a>{smileys.END_LINE}
						# END smileys #
						# IF C_BBCODE_SMILEY_MORE #
							<br /><br />
							<a href="" onclick="window.open('{PATH_TO_ROOT}/BBCode/formatting/smileys.php?field={FIELD}', '{L_SMILEY}', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;" class="smaller">{L_ALL_SMILEY}</a>
						# ENDIF #
					</div>
				</div>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-bold" {AUTH_B} onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');return false;" title="{L_BB_BOLD}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-italic" {AUTH_I} onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');return false;" title="{L_BB_ITALIC}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-underline" {AUTH_U} onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');return false;" title="{L_BB_UNDERLINE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-strike" {AUTH_S} onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');return false;" title="{L_BB_STRIKE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_COLOR}bbcode_color('{FIELD}');{DISABLED_COLOR}bb_display_block('5', '{FIELD}');" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" title="{L_BB_COLOR}">
					<i class="fa bbcode-icon-color" {AUTH_COLOR}></i>
				</a>
				<div class="bbcode-block-container color-picker" style="display:none;" id="bb-block5{FIELD}">
					<div id="bbcolor{FIELD}" class="bbcode-block" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
					</div>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_SIZE}bb_display_block('6', '{FIELD}');" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_SIZE}">
					<i class="fa bbcode-icon-size" {AUTH_SIZE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block6{FIELD}">
					<ul id="bbcolor{FIELD}" class="bbcode-block bbcode-block-list" style="width: 40px;" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=5]', '[/size]', '{FIELD}'); return false;" title="{L_SIZE}"> 05 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=10]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 10 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=15]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 15 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=20]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 20 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=25]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 25 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=30]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 30 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=35]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 35 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=40]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 40 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=45]', '[/size]', '{FIELD}');return false;" title="{L_SIZE}"> 45 </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_TITLE}bb_display_block('2', '{FIELD}');" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_TITLE}">
					<i class="fa bbcode-icon-title" {AUTH_TITLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block2{FIELD}">
					<ul id="bbcolor{FIELD}" class="bbcode-block bbcode-block-list" style="width: 70px;" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=1]', '[/title]', '{FIELD}'); return false;" title="{L_TITLE}"> {L_TITLE} 1 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=2]', '[/title]', '{FIELD}'); return false;" title="{L_TITLE}"> {L_TITLE} 2 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=3]', '[/title]', '{FIELD}'); return false;" title="{L_TITLE}"> {L_TITLE} 3 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=4]', '[/title]', '{FIELD}'); return false;" title="{L_TITLE}"> {L_TITLE} 4 </a></li>
					</ul>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_LIST}bb_display_block('9', '{FIELD}');" onmouseout="{DISABLED_LIST}bb_hide_block('9', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_LIST}">
					<i class="fa bbcode-icon-list" {AUTH_LIST}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block9{FIELD}">
					<div class="bbcode-block" style="width: 150px;" onmouseover="bb_hide_block('9', '{FIELD}', 1);" onmouseout="bb_hide_block('9', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb_list{FIELD}">{L_LINES}</label>
							<div class="form-field">
								<input id="bb_list{FIELD}" class="field-smaller" size="3" type="text" name="bb_list{FIELD}" maxlength="3" value="3">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb_ordered_list{FIELD}">{L_ORDERED_LIST}</label>
							<div class="form-field">
								<input id="bb_ordered_list{FIELD}" type="checkbox" name="bb_ordered_list{FIELD}" >
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="javascript:bbcode_list('{FIELD}');">
								<i class="fa bbcode-icon-list valign-middle" title="{L_BB_LIST}"></i> {L_INSERT_LIST}
							</a>
						</div>
					</div>
				</div>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_CONTAINER}">
					<i class="fa bbcode-icon-subtitle" {AUTH_BLOCK}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block3{FIELD}">
					<ul id="bbcolor{FIELD}" class="bbcode-block bbcode-block-list" style="width: 100px;" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[block]', '[/block]', '{FIELD}'); return false;" title="{L_CONTAINER}"> {L_BLOCK} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[fieldset]', '[/fieldset]', '{FIELD}'); return false;" title="{L_CONTAINER}"> {L_FIELDSET} </a></li>
					</ul>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-quote" {AUTH_QUOTE} onclick="{DISABLED_QUOTE}insertbbcode('[quote]', '[/quote]', '{FIELD}');return false;" title="{L_BB_QUOTE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-hide" {AUTH_HIDE} onclick="{DISABLED_HIDE}insertbbcode('[hide]', '[/hide]', '{FIELD}');return false;" title="{L_BB_HIDE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_STYLE}bb_display_block('4', '{FIELD}');" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_STYLE}">
					<i class="fa bbcode-icon-style" {AUTH_STYLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block4{FIELD}">
					<ul id="bbcolor{FIELD}" class="bbcode-block bbcode-block-list" style="width: 110px;" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=success] ', '[/style]', '{FIELD}'); return false;" title="{L_SUCCESS} "> {L_SUCCESS}  </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=question]', '[/style]', '{FIELD}'); return false;" title="{L_QUESTION}"> {L_QUESTION} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=notice]  ', '[/style]', '{FIELD}'); return false;" title="{L_NOTICE}  "> {L_NOTICE}   </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=warning] ', '[/style]', '{FIELD}'); return false;" title="{L_WARNING} "> {L_WARNING}  </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=error]   ', '[/style]', '{FIELD}'); return false;" title="{L_ERROR}   "> {L_ERROR}    </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-url" {AUTH_URL} onclick="{DISABLED_URL}bbcode_url('{FIELD}', ${escapejs(L_URL_PROMPT)});return false;" title="{L_BB_URL}"></a>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-image" {AUTH_IMG} onclick="{DISABLED_IMG}insertbbcode('[img]', '[/img]', '{FIELD}');return false;" title="{L_BB_IMAGE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-lightbox" {AUTH_LIGHTBOX} onclick="{DISABLED_lightbox}bbcode_lightbox('{FIELD}', ${escapejs(L_URL_PROMPT)});return false;" title="{L_BB_LIGHTBOX}"></a>
			</li>

			# IF C_UPLOAD_MANAGEMENT #
			<li class="bbcode-elements bbcode-separator">
			</li>
			<li class="bbcode-elements">
				<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=720,resizable=yes,scrollbars=yes');return false;">
					<i class="fa bbcode-icon-upload"></i>
				</a>
			</li>
			# ENDIF #
		</ul>

		<ul class="bbcode-container bbcode-more" id="bbcode_more{FIELD}">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-left" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');return false;" title="{L_BB_LEFT}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-center" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');return false;" title="{L_BB_CENTER}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-right" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');return false;" title="{L_BB_RIGHT}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-justify" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');return false;" title="{L_BB_JUSTIFY}"></a>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-float-left" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');return false;" title="{L_BB_FLOAT_LEFT}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-float-right" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');return false;" title="{L_BB_FLOAT_RIGHT}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-indent" {AUTH_INDENT} onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');return false;" title="{L_BB_INDENT}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_TABLE}bb_display_block('7', '{FIELD}');" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode-hover" title="{L_BB_TABLE}">
					<i class="fa bbcode-icon-table" {AUTH_TABLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block7{FIELD}">
					<div id="bbtable{FIELD}" class="bbcode-block" style="width:160px;" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb-lines{FIELD}">{L_LINES}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-lines{FIELD}" id="bb-lines{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-cols{FIELD}">{L_COLS}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-head{FIELD}">{L_ADD_HEAD}</label>
							<div class="form-field">
								<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller">
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="javascript:{DISABLED_TABLE}bbcode_table('{FIELD}', '{L_TABLE_HEAD}');">
								<i class="fa bbcode-icon-table" title="{L_BB_TABLE}"></i> {L_INSERT_TABLE}
							</a>
						</div>
					</div>
				</div>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sup" {AUTH_SUP} onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');return false;" title="{L_BB_SUP}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sub" {AUTH_SUB} onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');return false;" title="{L_BB_SUB}"></a>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-anchor" {AUTH_ANCHOR} onclick="{DISABLED_ANCHOR}bbcode_anchor('{FIELD}', ${escapejs(L_ANCHOR_PROMPT)});return false;" title="{L_BB_ANCHOR}"></a>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-flash" {AUTH_SWF} onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');return false;" title="{L_BB_SWF}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-movie" {AUTH_MOVIE} onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');return false;" title="{L_BB_MOVIE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-youtube" {AUTH_YOUTUBE} onclick="{DISABLED_YOUTUBE}insertbbcode('[youtube]', '[/youtube]', '{FIELD}');return false;" title="{L_BB_YOUTUBE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sound" {AUTH_SOUND} onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');return false;" title="{L_BB_SOUND}"></a>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_CODE}bb_display_block('8', '{FIELD}');" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_CODE}">
					<i class="fa bbcode-icon-code" {AUTH_CODE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block8{FIELD}">
					<div class="bbcode-block bbcode-block-list" style="width: 130px;" onmouseover="bb_hide_block('8', '{FIELD}', 1);" onmouseout="bb_hide_block('8', '{FIELD}', 0);">
						<ul class="bbcode-block-code">
							<li class="bbcode-code-title"><span>{L_TEXT}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=text]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">Text</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=sql]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">SqL</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=xml]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Xml</a></li>

							<li class="bbcode-code-title"><span>{L_PHPBOOST_LANGUAGES}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bbcode]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">BBCode</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=tpl]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">Template</a></li>

							<li class="bbcode-code-title"><span>{L_SCRIPT}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=php]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">PHP</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asp]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">Asp</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=python]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Python</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pearl]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">Pearl</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=ruby]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">Ruby</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bash]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Bash</a></li>

							<li class="bbcode-code-title"><span>{L_WEB}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=html]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">Html</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=css]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">Css</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=javascript]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Javascript</a></li>

							<li class="bbcode-code-title"><span>{L_PROG}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=c]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">C</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=cpp]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">C++</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=c#]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">C#</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=d]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">D</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=java]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">Java</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pascal]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Pascal</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=delphi]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Delphi</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=fortran]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} text">Fortran</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=vb]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} sql">Vb</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asm]', '[/code]', '{FIELD}'); return false;" title="{L_CODE} xml">Asm</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-math" {AUTH_MATH} onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');return false;" title="{L_BB_MATH}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-html" {AUTH_HTML} onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');return false;" title="{L_BB_HTML}"></a>
			</li>

			<li class="bbcode-elements bbcode-separator">
			</li>

			<li class="bbcode-elements">
				<a href="http://www.phpboost.com/wiki/bbcode" title="{L_BB_HELP}">
					<i class="fa bbcode-icon-help"></i>
				</a>
			</li>
		</ul>

		<ul class="bbcode-container bbcode-elements-more">
			<li class="bbcode-elements">
				<a href="" title="{L_BB_MORE}" onclick="show_bbcode_div('bbcode_more{FIELD}', 1);return false;" style="">
					<i class="fa bbcode-icon-more bbcode-hover"></i>
				</a>
			</li>
		</ul>

		<div class="spacer"></div>
	</div>
</div>


<script>
<!--
set_bbcode_preference('bbcode_more{FIELD}');
-->
</script>