<script>
<!--
var displayed = new Array();
displayed[${escapejs(FIELD)}] = false;
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	var contents = jQuery('#' + field).val();
	var preview_field = 'xmlhttprequest-preview' + field;

	if( contents != "" )
	{
		if(!displayed[field])
			jQuery("#" + preview_field).slideDown(500);

		jQuery('#loading_preview' + field).show();

		displayed[field] = true;

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/content_xmlhttprequest.php",
			type: "post",
			data: {
				token: '{TOKEN}',
				path_to_root: '{PHP_PATH_TO_ROOT}',
				editor: 'BBCode',
				page_path: '{PAGE_PATH}',
				contents: contents,
				ftags: '{FORBIDDEN_TAGS}'
			},
			success: function(returnData){
				jQuery('#' + preview_field).html(returnData);

				jQuery('#loading_preview' + field).hide();
			},
			error: function(e){
				alert(e);
			}
		});
	}
	else
		alert("{L_REQUIRE_TEXT}");
}
		
function insertTinyMceContent(textAreaId, content)
{
	tinyMCE.get(textAreaId).execCommand('mceInsertContent', false, content, {skip_undo : 1});
}

-->
</script>
<div style="position:relative;display:none;" id="loading_preview{FIELD}">
	<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;">
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>
<div style="display:none;" class="xmlhttprequest-preview" id="xmlhttprequest-preview{FIELD}"></div>

# IF NOT C_NOT_JS_INCLUDED #
	<script src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/tiny_mce.js"></script>
# ENDIF #
	
<script>
<!--
tinyMCE.init({
	mode : "exact",
	elements : "{FIELD}", 
	theme : "advanced",
	language : "fr",
	theme_advanced_buttons1 : "{THEME_ADVANCED_BUTTONS1}", 
	theme_advanced_buttons2 : "{THEME_ADVANCED_BUTTONS2}", 
	theme_advanced_buttons3 : "{THEME_ADVANCED_BUTTONS3}",
	theme_advanced_toolbar_location : "top", 
	theme_advanced_toolbar_align : "center", 
	theme_advanced_statusbar_location : "bottom",
	plugins : "table,searchreplace,inlinepopups,fullscreen,emotions",
	extended_valid_elements : "font[face|size|color|style],span[class|align|style],a[href|name]",
	theme_advanced_resize_horizontal : false, 
	theme_advanced_resizing : true
});
-->
</script>

# IF C_UPLOAD_MANAGEMENT #
<div style="width: 94%; margin: 5px auto; text-align: center;">
	{L_BB_UPLOAD} : <a style="font-size: 10px;" title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={IDENTIFIER}&amp;edt=TinyMCE', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-upload fa-2x"></i></a>
</div>
# ENDIF #