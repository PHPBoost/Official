		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_FORUM_MANAGEMENT}</li>
				<li>
					<a href="admin_forum.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum.php" class="quick-link">{L_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_forum_add.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_add.php" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_ranks.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks.php" class="quick-link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks_add.php" class="quick-link">{L_FORUM_ADD_RANKS}</a>
				</li>
				<li>
					<a href="${relative_url(ForumUrlBuilder::configuration())}"><img src="forum.png" alt="" /></a>
					<br />
					<a href="${relative_url(ForumUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
				</li>
			</ul>
		</div>

		<div id="admin-contents">
			
			<form method="post" action="admin_forum.php?del={IDCAT}&amp;token={TOKEN}" onsubmit="javascript:return check_form_select();" class="fieldset-content">
				# START topics #
				<fieldset>
					<legend>{topics.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<i class="fa fa-notice fa-2x"></i> &nbsp;{topics.L_EXPLAIN_CAT}
						<br />	
						<br />	
					</div>
					<br />	
					<div class="form-element">
						<label for="t_to">{topics.L_MOVE_TOPICS}</label>
						<div class="form-field"><label>
							<select id="t_to" name="t_to">
								{topics.FORUMS}
							</select>
						</label></div>
					</div>
				</fieldset>			
				# END topics #
				
				# START subforums #
				<fieldset>
					<legend>{subforums.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<i class="fa fa-notice"></i> &nbsp;{subforums.L_EXPLAIN_CAT}
						<br />	
						<br />	
					</div>
					<br />	
					<div class="form-element">
						<label for="f_to">{subforums.L_MOVE_FORUMS}</label>
						<div class="form-field"><label>
							<select id="f_to" name="f_to">
								{subforums.FORUMS}
							</select>
						</label></div>
					</div>
				</fieldset>			
				# END subforums #
				
				<fieldset>
					<legend>{L_DEL_ALL}</legend>
					<div class="form-element">
						<label for="del_conf">{L_DEL_FORUM_CONTENTS}</label>
						<div class="form-field"><label><input type="checkbox" name="del_conf" id="del_conf"></label></div>
					</div>
				</fieldset>	
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<button type="submit" name="del_cat" value="true" class="submit">{L_SUBMIT}</button>
				</fieldset>
			</form>
		</div>
		