<ul>
	<li onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/admin_mini.png" class="valign_middle" alt="" /> {L_INDEX}</h5>
		<ul id="smenu1">
			<li><a href="{U_INDEX_SITE}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/admin_mini.png);">{L_INDEX_SITE}</a></li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_index.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png);">{L_ADMINISTRATION}</a></li>
			<li class="separator"></li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_index.php?disconnect=true&amp;token={TOKEN}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/home_mini.png);">{L_DISCONNECT}</a></li>
			# IF C_ADMIN_LINKS_1 #
			<li class="separator"></li>
				# START admin_links_1 #
					# IF admin_links_1.C_ADMIN_LINKS_EXTEND #
			<li class="extend" onmouseover="show_menu(1{admin_links_1.IDMENU}, 1);" onmouseout="hide_menu(1);">
				<a href="{admin_links_1.U_ADMIN_MODULE}" style="background-image:url({admin_links_1.IMG});">{admin_links_1.NAME}</a>
				<ul id="ssmenu1{admin_links_1.IDMENU}">
					{admin_links_1.LINKS}
				</ul>
			</li>
					# ELSE #
			<li><a href="{admin_links_1.U_ADMIN_MODULE}" style="background-image:url({admin_links_1.IMG});">{admin_links_1.NAME}</a></li>
					# ENDIF #
				# END admin_links_1 #
			# ENDIF #
		</ul>
	</li>
	<li onmouseover="show_menu(2, 0);" onmouseout="hide_menu(0);">
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png" class="valign_middle" alt="" /> {L_ADMINISTRATION}</h5>
		<ul id="smenu2">
			<li class="extend" onmouseover="show_menu(21, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(AdminConfigUrlBuilder::general_config())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/config_mini.png);">{L_CONFIGURATION}</a>
				<ul id="ssmenu21">
					<li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/config_mini.png);">{L_CONFIG_GENERAL}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/config/?url=/advanced/" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/config_mini.png);">{L_CONFIG_ADVANCED}</a></li>
					<li><a href="${relative_url(AdminConfigUrlBuilder::mail_config())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/config_mini.png);">{L_MAIL_CONFIG}</a></li>
				</ul>
			</li>
			<li class="extend" onmouseover="show_menu(22, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/themes_mini.png);">{L_THEMES}</a>
				<ul id="ssmenu22">
					<li><a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/themes_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/themes_mini.png);">{L_ADD}</a></li>
				</ul>
			</li>
			<li class="extend" onmouseover="show_menu(23, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/languages_mini.png);">{L_LANGS}</a>
				<ul id="ssmenu23">
					<li><a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/languages_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminLangsUrlBuilder::install())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/languages_mini.png);">{L_ADD}</a></li>
				</ul>
			</li>
			<li class="extend" onmouseover="show_menu(24, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/admin_smileys.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys_mini.png);">{L_SMILEY}</a>
				<ul id="ssmenu24">
					<li><a href="{PATH_TO_ROOT}/admin/admin_smileys.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_smileys_add.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys_mini.png);">{L_ADD}</a></li>
				</ul>
			</li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_alerts.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/administrator_alert_mini.png);">{L_ADMINISTRATOR_ALERTS}</a></li>
			# IF C_ADMIN_LINKS_2 #
			<li class="separator"></li>
				# START admin_links_2 #
					# IF admin_links_2.C_ADMIN_LINKS_EXTEND #
			<li class="extend" onmouseover="show_menu(2{admin_links_2.IDMENU}, 1);" onmouseout="hide_menu(1);">
				<a href="{admin_links_2.U_ADMIN_MODULE}" style="background-image:url({admin_links_2.IMG});">{admin_links_2.NAME}</a>
				<ul id="ssmenu2{admin_links_2.IDMENU}">
					{admin_links_2.LINKS}
				</ul>
			</li>
					# ELSE #
			<li><a href="{admin_links_2.U_ADMIN_MODULE}" style="background-image:url({admin_links_2.IMG});">{admin_links_2.NAME}</a></li>
					# ENDIF #
				# END admin_links_2 #
			# ENDIF #
		</ul>
	</li>
	<li onmouseover="show_menu(3, 0);" onmouseout="hide_menu(0);">
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/tools_mini.png" class="valign_middle" alt="" /> {L_TOOLS}</h5>
		<ul id="smenu3">			
            <li class="extend" onmouseover="show_menu(31, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/updates/updates.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/updater_mini.png);">{L_UPDATES}</a>
				<ul id="ssmenu31">
					<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=kernel" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration_mini.png);">{L_KERNEL}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=module" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/modules_mini.png);">{L_MODULES}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=template" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/themes_mini.png);">{L_THEMES}</a></li>
				</ul>
			</li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_maintain.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/maintain_mini.png);">{L_MAINTAIN}</a></li>
			<li class="extend" onmouseover="show_menu(32, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/cache_mini.png);">{L_CACHE}</a>
				<ul id="ssmenu32">
					<li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/cache_mini.png);">{L_CACHE}</a></li>
					<li><a href="${relative_url(AdminCacheUrlBuilder::clear_syndication_cache())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/rss_mini.png);">{L_SYNDICATION_CACHE}</a></li>
					<li><a href="${relative_url(AdminCacheUrlBuilder::clear_css_cache())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/themes_mini.png);">{L_CSS_CACHE_CONFIG}</a></li>
					<li><a href="${relative_url(AdminCacheUrlBuilder::configuration())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/config_mini.png);">{L_CONFIGURATION}</a></li>
				</ul>
			</li>		
			<li class="extend" onmouseover="show_menu(34, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/errors_mini.png);">{L_ERRORS}</a>
				<ul id="ssmenu34">
					<li><a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/errors_mini.png);">{L_ERRORS}</a></li>
					<li><a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/errors_mini.png);">{L_404_ERRORS}</a></li>
				</ul>
			</li><li class="extend" onmouseover="show_menu(33, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/admin_system_report.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/server_mini.png);">{L_SERVER}</a>
				<ul id="ssmenu33">
					<li><a href="{PATH_TO_ROOT}/admin/admin_phpinfo.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/phpinfo_mini.png);">{L_PHPINFO}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_system_report.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/system_report_mini.png);">{L_SYSTEM_REPORT}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_3 #
			<li class="separator"></li>
				# START admin_links_3 #
					# IF admin_links_3.C_ADMIN_LINKS_EXTEND #
			<li class="extend" onmouseover="show_menu(3{admin_links_3.IDMENU}, 1);" onmouseout="hide_menu(1);">
				<a href="{admin_links_3.U_ADMIN_MODULE}" style="background-image:url({admin_links_3.IMG});">{admin_links_3.NAME}</a>
				<ul id="ssmenu3{admin_links_3.IDMENU}">
					{admin_links_3.LINKS}
				</ul>
			</li>
					# ELSE #
			<li><a href="{admin_links_3.U_ADMIN_MODULE}" style="background-image:url({admin_links_3.IMG});">{admin_links_3.NAME}</a></li>
					# ENDIF #
				# END admin_links_3 #
			# ENDIF #
		</ul>
	</li>
	<li onmouseover="show_menu(4, 0);" onmouseout="hide_menu(0);">
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/groups_mini.png" class="valign_middle" alt="" /> {L_USER}</h5>
		<ul id="smenu4">
			<li class="extend" onmouseover="show_menu(41, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/admin_members.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png);">{L_USER}</a>
				<ul id="ssmenu41">
					<li><a href="{PATH_TO_ROOT}/admin/admin_members.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminMembersUrlBuilder::add())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png);">{L_ADD}</a></li>
					<li><a href="${relative_url(AdminMembersUrlBuilder::configuration())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png);">{L_CONFIGURATION}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_members_punishment.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png);">{L_PUNISHEMENT}</a></li>
				</ul>
			</li>								
			<li class="extend" onmouseover="show_menu(42, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/admin_groups.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/groups_mini.png);">{L_GROUP}</a>
				<ul id="ssmenu42">
					<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/groups_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php?add=1" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/groups_mini.png);">{L_ADD}</a></li>
				</ul>
			</li>
			<li class="extend" onmouseover="show_menu(43, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/extended-fields/list/" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield_mini.png);">{L_EXTEND_FIELD}</a>
				<ul id="ssmenu43">
					<li><a href="{PATH_TO_ROOT}/admin/member/?url=/extended-fields/list/" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/member/?url=/extended-fields/add/" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield_mini.png);">{L_ADD}</a></li>
				</ul>
			</li>
			<li class="extend" onmouseover="show_menu(44, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/admin_ranks.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png);">{L_RANKS}</a>
				<ul id="ssmenu44">
					<li><a href="{PATH_TO_ROOT}/admin/admin_ranks.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_ranks_add.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png);">{L_ADD}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_4 #
			<li class="separator"></li>
				# START admin_links_4 #
					# IF admin_links_4.C_ADMIN_LINKS_EXTEND #
			<li class="extend" onmouseover="show_menu(4{admin_links_4.IDMENU}, 1);" onmouseout="hide_menu(1);">
				<a href="{admin_links_4.U_ADMIN_MODULE}" style="background-image:url({admin_links_4.IMG});">{admin_links_4.NAME}</a>
				<ul id="ssmenu4{admin_links_4.IDMENU}">
					{admin_links_4.LINKS}
				</ul>
			</li>
					# ELSE #
			<li><a href="{admin_links_4.U_ADMIN_MODULE}" style="background-image:url({admin_links_4.IMG});">{admin_links_4.NAME}</a></li>
					# ENDIF #
				# END admin_links_4 #
			# ENDIF #
		</ul>
	</li>
	<li onmouseover="show_menu(5, 0);" onmouseout="hide_menu(0);">
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/contents_mini.png" class="valign_middle" alt="" /> {L_CONTENT}</h5>
		<ul id="smenu5">
			<li><a href="{PATH_TO_ROOT}/admin/admin_content_config.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/content_mini.png);">{L_CONTENT_CONFIG}</a></li>
			<li class="extend" onmouseover="show_menu(51, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/menus/menus.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/menus_mini.png);">{L_MENUS}</a>
				<ul id="ssmenu51">
					<li><a href="{PATH_TO_ROOT}/admin/menus/menus.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/menus_mini.png);">{L_MANAGEMENT}</a></li>
                       <li><a href="{PATH_TO_ROOT}/admin/menus/links.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/menus_mini.png);">{L_ADD_LINKS_MENU}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/menus/content.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/menus_mini.png);">{L_ADD_CONTENT_MENU}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/menus/feed.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/menus_mini.png);">{L_ADD_FEED_MENU}</a></li>
                   </ul>
			</li>
			<li class="extend" onmouseover="show_menu(52, 1);" onmouseout="hide_menu(1);">
				<a href="{PATH_TO_ROOT}/admin/admin_files.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/files_mini.png);">{L_FILES}</a>
				<ul id="ssmenu52">
					<li><a href="{PATH_TO_ROOT}/admin/admin_files.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/files_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_files_config.php" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/files_mini.png);">{L_CONFIGURATION}</a></li>
				</ul>
			</li>								
			<li class="extend" onmouseover="show_menu(53, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(UserUrlBuilder::comments())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png);">{L_COMMENTS}</a>
				<ul id="ssmenu53">
					<li><a href="${relative_url(UserUrlBuilder::comments())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/content/?url=/comments/config/" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png);">{L_CONFIGURATION}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_5 #
			<li class="separator"></li>
				# START admin_links_5 #
					# IF admin_links_5.C_ADMIN_LINKS_EXTEND #
			<li class="extend" onmouseover="show_menu(5{admin_links_5.IDMENU}, 1);" onmouseout="hide_menu(1);">
				<a href="{admin_links_5.U_ADMIN_MODULE}" style="background-image:url({admin_links_5.IMG});">{admin_links_5.NAME}</a>
				<ul id="ssmenu5{admin_links_5.IDMENU}">
					{admin_links_5.LINKS}
				</ul>
			</li>
					# ELSE #
			<li><a href="{admin_links_5.U_ADMIN_MODULE}" style="background-image:url({admin_links_5.IMG});">{admin_links_5.NAME}</a></li>
					# ENDIF #
				# END admin_links_5 #
			# ENDIF #
		</ul>
	</li>
	<li onmouseover="show_menu(6, 0);" onmouseout="hide_menu(0);">
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules_mini.png" class="valign_middle" alt="" /> {L_MODULES}</h5>
		<ul id="smenu6">
			<li class="extend" onmouseover="show_menu(61, 1);" onmouseout="hide_menu(1);">
				<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/modules_mini.png);">{L_MODULES}</a>
				<ul id="ssmenu61"> 
					<li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/modules_mini.png);">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminModulesUrlBuilder::add_module())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/modules_mini.png);">{L_ADD}</a></li>
					<li><a href="${relative_url(AdminModulesUrlBuilder::update_module())}" style="background-image:url({PATH_TO_ROOT}/templates/{THEME}/images/admin/modules_mini.png);">{L_UPDATES}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_6 #
			<li class="separator"></li>
				# START admin_links_6 #
					# IF admin_links_6.C_ADMIN_LINKS_EXTEND #
			<li class="extend" onmouseover="show_menu(6{admin_links_6.IDMENU}, 1);" onmouseout="hide_menu(1);">
				<a href="{admin_links_6.U_ADMIN_MODULE}" style="background-image:url({admin_links_6.IMG});">{admin_links_6.NAME}</a>
				<ul id="ssmenu6{admin_links_6.IDMENU}">
					{admin_links_6.LINKS}
				</ul>
			</li>
					# ELSE #
			<li><a href="{admin_links_6.U_ADMIN_MODULE}" style="background-image:url({admin_links_6.IMG});">{admin_links_6.NAME}</a></li>
					# ENDIF #
				# END admin_links_6 #
			# ENDIF #
		</ul>
	</li>
</ul>
