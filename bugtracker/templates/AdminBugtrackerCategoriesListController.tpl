<script>
<!--
var BugtrackerFormFieldCategories = Class.create({
	integer : {NEXT_ID},
	max_input : {MAX_INPUT},
	add_category : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;
			
			var tr = Builder.node('tr', {'id' : 'tr_' + id}, []);
			
			var td = Builder.node('td', {'id' : 'td1_' + id}, [
				Builder.node('input', {type : 'radio', name : 'default_category', value : id}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td2_' + id}, [
				Builder.node('input', {type : 'text', id : 'category_' + id, name : 'category_' + id, size : 40, maxlength : 100, placeholder : ${escapejs(LangLoader::get_message('name', 'main'))}}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td3_' + id}, [
				Builder.node('a', {id : 'delete_' + id, href : 'javascript:BugtrackerFormFieldCategories.delete_category(' + id + ');', title : ${escapejs(LangLoader::get_message('delete', 'common'))}, className: 'fa fa-delete'}),
				' ',
			]);
			tr.insert(td);
			
			$('categories_list').insert(tr);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add_category').hide();
		}
		if (this.integer) {
			jQuery('#no_category').hide();
		}
	},
	delete_type : function (id) {
		$('tr_' + id).remove();
		this.integer--;
		if (this.integer == 1)
			$('no_category').style.display = "";
		
		jQuery('#add_category').show();
	},
});

var BugtrackerFormFieldCategories = new BugtrackerFormFieldCategories();
-->
</script>

<table>
	<thead>
		<tr>
			<th class="small-column">
				{@labels.default}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
			<th class="small-column">
				${LangLoader::get_message('delete', 'common')}
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>
				<a href="javascript:BugtrackerFormFieldCategories.add_category();" class="fa fa-plus" title="{@titles.add_category}" id="add_category"></a>
			</th>
			<th colspan="2" class="right">
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="{@actions.confirm.del_default_value}"><i class="fa fa-delete"></i> {@labels.del_default_value}</a># ENDIF #
			</th>
		</tr>
	</tfoot>
	<tbody id="categories_list">
		<tr id="no_category"# IF C_CATEGORIES # style="display:none;"# ENDIF #>
			<td colspan="3">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# START categories #
		<tr>
			<td>
				<input type="radio" name="default_category" value="{categories.ID}"# IF categories.C_IS_DEFAULT # checked="checked"# ENDIF # />
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="category{categories.ID}" value="{categories.NAME}" />
			</td>
			<td>
				<a href="{categories.LINK_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
			</td>
		</tr>
		# END categories #
	</tbody>
</table>
