<script type="text/javascript">
<!--
var ContactFields = Class.create({
	id : '',
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
			tag:'li',
			only:'field'
		});
	},
	destroy_sortable : function() {
		Sortable.destroy(this.id); 
	},
	serialize_sortable : function() {
		$('position').value = Sortable.serialize(this.id);
	},
	get_sortable_sequence : function() {
		return Sortable.sequence(this.id);
	},
	set_sortable_sequence : function(sequence) {
		Sortable.setSequence(this.id, sequence);
	},
	change_reposition_pictures : function() {
		sequence = Sortable.sequence(this.id);
		
		$('move_up_' + sequence[0]).style.display = "none";
		$('move_down_' + sequence[0]).style.display = "inline";
		
		for (var j = 1 ; j < sequence.length - 1 ; j++) {
			$('move_up_' + sequence[j]).style.display = "inline";
			$('move_down_' + sequence[j]).style.display = "inline";
		}
		
		$('move_up_' + sequence[sequence.length - 1]).style.display = "inline";
		$('move_down_' + sequence[sequence.length - 1]).style.display = "none";
	}
});

var ContactField = Class.create({
	id : '',
	more_is_opened : false,
	ContactFields: null,
	is_not_displayed : false,
	initialize : function(id, display, contact_fields) {
		this.id = id;
		this.ContactFields = contact_fields;
		if (display == 1) {
			this.is_not_displayed = false;
		}
		else {
			this.is_not_displayed = true;
		}
		this.change_display_picture();
		
		# IF C_MORE_THAN_ONE_FIELD #
		this.ContactFields.change_reposition_pictures();
		# ENDIF #
	},
	delete_fields : function() {
		if (confirm(${escapejs(@admin.fields.delete_field.confirm)}))
		{
			new Ajax.Request('${relative_url(ContactUrlBuilder::delete_field())}', {
				method:'post',
				asynchronous: false,
				parameters: {'id' : this.id, 'token' : '{TOKEN}'},
				onSuccess: function(transport) {
					if (transport.responseText == 0)
					{
						$('no_field').style.display = "";
					}
				}
			});
			
			var elementToDelete = $('list_' + this.id);
			elementToDelete.parentNode.removeChild(elementToDelete);
			ContactFields.destroy_sortable();
			ContactFields.create_sortable();
		}
	},
	move_up : function() {
		var sequence = ContactFields.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 1 ; j < sequence.length ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j-1];
					sequence[j-1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			ContactFields.set_sortable_sequence(sequence);
			ContactFields.change_reposition_pictures();
		}
	},
	move_down : function() {
		var sequence = ContactFields.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 0 ; j < sequence.length - 1 ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j+1];
					sequence[j+1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			ContactFields.set_sortable_sequence(sequence);
			ContactFields.change_reposition_pictures();
		}
	},
	change_display : function() {
		$('loading_' + this.id).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />');
		display = this.is_not_displayed;
		
		new Ajax.Request('{REWRITED_SCRIPT}', {
			method:'post',
			parameters: {'id' : this.id, 'token' : '{TOKEN}', 'display': !display},
		});
		
		this.change_display_picture();
		Element.update.delay(1, 'loading_' + this.id, ''); 
	},
	change_display_picture : function() {
		if (this.is_not_displayed == false) {
			$('change_display_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png";
			$('change_display_' + this.id).title = "{@admin.field.display}";
			$('change_display_' + this.id).alt = "{@admin.field.display}";
			this.is_not_displayed = true;
		}
		else {
			$('change_display_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png";
			$('change_display_' + this.id).title = "{@admin.field.not_display}";
			$('change_display_' + this.id).alt = "{@admin.field.not_display}";
			this.is_not_displayed = false;
		}
	},
});

var ContactFields = new ContactFields('fields_list');
Event.observe(window, 'load', function() {
	ContactFields.destroy_sortable();
	ContactFields.create_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ContactFields.serialize_sortable();">
	<fieldset id="contact_fields_management">
	<legend>{@admin.fields.manage}</legend>
		<ul id="fields_list" class="fields_list">
			# START fields_list #
				<li class="field" id="list_{fields_list.ID}">
					<div class="field_infos"> 
						<i title="${LangLoader::get_message('move', 'admin')}" class="icon-arrows" ></i>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/url.png" alt="url" class="url_picture" />
						<span class="text_strong">{fields_list.NAME}</span>
						<div class="field_option_icon"># IF NOT fields_list.C_READONLY #<img id="loading_{fields_list.ID}" alt="" class="valign_middle" /><a><img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" id="change_display_{fields_list.ID}" class="valign_middle" width="16px" /></a># ELSE #<img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" id="change_display_{fields_list.ID}" class="valign_middle not_displayable" width="16px" /># ENDIF #</div>
						<div class="field_options">
							<div class="field_option_icon"># IF fields_list.C_DELETE #<a class="icon-delete" data-confirmation="delete-element" title="{@admin.fields.action.delete_field}" id="delete_{fields_list.ID}"></a># ELSE #&nbsp;# ENDIF #</div>
							<div class="field_option_icon"><a href="{fields_list.U_EDIT}" title="{@admin.fields.action.edit_field}" class="icon-edit"></a></div>
							# IF C_MORE_THAN_ONE_FIELD #
							<div class="field_option_icon"><a title="{@admin.fields.move_field_down}" id="move_down_{fields_list.ID}"><i class="icon-arrow-down"></i></a>&nbsp;</div>
							<div class="field_option_icon"><a title="{@admin.fields.move_field_up}" id="move_up_{fields_list.ID}"><i class="icon-arrow-up"></i></a></div>
							# ENDIF #
						</div>
						<div class="float_right">
							{@admin.field.required} : # IF fields_list.C_REQUIRED #<span class="field_required">{@admin.field.yes}</span># ELSE #<span class="text_strong">{@admin.field.no}</span># ENDIF #
						</div>
					</div>
					<div class="spacer"></div>
				</li>
				<script type="text/javascript">
				<!--
				Event.observe(window, 'load', function() {
					var contact_field = new ContactField({fields_list.ID}, '{fields_list.C_DISPLAY}', ContactFields);
					
					$('list_{fields_list.ID}').observe('mouseout',function(){
						ContactFields.change_reposition_pictures();
					});
					
					# IF fields_list.C_DELETE #
					$('delete_{fields_list.ID}').observe('click',function(){
						contact_field.delete_fields();
					});
					# ENDIF #
					
					# IF NOT fields_list.C_READONLY #
					$('change_display_{fields_list.ID}').observe('click',function(){
						contact_field.change_display();
					});
					# ENDIF #
					
					# IF C_MORE_THAN_ONE_FIELD #
					$('move_up_{fields_list.ID}').observe('click',function(){
						contact_field.move_up();
					});
					
					$('move_down_{fields_list.ID}').observe('click',function(){
						contact_field.move_down();
					});
					# ENDIF #
				});
				-->
				</script>
			# END fields_list #
		</ul>
		<div id="no_field" class="no_field"# IF C_FIELDS # style="display:none;"# ENDIF #>{@admin.fields.no_field}</div>
	</fieldset>
	<fieldset class="fieldset_submit">
		# IF C_MORE_THAN_ONE_FIELD #
		<button type="submit" name="submit" value="true">{@admin.fields.update_fields_position}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="position" id="position" value="">
		# ENDIF #
		<button type="submit" name="add_field" value="true">{@admin.fields.action.add_field}</button>
	</fieldset>
</form>
