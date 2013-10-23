		# INCLUDE forum_top #
		
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('search').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			return true;
		}
		-->
		</script>
		<div class="module_position forum_position_cat">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top forum_top_cat">
				&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT}
			</div>

					<form action="search.php?token={TOKEN}#search_forum" method="post" onsubmit="return check_form();" >
						<div class="forum_position_subcat">
							<div class="module_contents forum_contents forum_contents_subcat" style="background: #F9F9F9;">
								<fieldset style="margin:10px 20px;">
									<legend>{L_SEARCH_FORUM}</legend>
									<dl>
										<dt><label for="search_forum_form">{L_KEYWORDS}</label></dt>
										<dd><label><input type="text" size="35" id="search_forum_form" name="search" value="{SEARCH}"  class="text"></label></dd>
									</dl>
									<dl>
										<dt><label for="time">{L_DATE}</label></dt>
										<dd><label>	
											<select id="time" name="time">
												<option value="30000" selected="selected">{L_ALL}</option>
												<option value="1">1 {L_DAY}</option>
												<option value="7">7 {L_DAYS}</option>
												<option value="15">15 {L_DAYS}</option>
												<option value="30">1 {L_MONTH}</option>
												<option value="180">6 {L_MONTH}</option>
												<option value="360">1 {L_YEAR}</option>
											</select>
										</label></dd>
									</dl>
									<dl>
										<dt><label for="idcat">{L_CATEGORY}</label></dt>
										<dd><label>
											<select name="idcat" id="idcat">
												# START cat #
													{cat.CAT}
												# END cat #
											</select>
										</label></dd>
									</dl>
									<dl>
										<dt><label for="where">{L_OPTIONS}</label></dt>
										<dd>
											<label><input type="radio" name="where" id="where" value="contents" {CONTENTS_CHECKED}> {L_CONTENTS}</label>
											<br />
											<label><input type="radio" name="where" value="title" {TITLE_CHECKED}> {L_TITLE}</label>
											<br />
											<label><input type="radio" name="where" value="all" {ALL_CHECKED}> {L_TITLE}/{L_CONTENTS}</label>
										</dd>
									</dl>
									<dl>
										<dt><label for="colorate_result">{L_COLORATE_RESULT}</label></dt>
										<dd>
											<label><input type="checkbox" name="colorate_result" id="colorate_result" value="1" {COLORATE_RESULT}></label>
										</dd>
									</dl>
								</fieldset>			
								<fieldset class="fieldset_submit" style="margin-bottom:0px;">
									<legend>{L_SEARCH}</legend>
									<button type="submit" name="valid_search" value="true">{L_SEARCH}</button>			
								</fieldset>
							</div>	
						</div>
						
						<div class="forum_position_subcat">
							<div class="forum_position_subcat-bottom"></div>
						</div>
						
						<p><span id="search_forum"></span></p>
						# INCLUDE message_helper #
						
						# IF C_FORUM_SEARCH #
						<div class="module_position" style="width:100%;">
							<div class="module_top_l"></div>
							<div class="module_top_r"></div>
							<div class="module_top">&nbsp;</div>
						</div>
						# ENDIF #
						# START list #
						<div class="msg_position" style="width:100%;">					
							<div class="msg_container">
								<div class="msg_top_row">
									<div class="msg_pseudo_mbr">
										{list.USER_ONLINE} {list.USER_PSEUDO}
									</div>
									<span class="text_strong" style="float:left;">&nbsp;&nbsp;{L_TOPIC}: {list.U_TITLE}</span>
									<span class="smaller" style="float: right;">{L_ON}: {list.DATE}</span>&nbsp;
								</div>
								<div class="msg_contents_container">
									<div class="msg_info_mbr">
									</div>
									<div class="msg_contents">
										<div class="msg_contents_overflow">
											{list.CONTENTS}
										</div>									
									</div>
								</div>
							</div>	
							<div class="msg_bottom_l"></div>		
							<div class="msg_bottom_r"></div>
							<div class="msg_bottom"><span class="smaller">{L_RELEVANCE}: {list.RELEVANCE}%</span></div>
						</div>
						# END list #
					</form>			

			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom forum_top_cat">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		