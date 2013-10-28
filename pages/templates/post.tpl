		<script type="text/javascript">
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {SELECTED_CAT};
			function check_form_post(){
				# IF C_BBCODE_TINYMCE_MODE #
					tinyMCE.triggerSave();
				# ENDIF #
			
				if(document.getElementById('title') && document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				if(document.getElementById('contents').value == "") {
					alert("{L_ALERT_CONTENTS}");
					return false;
				}
				return true;
			}
			var disabled = {OWN_AUTH_DISABLED};
			function disable_own_auth()
			{
				if( disabled )
				{
					disabled = false;
					document.getElementById("own_auth_display").style.display = 'block';
				}
				else
				{
					document.getElementById("own_auth_display").style.display = 'none';
					disabled = true;
				}
			}
		-->
		</script>

		<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/pages.js"></script>
	
		# IF C_ERROR_HANDLER #
			<div class="{ERRORH_CLASS}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				<br />
			</div>
			<br />
		# ENDIF #
		
		# START previewing #
		<article>					
			<header>
				<h1>{L_PREVIEWING} {previewing.TITLE}</h1>
			</header>
			<div class="content">{previewing.PREVIEWING}</div>
			<footer></footer>
		</article>
		# END previewing #
			
		<form action="{TARGET}" method="post"  onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{L_TITLE_POST}</legend>
				# START create #
				<div class="form-element">
					<label for="title">* {L_TITLE_FIELD}</label>
					<div class="form-field"><label><input type="text" class="text" id="title" name="title" size="70" maxlength="250" value="{PAGE_TITLE}"></label></div>					
				</div>
				# END create #
				<div class="form-element-textarea">
					<label for="contents">* {L_CONTENTS}</label>
					{KERNEL_EDITOR}
					<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
				</div>
			</fieldset>	
			
			<fieldset>
				<legend>{L_PATH}</legend>
				<div class="form-element">
					<label for="is_cat">{L_IS_CAT}</label>
					<div class="form-field"><label><input type="checkbox" name="is_cat" id="is_cat" {CHECK_IS_CAT}></label></div>					
				</div>
				<div class="form-element">
					<label>{L_CAT}</label>
					<div class="form-field">
						<input type="hidden" name="id_cat" id="id_cat" value="{ID_CAT}"/>
						<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{CAT_0}">{L_ROOT}</span></a></span>
						<br />
						<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">
						{CAT_LIST}
						</ul>
					</div>					
				</div>
			</fieldset>
			
			<fieldset>
				<legend>{L_PROPERTIES}</legend>
				<div class="form-element">
					<label for="count_hits">{L_COUNT_HITS}</label>
					<div class="form-field"><label><input type="checkbox" id="count_hits" name="count_hits" {COUNT_HITS_CHECKED}></label></div>					
				</div>
				<div class="form-element">
					<label for="comments_activated">{L_COMMENTS_ACTIVATED}</label>
					<div class="form-field"><label><input type="checkbox" id="comments_activated" name="comments_activated" {COMMENTS_ACTIVATED_CHECKED}></label></div>					
				</div>
				<div class="form-element">
					<label for="display_print_link">{L_DISPLAY_PRINT_LINK}</label>
					<div class="form-field"><label><input type="checkbox" id="display_print_link" name="display_print_link" {DISPLAY_PRINT_LINK_CHECKED}></label></div>					
				</div>
			</fieldset>
			
			<fieldset>
				<legend>{L_AUTH}</legend>
				<div class="form-element">
					<label for="own_auth">{L_OWN_AUTH}</label>
					<div class="form-field"><label><input type="checkbox" name="own_auth" id="own_auth" onclick="disable_own_auth();" {OWN_AUTH_CHECKED}></label></div>					
				</div>
				<span id="own_auth_display" style="{DISPLAY}">
					<div class="form-element">
						<label>{L_READ_PAGE}</label>
						<div class="form-field">{SELECT_READ_PAGE}</div>					
					</div>
					<div class="form-element">
						<label>{L_EDIT_PAGE}</label>
						<div class="form-field">{SELECT_EDIT_PAGE}</div>					
					</div>
					<div class="form-element">
						<label>{L_READ_COM}</label>
						<div class="form-field">{SELECT_READ_COM}</div>					
					</div>
				</span>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_edit" value="{ID_EDIT}">
				<button type="submit">{L_SUBMIT}</button>
				<button type="submit" name="preview">{L_PREVIEW}</button>
				<button type="reset">{L_RESET}</button>
			</fieldset>
		</form>