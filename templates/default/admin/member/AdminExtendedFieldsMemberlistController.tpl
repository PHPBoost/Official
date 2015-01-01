<script>
<!--
var ExtendedFields = Class.create({
	id : '',
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
			tag:'li',
			only:'sortable-element'
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

var ExtendedField = Class.create({
	id : '',
	more_is_opened : false,
	ExtendedFields: null,
	is_not_displayed : false,
	initialize : function(id, display, extended_fields) {
		this.id = id;
		this.ExtendedFields = extended_fields;
		if (display == 1) {
			this.is_not_displayed = false;
		}
		else {
			this.is_not_displayed = true;
		}
		this.change_display_picture();
		
		# IF C_MORE_THAN_ONE_FIELD #
		this.ExtendedFields.change_reposition_pictures();
		# ENDIF #
	},
	delete_fields : function() {
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			new Ajax.Request('${relative_url(AdminExtendedFieldsUrlBuilder::delete())}', {
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
			ExtendedFields.destroy_sortable();
			ExtendedFields.create_sortable();
		}
	},
	move_up : function() {
		var sequence = ExtendedFields.get_sortable_sequence();
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
			ExtendedFields.set_sortable_sequence(sequence);
			ExtendedFields.change_reposition_pictures();
		}
	},
	move_down : function() {
		var sequence = ExtendedFields.get_sortable_sequence();
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
			ExtendedFields.set_sortable_sequence(sequence);
			ExtendedFields.change_reposition_pictures();
		}
	},
	change_display : function() {
		display = this.is_not_displayed;
		
		new Ajax.Request('{REWRITED_SCRIPT}', {
			method:'post',
			parameters: {'id' : this.id, 'token' : '{TOKEN}', 'display': !display},
		});
		
		this.change_display_picture();
	},
	change_display_picture : function() {
		if (this.is_not_displayed == false) {
			$('change_display_' + this.id).className = "fa fa-eye";
			$('change_display_' + this.id).title = "{@field.display}";
			$('change_display_' + this.id).alt = "{@field.display}";
			this.is_not_displayed = true;
		}
		else {
			$('change_display_' + this.id).className = "fa fa-eye-slash";
			$('change_display_' + this.id).title = "{@field.not_display}";
			$('change_display_' + this.id).alt = "{@field.not_display}";
			this.is_not_displayed = false;
		}
	},
});

var ExtendedFields = new ExtendedFields('lists');
jQuery(document).ready(function() {
	ExtendedFields.destroy_sortable();
	ExtendedFields.create_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ExtendedFields.serialize_sortable();">
	<fieldset id="management_extended_fields">
	<legend>{@fields.management}</legend>
		<ul id="lists" class="sortable-block">
			# START list_extended_fields #
				<li class="sortable-element" id="list_{list_extended_fields.ID}">
					<div class="sortable-title">
						<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
						<i class="fa fa-globe"></i>
						<span class="text-strong">{list_extended_fields.NAME}</span>
						<div class="sortable-actions">
							{@field.required} : <span class="text-strong"># IF list_extended_fields.C_REQUIRED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #</span>
							# IF C_MORE_THAN_ONE_FIELD #
							<div class="sortable-options">
								<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move_up_{list_extended_fields.ID}" onclick="return false;" class="fa fa-arrow-up"></a>
							</div>
							<div class="sortable-options">
								<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move_down_{list_extended_fields.ID}" onclick="return false;" class="fa fa-arrow-down"></a>
							</div>
							# ENDIF #
							<div class="sortable-options">
								<a href="{list_extended_fields.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
							</div>
							<div class="sortable-options">
								# IF NOT list_extended_fields.C_FREEZE #
								<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete_{list_extended_fields.ID}" class="fa fa-delete"></a>
								# ELSE #
								&nbsp;
								# ENDIF #
							</div>
							<a href="" onclick="return false;" id="change_display_{list_extended_fields.ID}" class="fa fa-eye"></a>
						</div>
					</div>
					<div class="spacer"></div>
				</li>
				<script>
				<!--
				jQuery(document).ready(function() {
					var extended_field = new ExtendedField({list_extended_fields.ID}, '{list_extended_fields.C_DISPLAY}', ExtendedFields);
					
					$('list_{list_extended_fields.ID}').observe('mouseup',function(){
						ExtendedFields.change_reposition_pictures();
					});
					
					# IF NOT list_extended_fields.C_FREEZE #
					$('delete_{list_extended_fields.ID}').observe('click',function(){
						extended_field.delete_fields();
					});
					# ENDIF #
					
					$('change_display_{list_extended_fields.ID}').observe('click',function(){
						extended_field.change_display();
					});
					
					# IF C_MORE_THAN_ONE_FIELD #
					$('move_up_{list_extended_fields.ID}').observe('click',function(){
						extended_field.move_up();
					});
					
					$('move_down_{list_extended_fields.ID}').observe('click',function(){
						extended_field.move_down();
					});
					# ENDIF #
				});
				-->
				</script>
			# END list_extended_fields #
		</ul>
		<div id="no_field" class="center"# IF C_FIELDS # style="display:none;"# ENDIF #>${LangLoader::get_message('no_item_now', 'common')}</div>
	</fieldset>
	# IF C_MORE_THAN_ONE_FIELD #
	<fieldset class="fieldset-submit">
		<button type="submit" class="submit" name="submit" value="true">${LangLoader::get_message('position.update', 'common')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="position" id="position" value="">
	</fieldset>
	# ENDIF #
</form>