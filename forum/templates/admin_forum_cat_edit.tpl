		<link href="{MODULE_DATA_PATH}/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
		<script type="text/javascript">
		<!--
			function check_form_list()
			{
				if(document.getElementById('name').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}		
				return true;
			}
			
			var disabled = {DISABLED};				
			function check_select_multiple_ranks(id, start)
			{
				if( !disabled || id == '1r' )			
				{
					id_select = id.replace(/(.*)r/g, '$1');
					check_select_multiple(id_select, false);
					var i;
					for(i = start; i <= 3; i++)
					{
						if( document.getElementById(id + i) )
							document.getElementById(id + i).selected = true;
					}
				}
			}
			function change_parent_cat(value)
			{			
				if( value > 0 )
				{
					disabled = 0;

					var i;
					for(id = 2; id <= 4; id++)
					{
						if( id == 3 )
							id++;
							
						//S�lection des groupes.
						var selectidgroups = document.getElementById('groups_auth' + id);
						for(i = 0; i < selectidgroups.length; i++)
						{	
							if( selectidgroups[i] )
								selectidgroups[i].disabled = '';
						}
						
						//S�lection des membres.
						var selectidmember = document.getElementById('members_auth' + id);
						for(i = 0; i < selectidmember.length; i++)
						{	
							if( selectidmember[i] )
								selectidmember[i].disabled = '';
						}
					}
					document.getElementById('2r1').selected = true;
					document.getElementById('2r2').selected = true;
					document.getElementById('2r3').selected = true;
					document.getElementById('4r2').selected = true;
					document.getElementById('4r3').selected = true;
				}
				else
				{
					document.getElementById('2r3').selected = false;
					document.getElementById('4r3').selected = false;
					disabled = 1;
					var i;
					
					for(id = 2; id <= 4; id++)
					{
						if( id == 3 )
							id++;
							
						//S�lection des groupes.
						var selectidgroups = document.getElementById('groups_auth' + id);
						for(i = 0; i < selectidgroups.length; i++)
						{	
							if( selectidgroups[i] )
							{	
								selectidgroups[i].disabled = 'disabled';
								selectidgroups[i].selected = false;
							}
						}
						
						//S�lection des membres.
						var selectidmember = document.getElementById('members_auth' + id);
						for(i = 0; i < selectidmember.length; i++)
						{	
							if( selectidmember[i] )
							{
								selectidmember[i].disabled = 'disabled';
								selectidmember[i].selected = false;
							}
						}
					}
				}
			}
		-->
		</script>
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FORUM_MANAGEMENT}</li>
				<li>
					<a href="admin_forum.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum.php" class="quick_link">{L_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_forum_add.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_add.php" class="quick_link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_forum_config.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_config.php" class="quick_link">{L_FORUM_CONFIG}</a>
				</li>
				<li>
					<a href="admin_forum_groups.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_groups.php" class="quick_link">{L_FORUM_GROUPS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
					
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
				
			<form action="admin_forum.php?id={ID}" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_CAT}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="100" size="35" id="name" name="name" value="{NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="category">* {L_PARENT_CATEGORY}</label></dt>
						<dd><label>
							<select name="category" id="category" onchange="change_parent_cat(this.options[this.selectedIndex].value)">
								{CATEGORIES}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="desc">{L_DESC}</label></dt>
						<dd><label><input type="text" maxlength="150" size="55" name="desc" id="desc" value="{DESC}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="aprob">{L_APROB}</label></dt>
						<dd>
							<label><input type="radio" name="aprob" id="aprob" {CHECKED_APROB} value="1" /> {L_YES}</label>
							<label><input type="radio" name="aprob" {UNCHECKED_APROB} value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="status">{L_STATUS}</label></dt>
						<dd>
							<label><input type="radio" name="status" id="status" {CHECKED_STATUS} value="1" /> {L_UNLOCK}</label>
							<label><input type="radio" name="status" {UNCHECKED_STATUS} value="0" /> {L_LOCK}</label>
						</dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_READ}</label></dt>
						<dd><label>{AUTH_READ}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_WRITE}</label></dt>
						<dd><label>{AUTH_WRITE}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_EDIT}</label></dt>
						<dd><label>{AUTH_EDIT}</label></dd>
					</dl>
				</fieldset>
								
				<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
		</div>
