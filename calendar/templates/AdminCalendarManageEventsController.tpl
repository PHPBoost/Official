<table>
	<caption>{@calendar.config.events.management}</caption>
	<thead>
		<tr>
			<th></th>
			<th>
				<a href="{U_SORT_TITLE_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('form.title', 'common')}
				<a href="{U_SORT_TITLE_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('category', 'categories-common')}
				<a href="{U_SORT_CATEGORY_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('author', 'common')}
				<a href="{U_SORT_AUTHOR_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_DATE_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('date', 'date-common')}
				<a href="{U_SORT_DATE_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				{@calendar.titles.repetion}
			</th>
			<th>
				<a href="{U_SORT_STATUS_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('status.approved', 'common')}
				<a href="{U_SORT_STATUS_DESC}" class="fa fa-table-sort-down"></a>
			</th>
		</tr>
	</thead>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<th colspan="7">
				# INCLUDE PAGINATION #
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START event #
		<tr> 
			<td> 
				<a href="{event.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
				<a href="{event.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete"# IF NOT event.C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #></a>
			</td>
			<td class="left">
				<a href="{event.U_LINK}">{event.TITLE}</a>
			</td>
			<td> 
				<span# IF event.CATEGORY_COLOR # style="color:{event.CATEGORY_COLOR}"# ENDIF #>{event.CATEGORY_NAME}</span>
			</td>
			<td> 
				# IF event.C_AUTHOR_EXIST #<a href="{event.U_AUTHOR_PROFILE}" class="{event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}"# ENDIF #>{event.AUTHOR}</a># ELSE #{event.AUTHOR}# ENDIF #
			</td>
			<td>
				${LangLoader::get_message('from_date', 'main')} {event.START_DATE}<br />
				${LangLoader::get_message('to_date', 'main')} {event.END_DATE}
			</td>
			<td>
				# IF event.C_BELONGS_TO_A_SERIE #{event.REPEAT_TYPE} - {event.REPEAT_NUMBER} {@calendar.labels.repeat_times}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #
			</td>
			<td>
				# IF event.C_APPROVED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #
			</td>
		</tr>
		# END event #
		# IF NOT C_EVENTS #
		<tr> 
			<td colspan="7">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
