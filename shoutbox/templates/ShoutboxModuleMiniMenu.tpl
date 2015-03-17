<script>
<!--
function shoutbox_add_message()
{
	var pseudo = jQuery("#shout-pseudo").val();
	var contents = jQuery("#shout-contents").val();

	if (pseudo && contents)
	{
		jQuery.ajax({
			url: '${relative_url(ShoutboxUrlBuilder::ajax_add())}',
			type: "post",
			dataType: "json",
			data: {'pseudo' : pseudo, 'contents' : contents, 'token' : '{TOKEN}'},
			beforeSend: function(){
				jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
			},
			success: function(returnData){
				if(returnData.code > 0) {
					shoutbox_refresh_messages_box();
					jQuery('#shout-contents').val('');
				} else {
					switch(returnData.code)
					{
						case -1:
							alert(${escapejs(LangLoader::get_message('e_flood', 'errors'))});
						break;
						case -2:
							alert("{L_ALERT_LINK_FLOOD}");
						break;
						case -3:
							alert(${escapejs(LangLoader::get_message('e_incomplete', 'errors'))});
						break;
						case -4:
							alert(${escapejs(LangLoader::get_message('error.auth', 'status-messages-common'))});
						break;
					}
				}
				jQuery('#shoutbox-refresh').html('<i class="fa fa-refresh"></i>');
			},
			error: function(e){
				alert(e);
			}
		});
	} else {
		alert("${LangLoader::get_message('require_text', 'main')}");
		return false;
	}
}

function shoutbox_delete_message(id_message)
{
	if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
	{
		jQuery.ajax({
			url: '${relative_url(ShoutboxUrlBuilder::ajax_delete())}',
			type: "post",
			dataType: "json",
			data: {'id' : id_message, 'token' : '{TOKEN}'},
			beforeSend: function(){
				jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
			},
			success: function(returnData){
				var code = returnData.code;

				if(code > 0) {
					jQuery('#shoutbox-message-' + code).remove();
				} else {
					alert("{@error.message.delete}");
				}
				jQuery('#shoutbox-refresh').html('<i class="fa fa-refresh"></i>');
			},
			error: function(e){
				alert(e);
			}
		});
	}
}

function shoutbox_refresh_messages_box() {
	jQuery.ajax({
		url: '${relative_url(ShoutboxUrlBuilder::ajax_refresh())}',
		type: "post",
		dataType: "json",
		data: {'token' : '{TOKEN}'},
		beforeSend: function(){
			jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
		},
		success: function(returnData){
			jQuery('#shoutbox-messages-container').html(returnData.messages);

			jQuery('#shoutbox-refresh').html('<i class="fa fa-refresh"></i>');
		},
		error: function(e){
			alert(e);
		}
	});
}

# IF C_AUTOMATIC_REFRESH_ENABLED #setInterval(shoutbox_refresh_messages_box, {SHOUT_REFRESH_DELAY});# ENDIF #
-->
</script>
# IF C_DISPLAY_SHOUT_BBCODE #<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script># ENDIF #

<div id="module-mini-shoutbox" class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title">{@module_title}</h5>
	</div>
	<div class="module-mini-contents">
		<div id="shoutbox-messages-container"# IF C_HORIZONTAL # class="shout-horizontal" # ENDIF #># INCLUDE SHOUTBOX_MESSAGES #</div>
		# IF C_DISPLAY_FORM #
		<form action="?token={TOKEN}" method="post">
			# IF NOT C_MEMBER #
			<div class="spacer"></div>
			<label for="shout-pseudo"><span class="small">${LangLoader::get_message('field.name', 'admin-user-common')}</span></label>
			<input size="16" maxlength="25" type="text" name="shout-pseudo" id="shout-pseudo" value="${LangLoader::get_message('guest', 'main')}">
			# ELSE #
			<input size="16" maxlength="25" type="hidden" name="shout-pseudo" id="shout-pseudo" value="{SHOUTBOX_PSEUDO}">
			# ENDIF #
			<br />
			# IF C_VERTICAL #<label for="shout-contents"><span class="small">${LangLoader::get_message('message', 'main')}</span></label># ENDIF #
			<textarea id="shout-contents" name="shout-contents"# IF C_VALIDATE_ONKEYPRESS_ENTER # onkeypress="if(event.keyCode==13){shoutbox_add_message();}"# ENDIF # rows="# IF C_VERTICAL #4# ELSE #2# ENDIF #" cols="16"></textarea>
			# IF C_DISPLAY_SHOUT_BBCODE #
			<div id="shoutbox-bbcode-container" class="shout-spacing">
				<ul>
					<li class="bbcode-elements">
						<a href="javascript:bb_display_block('1', 'shout-contents');" onmouseover="bb_hide_block('1', 'shout-contents', 1);" onmouseout="bb_hide_block('1', 'shout-contents', 0);" class="fa bbcode-icon-smileys" title="${LangLoader::get_message('bb_smileys', 'common', 'BBCode')}"></a>
						<div class="bbcode-block-container" style="display:none;" id="bb-block1shout-contents">
							<div class="bbcode-block block-smileys" onmouseover="bb_hide_block('1', 'shout-contents', 1);" onmouseout="bb_hide_block('1', 'shout-contents', 0);">
								# START smileys #
									<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', 'shout-contents');return false;" class="bbcode-hover" title="{smileys.CODE}"><img src="{smileys.URL}" alt="{smileys.CODE}"></a># IF smileys.C_END_LINE #<br /># ENDIF #
								# END smileys #
							</div>
						</div>
					</li>
					<li class="bbcode-elements">
						<a href="" class="fa bbcode-icon-bold# IF C_BOLD_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_BOLD_DISABLED #insertbbcode('[b]', '[/b]', 'shout-contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_bold', 'common', 'BBCode')}"></a>
					</li>
					<li class="bbcode-elements">
						<a href="" class="fa bbcode-icon-italic# IF C_ITALIC_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_ITALIC_DISABLED #insertbbcode('[i]', '[/i]', 'shout-contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_italic', 'common', 'BBCode')}"></a>
					</li>
					<li class="bbcode-elements">
						<a href="" class="fa bbcode-icon-underline# IF C_UNDERLINE_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_UNDERLINE_DISABLED #insertbbcode('[u]', '[/u]', 'shout-contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_underline', 'common', 'BBCode')}"></a>
					</li>
					<li class="bbcode-elements">
						<a href="" class="fa bbcode-icon-strike# IF C_STRIKE_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_STRIKE_DISABLED #insertbbcode('[s]', '[/s]', 'shout-contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_strike', 'common', 'BBCode')}"></a>
					</li>
				</ul>
			</div>
			# ENDIF #
			<p class="shout-spacing">
				<button onclick="shoutbox_add_message();" type="button">${LangLoader::get_message('submit', 'main')}</button>
				<a href="" onclick="shoutbox_refresh_messages_box();return false;" id="shoutbox-refresh" title="${LangLoader::get_message('refresh', 'main')}"><i class="fa fa-refresh"></i></a>
			</p>
		</form>
		# ELSE #
		<div class="spacer"></div>
		<span class="warning">{@error.post.unauthorized}</span>
		<p class="shout-spacing">
			<a href="" onclick="shoutbox_refresh_messages_box();return false;" id="shoutbox-refresh" title="${LangLoader::get_message('refresh', 'main')}"><i class="fa fa-refresh"></i></a>
		</p>
		# ENDIF #
		<a class="small" href="${relative_url(ShoutboxUrlBuilder::home())}" title="">{@archives}</a>
	</div>
	<div class="module-mini-bottom"></div>
</div>
