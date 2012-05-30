<script type="text/javascript">
 var MultipleFilePicker = Class.create({
	integer : 2,
	id_input : ${escapejs(ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_file_input : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			var input = new Element('input', {'type' : 'file', 'id' : id, 'name' : id});
			$('input_files_list_' + this.id_input).insert(input);
			$(id).form.enctype = "multipart/form-data";
			var br = new Element('br');
			$('input_files_list_' + this.id_input).insert(br);
			this.incremente_integer();
		}
		if (this.integer == this.max_input) {
			$('add_' + this.id_input).hide();
		}
	},
	incremente_integer : function () {
		this.integer = this.integer + 1;
	},
});
var MultipleFilePicker = new MultipleFilePicker();

</script>
<div id="input_files_list_${escape(ID)}">
	<input type="file" name="${escape(ID)}_1" id="${escape(ID)}_1" # IF C_DISABLED # disabled="disabled" # ENDIF # /></br>
	<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden" />
</div>
<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/plus.png" id="add_${escape(ID)}" class="valign_middle" style="width:25px;"/>
<script type="text/javascript">
<!--
$("${escape(ID)}_1").form.enctype = "multipart/form-data";
Event.observe(window, 'load', function() {		
	$('add_${escape(ID)}').observe('click',function(){
		MultipleFilePicker.add_file_input();
	});
});
-->
</script>