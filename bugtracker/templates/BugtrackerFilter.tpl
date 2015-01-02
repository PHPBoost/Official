<script>
<!--
function delete_filter(id) {
	if (confirm(${escapejs(@actions.confirm.del_filter)})) {

		jQuery.ajax({
			url: '${relative_url(BugtrackerUrlBuilder::delete_filter())}',
			type: "post",
			async: false,
			data: {'id' : id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData > 0) {
					var elementToDelete = jQuery('#filter' + id)[0];
					elementToDelete.parentNode.removeChild(elementToDelete);
				}
			},
			error: function(e){
				alert(e);
			}
		});
	}
}
-->
</script>
<menu class="dynamic-menu">
	<ul>
		<li>
			<a href="" onclick="jQuery("#" + table_filters).fadeToggle(); return false;"><i class="fa fa-filter"></i> {L_FILTERS}</a> 
		</li>
	</ul>
</menu>
<table id="table_filters"# IF NOT C_HAS_SELECTED_FILTERS # style="display:none;"# ENDIF #>
	<thead>
		<tr>
			# IF C_DISPLAY_TYPES #
			<th>
				{@labels.fields.type}
			</th>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
			<th>
				{@labels.fields.category}
			</th>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
			<th>
				{@labels.fields.severity}
			</th>
			# ENDIF #
			<th>
				{@labels.fields.status}
			</th>
			# IF C_DISPLAY_VERSIONS #
			<th>
				{@labels.fields.version}
			</th>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
			<th>
			</th>
			# ENDIF #
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="{FILTERS_NUMBER}">{@labels.number} # IF C_FILTER #{@labels.matching_selected_filter} # ELSE ## IF C_FILTERS #{@labels.matching_selected_filters} # ENDIF ## ENDIF #: {BUGS_NUMBER}</th>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			# IF C_DISPLAY_TYPES #
			<td class="no-separator">
				# INCLUDE SELECT_TYPE #
			</td>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
			<td class="no-separator">
				# INCLUDE SELECT_CATEGORY #
			</td>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
			<td class="no-separator">
				# INCLUDE SELECT_SEVERITY #
			</td>
			# ENDIF #
			<td class="no-separator">
				# INCLUDE SELECT_STATUS #
			</td>
			# IF C_DISPLAY_VERSIONS #
			<td class="no-separator">
				# INCLUDE SELECT_VERSION #
			</td>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
			<td class="no-separator">
				<a href="{LINK_FILTER_SAVE}" title="{@labels.save_filters}" class="fa fa-save"></a>
			</td>
			# ENDIF #
		</tr>
		# IF C_SAVED_FILTERS #
		# START filters #
		<tr id="filter{filters.ID}">
			<td colspan="{FILTERS_NUMBER}">
				<a href="" title="${LangLoader::get_message('delete', 'common')}" id="delete_{filters.ID}" onclick="return false;" class="fa fa-delete"></a> <a href="{filters.LINK_FILTER}">{filters.FILTER}</a>
			</td>
		</tr>
		<script>
		<!--
		jQuery(document).ready(function() {
			$('delete_{filters.ID}').observe('click',function(){
				delete_filter('{filters.ID}');
			});
		});
		-->
		</script>
		# END filters #
		# ENDIF #
	</tbody>
</table>

<div class="spacer">&nbsp;</div>