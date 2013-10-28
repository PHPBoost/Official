		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_NAME}");
				return false;
		    }
			if(document.getElementById('idcat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
			}
			if(document.getElementById('url').value == "") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
			
			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick_link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick_link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick_link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick_link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">
			# START web #
			<table class="module_table">
					<tr> 
						<th colspan="2">
							{L_PREVIEW}
						</th>
					</tr>
					<tr> 
						<td>
							<br />
							<article>
								<header>
									<h1>{web.NAME}</h1>
								</header>
								<div class="content">
									<p>					
										<strong>{web.L_DESC}:</strong> {web.CONTENTS}									
											<br /><br />									
											<strong>{web.L_CATEGORY}:</strong> 
											<a href="{PATH_TO_ROOT}/web/web.php?cat={web.IDCAT}" title="{web.CAT}">{web.CAT}</a><br />
											
											<strong>{web.L_DATE}:</strong> {web.DATE}<br />									
											<strong>{web.L_VIEWS}:</strong> {COMPT}
									</p>
									<p style="text-align: center;">					
										<a href="{web.URL}"><img src="{PICTURES_DATA_PATH}/images/{LANG}/bouton_url.gif" alt="" title="" /></a>
									</p>
								</div>
								<footer></footer>
							</article>
							<br />
						</td>
					</tr>
			</table>	

			<br /><br /><br />
			# END web #

			<form action="admin_web.php?token={TOKEN}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_LINK}</legend>
					<p>{L_REQUIRE}</p>
					<div class="form-element">
						<label for="name">* {L_TITLE}</label>
						<div class="form-field"><label><input type="text" size="55" maxlength="50" name="name" id="name" value="{NAME}" class="text"></label></div>
					</div>
					<div class="form-element">
						<label for="idcat">* {L_CATEGORY}</label>
						<div class="form-field"><label>
							<select id="idcat" name="idcat">				
							# START select #				
								{select.CAT}				
							# END select #				
							</select>
						</label></div>
					</div>
					<div class="form-element">
						<label for="url">* {L_URL_LINK}</label>
						<div class="form-field"><label><input type="text" size="65" id="url" name="url" id="url" value="{URL}" class="text"></label></div>
					</div>
					<div class="form-element">
						<label for="compt">{L_VIEWS}</label>
						<div class="form-field"><label><input type="text" size="10" maxlength="10" name="compt" id="compt" value="{COMPT}" class="text"></label></div>
					</div>
					<br />
					<label for="contents">{L_DESC}</label>
					<label>
						{KERNEL_EDITOR}
						<textarea rows="20" cols="90" id="contents" name="contents">{CONTENTS}</textarea> 
						<br />
					</label>
					<div class="form-element">
						<label for="aprob">* {L_APROB}</label>
						<div class="form-field">
							<label><input type="radio" {APROB_ENABLED} name="aprob" id="aprob" value="1"> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {APROB_DISABLED}  name="aprob" value="0"> {L_NO}</label>
						</div>
					</div>
				</fieldset>		
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{IDWEB}">
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="submit" name="previs" value="true">{L_PREVIEW}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>	
			</form>
		</div>
		