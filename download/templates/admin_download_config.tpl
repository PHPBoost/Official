		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('max_files_number_per_page').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('columns_number').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('notation_scale').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		-->
		</script>

		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">							
			<form action="admin_download_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>
						{L_GLOBAL_AUTH}
					</legend>
					{L_GLOBAL_AUTH_EXPLAIN}
					<dl>
						<dt>
							<label>
								{L_READ_AUTH}
							</label>
						</dt>
						<dd>
							{READ_AUTH}
						</dd>					
					</dl>
					<dl>
						<dt>
							<label>
								{L_WRITE_AUTH}
							</label>
						</dt>
						<dd>
							{WRITE_AUTH}
						</dd>					
					</dl>
					<dl>
						<dt>
							<label>
								{L_CONTRIBUTION_AUTH}
							</label>
						</dt>
						<dd>
							{CONTRIBUTION_AUTH}
						</dd>					
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_DOWNLOAD_CONFIG}</legend>
					<dl>
						<dt><label for="max_files_number_per_page">* {L_MAX_FILES_NUMBER_PER_PAGE}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="max_files_number_per_page" name="max_files_number_per_page" value="{MAX_FILES_NUMBER_PER_PAGE}" class="text"></label></dd>
					</dl>
					<dl>
						<dt><label for="columns_number">* {L_COLUMNS_NUMBER}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="columns_number" name="columns_number" value="{COLUMNS_NUMBER}" class="text"></label></dd>
					</dl>
					<dl>
						<dt><label for="notation_scale">* {L_NOTATION_SCALE}</label></dt>
						<dd><label><input type="text" size="2" maxlength="2" id="notation_scale" name="note_max" value="{NOTATION_SCALE}" class="text"></label></dd>
					</dl>
					<label for="contents">
						{L_ROOT_DESCRIPTION}
					</label>
					{KERNEL_EDITOR}
					<textarea id="contents" rows="15" cols="40" name="root_contents">{DESCRIPTION}</textarea>
				</fieldset>
								
				<fieldset class="fieldset_submit">
					<legend>{L_DELETE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset">				
				</fieldset>	
			</form>
		</div>	
		