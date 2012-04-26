<script type="text/javascript">
<!--
function Confirm_menu() {
	return confirm("{L_CONFIRM_DEL_MENU}");
}

var delay = 1200; //D�lai apr�s lequel le bloc est automatiquement masqu�, apr�s le d�part de la souris.
var timeout;
var displayed = false;
var previous = '';
var started = false;

//Affiche le bloc.
function menu_display_block(divID)
{
	if( timeout )
		clearTimeout(timeout);
	
	if( document.getElementById(previous) )
	{		
		document.getElementById(previous).style.display = 'none';
		started = false
	}	

	if( document.getElementById('move' + divID) )
	{			
		document.getElementById('move' + divID).style.display = 'block';
		previous = 'move' + divID;
		started = true;
	}
}
//Cache le bloc.
function menu_hide_block(idfield, stop)
{
	if( stop && timeout )
		clearTimeout(timeout);
	else if( started )
		timeout = setTimeout('menu_display_block()', delay);
}
function minimize_container(input, containerName) 
{
	var container = document.getElementById('mod_' + containerName);
	
	if (!container)
		return;

	if(input.checked == false)
	{
		container.style.opacity = 0.5;
		container.style.filter='alpha(opacity=50)';
	}
	else
	{
		container.style.opacity = 1;
		container.style.filter='alpha(opacity=100)';
	}
}

//Drag'n drop
var menusContainerList = new Array(
	'mod_header',
	'mod_subheader',
	'mod_left',
	'mod_right',
	'mod_topcentral',
	'mod_central',
	'mod_bottomcentral',
	'mod_topfooter',
	'mod_footer'
);
function build_menu_tree() 
{
	var containerListLength = menusContainerList.length;
	for(var i = 0; i < containerListLength; i++)
	{
		document.getElementById('menu_tree').value += Sortable.serialize(menusContainerList[i]);
	}
}
function createSortableMenu() 
{
	var containerListLength = menusContainerList.length;
	for(var i = 0; i < containerListLength; i++)
	{
		Sortable.create(
			menusContainerList[i], 
			{
				tag:'div',
				containment:['mod_header','mod_subheader','mod_left','mod_right','mod_topcentral','mod_central','mod_bottomcentral','mod_topfooter','mod_footer'],
				constraint:false,
				scroll:window,
				format:/^menu_([0-9]+)$/,
				dropOnEmpty: true
			}
		);   
	}
}
-->
</script>


