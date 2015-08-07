		<script>
			<!--
			function check_form_conf()
			{
				if(document.getElementById('cookie_lenght').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				if(document.getElementById('cookie_name').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				return true;
			}
			function select_displayed_polls_in_mini(id, status)
			{
				var i;
				
				for(i = 0; i < {NBR_POLL}; i++)
				{
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = status;
				}
			}
			-->
			</script>
			
		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_POLL_MANAGEMENT}</li>
				<li>
					<a href="admin_poll.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll.php" class="quick-link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_add.php" class="quick-link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_config.php" class="quick-link">{L_POLL_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin-contents">
			<form action="admin_poll_config.php?token={TOKEN}" method="post" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_POLL_CONFIG_MINI}</legend>
					<div class="form-element">
						<label for="displayed_in_mini_module_list">{L_DISPLAYED_IN_MINI_MODULE_LIST} <span class="field-description">{L_DISPLAYED_IN_MINI_MODULE_LIST_EXPLAIN}</span></label>
						<div class="form-field"><label>
							<select id="displayed_in_mini_module_list" name="displayed_in_mini_module_list[]" size="5" multiple="multiple">
								{POLL_LIST}
							</select>
							<br />
							<a class="small" href="javascript:select_displayed_polls_in_mini('displayed_in_mini_module_list', true);">{L_SELECT_ALL}</a>/<a class="small" href="javascript:select_displayed_polls_in_mini('displayed_in_mini_module_list', false);">{L_SELECT_NONE}</a>
						</label></div>
					</div>
				</fieldset>
				<fieldset>
					<legend>{L_POLL_CONFIG_ADVANCED}</legend>
					<div class="form-element">
						<label for="cookie_name">* {L_COOKIE_NAME}</label>
						<div class="form-field"><input type="text" maxlength="25" size="25" name="cookie_name" id="cookie_name" value="{COOKIE_NAME}"></div>
					</div>
					<div class="form-element">
						<label for="cookie_lenght">* {L_COOKIE_LENGHT}</label>
						<div class="form-field"><input type="text" maxlength="11" size="6" name="cookie_lenght" id="cookie_lenght" value="{COOKIE_LENGHT}"> {L_DAYS}</div>
					</div>
					<div class="form-element">
						<label for="display_results_before_polls_end">{L_DISPLAY_RESULTS_BEFORE_POLLS_END}</label>
						<div class="form-field"><input type="checkbox" name="display_results_before_polls_end"# IF C_DISPLAY_RESULTS_BEFORE_POLLS_END # checked="checked"# ENDIF #></div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						{L_AUTHORIZATIONS}
					</legend>
					<div class="form-element">
						<label>
							{L_READ_AUTHORIZATION}
						</label>
						<div class="form-field">
							{READ_AUTHORIZATION}
						</div>
					</div>
					<div class="form-element">
						<label>
							{L_WRITE_AUTHORIZATION}
						</label>
						<div class="form-field">
							{WRITE_AUTHORIZATION}
						</div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
		