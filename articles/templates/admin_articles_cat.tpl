<link href="{PICTURES_DATA_PATH}/articles.css" rel="stylesheet" type="text/css" media="screen, handheld">
{ADMIN_MENU}
<div id="admin_contents">
	# INCLUDE message_helper #
	
	# START removing_interface #
	<form action="admin_articles_cat.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_REMOVING_CATEGORY}</legend>
					<p>{L_EXPLAIN_REMOVING}</p>

					<label>
						<input type="radio" name="action" value="delete"# IF EMPTY_CATS # checked="checked"# ENDIF # /> {L_DELETE_CATEGORY_AND_CONTENT}
					</label>
					<br /> <br />
					<label>
						<input type="radio" name="action" value="move"# IF EMPTY_CATS # disabled="disabled"# ELSE # checked="checked"# ENDIF # /> {L_MOVE_CONTENT}
					</label>
					&nbsp;
					<select id="{FORM_ID}" name="{FORM_NAME}">
						<option value="0" disabled="disabled">{L_ROOT}</option>
					# START options #
						<option value="{options.ID}" {options.SELECTED_OPTION}>{options.PREFIX} {options.NAME}</option>
					# END options #
					</select>
				</fieldset>

				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="cat_to_del" value="{removing_interface.IDCAT}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				</fieldset>
			</form>
	# END removing_interface #
	# START categories_management #
		<table class="module_table" style="width:99%;">
			<tr>			
				<th colspan="3">
					{categories_management.L_CATS_MANAGEMENT}
				</th>
			</tr>							
			<tr>
				<td style="padding-left:20px;" class="row2">
					<br />
					{categories_management.CATEGORIES}
					<br />
				</td>
			</tr>
		</table>
	# END categories_management #
	# START edition_interface #
	<script type="text/javascript">
	<!--
	function check_form()
	{
		if (document.getElementById('name').value == "")
		{
			alert("{L_REQUIRE_TITLE}");
			return false;
		}

		return true;
	}
	
	function change_icon(img_path)
	{
		document.getElementById('icon_img').innerHTML = '<img src="' + img_path + '" alt="" class="valign_middle" />';
	}
	
	var global_auth = {edition_interface.JS_SPECIAL_AUTH};
	function change_status_global_auth()
	{
		if( global_auth )
			hide_div("hide_special_auth");
		else
			show_div("hide_special_auth");
		global_auth = !global_auth;
	}
	function ajax_models_desc()
	{					
		new Ajax.Request(
			'xmlhttprequest.php',
			{
				method: 'post',
				parameters: {
					model_desc: true,
					models: document.getElementById('models').value,
				 },
				onSuccess: function(response)
				{
					document.getElementById('model_desc').innerHTML = response.responseText;
				}				
			}
		);
	}
	-->
	</script>
	<form action="admin_articles_cat.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
		<fieldset>
			<legend>{L_CATEGORY}</legend>
			<p>{L_REQUIRE}</p>
			<dl>
				<dt>
					<label for="name">
						* {L_NAME}
					</label>
				</dt>
				<dd>
					<input type="text" size="65" maxlength="100" id="name" name="name" value="{edition_interface.NAME}" class="text" />
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="id_parent">
						* {L_LOCATION}
					</label>
				</dt>
				<dd>
					{edition_interface.CATEGORIES_TREE}
				</dd>
			</dl>
			<dl>
				<dt><label for="icon">* {L_CAT_ICON}</label></dt>
				<dd><label>
					<select name="icon" onchange="change_icon(this.options[this.selectedIndex].value)" onclick="change_icon(this.options[this.selectedIndex].value)">
						{edition_interface.IMG_LIST}
					</select>
					<span id="icon_img">{edition_interface.IMG_ICON}</span>
					<br />
					<span class="text_small">{L_OR_DIRECT_PATH}</span> <input size="40" type="text" class="text" name="icon_path" value="{edition_interface.IMG_PATH}" onblur="if( this.value != '' )change_icon(this.value)" />
				</label></dd>
			</dl>
			<label for="description">
				{L_DESCRIPTION}
			</label>
			{KERNEL_EDITOR}
			<textarea id="contents" rows="15" cols="40" name="description">{edition_interface.DESCRIPTION}</textarea>
		</fieldset>
		<fieldset>
			<legend>
				{L_SPECIAL_AUTH}
			</legend>
			<dl>
				<dt><label for="special_auth">{L_SPECIAL_AUTH}</label>
				<br />
				<span class="text_small">{L_SPECIAL_AUTH_EXPLAIN}</span></dt>
				<dd>
					<input type="checkbox" name="special_auth" id="special_auth" onclick="javascript: change_status_global_auth();" {edition_interface.SPECIAL_CHECKED} />
				</dd>					
			</dl>
			<div id="hide_special_auth" style="display:{edition_interface.DISPLAY_SPECIAL_AUTH};">
				<dl>
					<dt>
						<label for="auth_read">{L_AUTH_READ}</label>
					</dt>
					<dd>
						{edition_interface.AUTH_READ}
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="auth_contribution">{L_AUTH_CONTRIBUTION}</label>
					</dt>
					<dd>
						{edition_interface.AUTH_CONTRIBUTION}
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="auth_write">{L_AUTH_WRITE}</label>
					</dt>
					<dd>
						{edition_interface.AUTH_WRITE}
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="auth_moderation">{L_AUTH_MODERATION}</label>
					</dt>
					<dd>
						{edition_interface.AUTH_MODERATION}
					</dd>
				</dl>
			</div>
		</fieldset>
		<fieldset class="fieldset_submit">
			<legend>{L_SUBMIT}</legend>
			<input type="hidden" name="idcat" value="{edition_interface.IDCAT}" />
			<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
			&nbsp;&nbsp;
			<input type="button" name="preview" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="submit" />
			&nbsp;&nbsp;
			<input type="reset" value="{L_RESET}" class="reset" />		
		</fieldset>
	</form>
	<script type="text/javascript">
	<!--
		ajax_models_desc();
	-->
	</script>
	# END edition_interface #	
</div>
