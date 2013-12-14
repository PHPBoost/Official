<table>
	<thead>
		<tr> 
			<th class="column_title">
				<a href="{U_SORT_NAME_ASC}" class="icon-table-sort-up"></a>
				{@news.form.name}
				<a href="{U_SORT_NAME_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="icon-table-sort-up"></a>
				${LangLoader::get_message('category', 'categories-common')}
				<a href="{U_SORT_CATEGORY_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="icon-table-sort-up"></a>
				${LangLoader::get_message('pseudo', 'main')}
				<a href="{U_SORT_AUTHOR_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_DATE_ASC}" class="icon-table-sort-up"></a>
				${LangLoader::get_message('form.date.creation', 'common')}
				<a href="{U_SORT_DATE_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				${LangLoader::get_message('form.approbation', 'common')}
			</th>
			<th>
			</th>
		</tr>
	</thead>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<th colspan="6">
				# INCLUDE PAGINATION #
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START news #
			<tr>
				<td>
					<a href="{news.U_LINK}">{news.NAME}</a>
				</td>
				<td>
					<a href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
				</td>
				<td>
					<a href="{news.U_AUTHOR_PROFILE}" class="small_link {news.USER_LEVEL_CLASS}" # IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}"# ENDIF #>{news.PSEUDO}</a>
				</td>
				<td>
					{news.DATE}
				</td>
				<td>
					{news.STATUS}
				</td>
				<td class="text_left">
					<a href="{news.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
					<a href="{news.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
				</td>
			</tr>
		# END news #
		# IF NOT C_NEWS #
		<tr> 
			<td colspan="6">
				{@news.message.no_items}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>