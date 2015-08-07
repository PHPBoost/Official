		<script>
		<!--
			function img_change(id, url)
			{
				if (document.getElementById(id))
					document.getElementById(id).src = "{PATH_TO_ROOT}/forum/templates/images/ranks/" + url;
			}
		-->
		</script>
		
		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_FORUM_MANAGEMENT}</li>
				<li>
					<a href="${relative_url(ForumUrlBuilder::manage_categories())}"><img src="forum.png" alt="" /></a>
					<br />
					<a href="${relative_url(ForumUrlBuilder::manage_categories())}" class="quick-link">{L_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="${relative_url(ForumUrlBuilder::add_category())}"><img src="forum.png" alt="" /></a>
					<br />
					<a href="${relative_url(ForumUrlBuilder::add_category())}" class="quick-link">{L_ADD_CAT}</a>
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
			<form action="admin_ranks.php?token={TOKEN}" method="post">
				<fieldset>
					<legend>{L_FORUM_RANKS_MANAGEMENT}</legend>
					<table>
						<thead>
							<tr> 
								<th>
									{L_RANK_NAME}
								</th>
								<th>
									{L_NBR_MSG}
								</th>
								<th>
									{L_IMG_ASSOC}
								</th>
								<th>
									{L_DELETE}
								</th>
							</tr>
						</thead>
						<tbody>
							# START rank #
							<tr>
								<td> 
									<input type="text" maxlength="30" size="20" name="{rank.ID}name" value="{rank.RANK}">
								</td>
								<td>
									{rank.MSG}
								</td>
								<td>
									<select name="{rank.ID}icon" onchange="img_change('icon{rank.ID}', this.options[selectedIndex].value)">
										{rank.RANK_OPTIONS}
									</select>
									<br />
									# IF rank.IMG_RANK # <img src="{PATH_TO_ROOT}/forum/templates/images/ranks/{rank.IMG_RANK}" id="icon{rank.ID}" alt="" /> # ENDIF #
								</td>
								<td>
									{rank.DELETE}
								</td>
							</tr>
							# END rank #
						</tbody>
					</table>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form>
		</div>
		