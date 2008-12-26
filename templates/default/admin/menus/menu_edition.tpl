# IF C_FIRST_MENU #
<script type="text/javascript">
<!--
function show_sub_menu_properties(id)
{
	if (document.getElementById("menu_element_" + id + "_properties").style.display == "none")
	{
		Effect.Appear("menu_element_" + id + "_properties");
		document.getElementById("menu_element_" + id + "_more_image").src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/minus.png";
	}
	else
	{
		Effect.Fade("menu_element_" + id + "_properties");
		document.getElementById("menu_element_" + id + "_more_image").src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png";
	}
}
-->
</script>
<ul id="menu" class="menu_link_list">
	# START elements #
		{elements.DISPLAY}
	# END elements #
</ul>
# ENDIF #

# IF C_NEXT_MENU #
	<li class="row1 menu_link_element">
		<div style="float:left;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder.png" alt="plus" class="valign_middle" />
			<label for="menu_element_{ID}_name">Nom :</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name" />
			<label for="menu_element_{ID}_url">URL :</label> <input type="text" value="{URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url" />
			<label for="menu_element_{ID}_image">Image :</label> <input type="text" value="{URL}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" />
		</div>
		<div style="float:right;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="More..." id="menu_element_{ID}_more_image" class="valign_middle" onclick="show_sub_menu_properties({ID});" />
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>Propriétés</legend>
			<dl>
				<dt><label>Autorisations</label></dt>
				<dd>auth</dd>
			</dl>
		</fieldset>
		<ul class="menu_link_list">
		# START elements #
			{elements.DISPLAY}
		# END elements #
		</ul>
	</li>
# ENDIF #

# IF C_LINK #
    <li class="row2 menu_link_element">
   		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" alt="plus" class="valign_middle" />
   		{TITLE}
    </li>
# ENDIF #