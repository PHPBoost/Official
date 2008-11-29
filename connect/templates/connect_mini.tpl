		# IF C_USER_NOTCONNECTED #		
		<script type="text/javascript">
		<!--
		function check_connect(){
			if(document.getElementById('login').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
			}
			if(document.getElementById('password').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
			}
			return true;
		}
		
		-->
		</script>
				
		<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();">
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_CONNECT}</h5>
			</div>
			<div class="module_mini_contents">
				<p>
					<label>{L_PSEUDO}
					<br />
					<input size="15" type="text" class="text" id="login" name="login" maxlength="25" /></label>
					<br />
					<label>{L_PASSWORD}
					<br />
					<input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label>
					<br />
					<label>{L_AUTOCONNECT} <input checked="checked" type="checkbox" name="auto" /></label>
				</p>
				<p>	
					<input type="submit" name="connect" value="{L_CONNECT}" class="submit" />
				</p>
				<p style="margin:0;margin-top:5px;">
					# IF C_USER_REGISTER # 
					<a class="small_link" href="{PATH_TO_ROOT}/member/register.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/register_mini.png" alt="" class="valign_middle" /> {L_REGISTER}</a> 
					# ENDIF #
					<br />
					<a class="small_link" href="{PATH_TO_ROOT}/member/forget.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/forget_mini.png" alt="" class="valign_middle" /> {L_FORGOT_PASS}</a>
				</p>
			</div>		
			<div class="module_mini_bottom">
			</div>
		</div>				
		</form>	
		# ENDIF #		
		
		
		# IF C_USER_CONNECTED #		
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_PROFIL}</h5>
			</div>
			<div class="module_mini_contents" style="text-align:left;">
				<ul style="margin:0;padding:0;padding-left:4px;list-style-type:none;line-height:18px">
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/member{U_USER_ID}" class="small_link">{L_PRIVATE_PROFIL}</a></li>
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_PM}" class="valign_middle" alt="" /> <a href="{U_USER_PM}" class="small_link">{L_NBR_PM}</a>&nbsp;</li>
					
					# IF C_ADMIN_AUTH # 
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/admin/admin_index.php" class="small_link">{L_ADMIN_PANEL}
						# IF C_UNREAD_ALERT #
							({NUMBER_UNREAD_ALERTS})
						# ENDIF #
					</a></li> 
					# ENDIF #
					
					# IF C_MODERATOR_AUTH # 
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modo_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/moderation_panel.php" class="small_link">{L_MODO_PANEL}</a>&nbsp;
					# ENDIF #
					
					# IF C_UNREAD_CONTRIBUTION #
						# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="small_link">{L_CONTRIBUTION_PANEL} ({NUM_UNREAD_CONTRIBUTIONS})</a>&nbsp;
						# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="small_link">{L_CONTRIBUTION_PANEL}</a>&nbsp;
						# ENDIF #
					# ELSE #
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="small_link">{L_CONTRIBUTION_PANEL}</a>&nbsp;
					# ENDIF #
					
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/home_mini.png" alt="" class="valign_middle" /> <a href="{U_DISCONNECT}" class="small_link">{L_DISCONNECT}</a></li>
				</ul>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		# END ENDIF #
		