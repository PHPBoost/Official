<table class="module_table" style="width:70%;">	
	<tr>
		<td style="vertical-align:top;" class="row2">
			# INCLUDE SELECT_GROUP #
		</td>
	</tr>
</table>

<div class="spacer">&nbsp;</div>

<table class="module_table" style="width: 70%;text-align:center;">
	<tr>
		<th colspan="3">
			{GROUP_NAME}
			# IF C_ADMIN #
				&nbsp;&nbsp;
				<a href="{U_ADMIN_GROUPS}" >
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" />
				</a>
			# ENDIF #
		</th>
	</tr>
	<tr>
		<td class="row3" style="font-weight: bold;width: auto;">
			{@avatar}
		</td>
		<td class="row3" style="font-weight: bold;">
			{@pseudo}
		</td>
		<td class="row3" style="font-weight: bold;">
			{@level}
		</td>
	</tr>
	# START members_list #
	<tr>
		<td class="row1">
			<img class="valign_middle" src="{members_list.U_AVATAR}" alt=""	/>
		</td>
		<td class="row1">
			<a href="{members_list.U_PROFILE}" class="{members_list.LEVEL_CLASS}" # IF members_list.C_GROUP_COLOR # style="color:{members_list.GROUP_COLOR}" # ENDIF #>
				{members_list.PSEUDO}
			</a>
		</td>
		<td class="row1">
			{members_list.LEVEL}
		</td>
	</tr>	
	# END members_list #
	# IF C_NOT_MEMBERS #
	<tr style="text-align:center;">
		<td colspan="4" class="row2">
			<span style="margin-left:auto;margin-right:auto;" class="text_strong" >{@no_member}</span>
		</td>
	</tr>
	# ENDIF #
</table>