<div id="admin_contents admin_contents_no_column">
	<form action="menus.php?action=save" method="post" onsubmit="build_menu_tree();">
		<table class="module_table" style="background:#f4f4f4;width:99%;margin:auto;padding-bottom:25px;">
			<tr>
				<td colspan="3" style="padding:10px;" class="row2">
					<div style="float:right;">
						<strong>{L_THEME_MANAGEMENT} :</strong> 
						<select name="switchtheme" onchange="document.location = '?token={TOKEN}&amp;theme=' + this.options[this.selectedIndex].value;">
							# START themes #
								<option value="{themes.IDNAME}" {themes.SELECTED} >{themes.NAME}</option>
							# END themes #
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td id="container_header" colspan="3">
					<div style="width:165px;margin:auto;">
						<div style="position:relative;float:left;z-index:99;">
							<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu1">
								<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
								    <p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=1" class="small_link">{L_ADD_LINKS_MENUS}</a>
									</p>
								    <p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=1" class="small_link">{L_ADD_CONTENT_MENUS}</a>
									</p>
								    <p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=1" class="small_link">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
							<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'header')" type="checkbox" name="header_enabled" {CHECKED_HEADER_COLUMN} /> {L_HEADER}</label></p>
							<p class="menus_block_add" onclick="menu_display_block('addmenu1');" onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div id="mod_header">
						# START mod_header #
							{mod_header.MENU}
						# END mod_header #
						
						# IF C_HEADER_COLUMN #
							<script type="text/javascript">
							<!--
							$('mod_header').style.opacity = 0.5;
							$('mod_header').style.filter='alpha(opacity=50)';
							-->
							</script>
						# ENDIF #
						<div id="menu_spacer1" class="menu_spacer"></div>
					</div>
				</td>
			</tr>
			<tr>
				<td id="container_subheader" colspan="3">
					<div style="width:165px;margin:auto;">
						<div style="position:relative;float:left;z-index:99;">
							<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu2">
								<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=2" class="small_link">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=2" class="small_link">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=2" class="small_link">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
							<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'subheader')" type="checkbox" name="sub_header_enabled" {CHECKED_SUB_HEADER_COLUMN} /> {L_SUB_HEADER}</label></p>
							<p class="menus_block_add" onclick="menu_display_block('addmenu2');" onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div id="mod_subheader">
						# START mod_subheader #
							{mod_subheader.MENU}
						# END mod_subheader #
						
						# IF C_SUB_HEADER_COLUMN #
							<script type="text/javascript">
							<!--
							$('mod_subheader').style.opacity = 0.5;
							$('mod_subheader').style.filter='alpha(opacity=50)';
							-->
							</script>
						# ENDIF #
						<div id="menu_spacer2" class="menu_spacer"></div>
					</div>
				</td>
			</tr>
			<tr>				
				<td id="container_leftmenu">
					<div style="width:165px;margin:auto;">
						<div style="position:relative;float:left;z-index:99;">
							<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu3">
								<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=7" class="small_link">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=7" class="small_link">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=7" class="small_link">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
							<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'left')" type="checkbox" name="left_column_enabled" {CHECKED_LEFT_COLUMN} /> {L_LEFT_MENU}</label></p>
							<p class="menus_block_add" onclick="menu_display_block('addmenu3');" onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div id="mod_left">
						<hr style="margin:10px 0px" />
						# START mod_left #
							{mod_left.MENU}
						# END mod_left #
						
						# IF C_LEFT_COLUMN #
							<script type="text/javascript">
							<!--
							$('mod_left').style.opacity = 0.5;
							$('mod_left').style.filter='alpha(opacity=50)';
							-->
							</script>
						# ENDIF #
						<div id="menu_spacer3" class="menu_spacer"></div>
					</div>
				</td>
				<td style="vertical-align:top;">
					<table style="width:100%;">
						<tr>
							<td id="container_topcentral">
								<div style="width:165px;margin:auto;">
									<div style="position:relative;float:left;z-index:99;">
										<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu4">
											<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
												<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
													<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=3" class="small_link">{L_ADD_LINKS_MENUS}</a>
												</p>
												<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
													<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=3" class="small_link">{L_ADD_CONTENT_MENUS}</a>
												</p>
												<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
													<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=3" class="small_link">{L_ADD_FEED_MENUS}</a>
												</p>
											</div>
										</div>
										<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'topcentral')" type="checkbox" name="top_central_enabled" {CHECKED_TOP_CENTRAL_COLUMN} /> {L_TOP_CENTRAL_MENU}</label></p>
										<p class="menus_block_add" onclick="menu_display_block('addmenu4');" onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
											<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
										</p>
									</div>
								</div>
								<div id="mod_topcentral">
									# START mod_topcentral #
										{mod_topcentral.MENU}
									# END mod_topcentral #
										
									# IF C_TOP_CENTRAL_COLUMN #
										<script type="text/javascript">
										<!--
										$('mod_topcentral').style.opacity = 0.5;
										$('mod_topcentral').style.filter='alpha(opacity=50)';
										-->
										</script>
									# ENDIF #
									<div id="menu_spacer4" class="menu_spacer"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td id="container_central">
								<div style="width:165px;margin:auto;">
									<div style="position:relative;float:left;margin-top:5px;z-index:99;">
										<p class="menu_block_libelle">{L_MENUS_AVAILABLE}</p>
										<p class="menus_block_add" style="height:3px;"></p>
									</div>
								</div>
								
								<div id="mod_central">
									# START mod_main #
										{mod_main.MENU}
									# END mod_main #
									<div class="spacer"></div>
									<div id="menu_spacer5" class="menu_spacer"></div>
									<div class="spacer">&nbsp;</div>
								</div>
							</td>
						</tr>
						<tr>
							<td id="container_bottomcentral">
								<div style="width:165px;margin:auto;">
									<div style="position:relative;float:left;z-index:99;">
										<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu5">
											<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
												<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
													<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=4" class="small_link">{L_ADD_LINKS_MENUS}</a>
												</p>
												<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
													<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=4" class="small_link">{L_ADD_CONTENT_MENUS}</a>
												</p>
												<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
													<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=4" class="small_link">{L_ADD_FEED_MENUS}</a>
												</p>
											</div>
										</div>
										<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'bottomcentral')" type="checkbox" name="bottom_central_enabled" {CHECKED_BOTTOM_CENTRAL_COLUMN} /> {L_BOTTOM_CENTRAL_MENU}</label></p>
										<p class="menus_block_add" onclick="menu_display_block('addmenu5');" onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
											<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
										</p>
									</div>
								</div>
								<div id="mod_bottomcentral">
									# START mod_bottomcentral #
										{mod_bottomcentral.MENU}
									# END mod_bottomcentral #
									
									# IF C_BOTTOM_CENTRAL_COLUMN #
										<script type="text/javascript">
										<!--
										$('mod_bottomcentral').style.opacity = 0.5;
										$('mod_bottomcentral').style.filter='alpha(opacity=50)';
										-->
										</script>
									# ENDIF #
									<div id="menu_spacer6" class="menu_spacer"></div>
								</div>
							</td>
						<tr>
					</table>
				</td>
				<td id="container_rightmenu">
					<div style="width:165px;margin:auto;">
						<div style="position:relative;float:left;z-index:99;">
							<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu6">
								<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=8" class="small_link">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=8" class="small_link">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=8" class="small_link">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
							<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'right')" type="checkbox" name="right_column_enabled" {CHECKED_RIGHT_COLUMN} /> {L_RIGHT_MENU}</label></p>
							<p class="menus_block_add" onclick="menu_display_block('addmenu6');" onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div id="mod_right">
						<hr style="margin:10px 0px" />
						# START mod_right #
							{mod_right.MENU}
						# END mod_right #
						
						# IF C_RIGHT_COLUMN #
							<script type="text/javascript">
							<!--
							$('mod_right').style.opacity = 0.5;
							$('mod_right').style.filter='alpha(opacity=50)';
							-->
							</script>
						# ENDIF #
						<div id="menu_spacer7" class="menu_spacer"></div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" id="container_topfooter">
					<div style="width:165px;margin:auto;">
						<div style="position:relative;float:left;z-index:99;">
							<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu7">
								<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=5" class="small_link">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=5" class="small_link">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=5" class="small_link">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
							<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'topfooter')" type="checkbox" name="top_footer_enabled" {CHECKED_TOP_FOOTER_COLUMN} /> {L_TOP_FOOTER}</label></p>
							<p class="menus_block_add" onclick="menu_display_block('addmenu7');" onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div id="mod_topfooter">
						# START mod_topfooter #
							{mod_topfooter.MENU}
						# END mod_topfooter #
						
						# IF C_TOP_FOOTER_COLUMN #
							<script type="text/javascript">
							<!--
							$('mod_topfooter').style.opacity = 0.5;
							$('mod_topfooter').style.filter='alpha(opacity=50)';
							-->
							</script>
						# ENDIF #
						<div id="menu_spacer8" class="menu_spacer"></div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" id="container_footer">
					<div style="width:165px;margin:auto;">
						<div style="position:relative;float:left;z-index:99;">
							<div style="position:absolute;margin-top:125px;margin-left:0px;float:left;display:none;" id="moveaddmenu8">
								<div style="position:absolute;bottom:13px;z-index:100;" onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=6" class="small_link">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=6" class="small_link">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus_block_add menus_block_add_links" style="margin:0px;margin-top:-1px;">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=6" class="small_link">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
							<p class="menu_block_libelle"><label><input onclick="minimize_container(this, 'footer')" type="checkbox" name="footer_enabled" {CHECKED_FOOTER_COLUMN} /> {L_FOOTER}</label></p>
							<p class="menus_block_add" onclick="menu_display_block('addmenu8');" onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div id="mod_footer">
						# START mod_footer #
							{mod_footer.MENU}
						# END mod_footer #
						# IF C_FOOTER_COLUMN #
							<script type="text/javascript">
							<!--
							$('mod_footer').style.opacity = 0.5;
							$('mod_footer').style.filter='alpha(opacity=50)';
							-->
							</script>
						# ENDIF #
						<div id="menu_spacer9" class="menu_spacer"></div>
					</div>
				</td>
			</tr>
		</table>
		
		<script type="text/javascript">
		<!--
		createSortableMenu();
		-->
		</script>
		
		<div id="valid_position_menus">
			<input type="submit" name="valid" value="{L_VALID_POSTIONS}" class="submit" />
			<input type="hidden" name="theme" value="{NAME_THEME}" />
			<input type="hidden" name="token" value="{TOKEN}" />
		</div>
		<input type="hidden" name="menu_tree" id="menu_tree" value="" />
	</form>
</div>
