# IF C_IS_ENABLED #
<script type="text/javascript">
<!--
function refresh_img_{CAPTCHA_INSTANCE}()
{
	if (typeof this.img_id == 'undefined')
		this.img_id = 0;
	else
		this.img_id++;
	document.getElementById('verif_code_img{CAPTCHA_INSTANCE}').src = '{PATH_TO_ROOT}/kernel/framework/ajax/captcha.php?instance={CAPTCHA_INSTANCE}&width={CAPTCHA_WIDTH}&difficulty={CAPTCHA_DIFFICULTY}&height={CAPTCHA_HEIGHT}&font={CAPTCHA_FONT}&new=' + img_id;	
}
-->
</script>
<dl id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF #>
	<dt>
		<label for="${escape(ID)}">* {LABEL}</label>
		# IF DESCRIPTION # <br /><span class="text_small">{DESCRIPTION}</span> # ENDIF #
	</dt>
	<dd>
		<img src="{PATH_TO_ROOT}/kernel/framework/ajax/captcha.php?instance={CAPTCHA_INSTANCE}&amp;width={CAPTCHA_WIDTH}&amp;height={CAPTCHA_HEIGHT}&amp;difficulty={CAPTCHA_DIFFICULTY}&amp;font={CAPTCHA_FONT}" id="verif_code_img{CAPTCHA_INSTANCE}" alt="" style="padding:2px;" />
		<br />
		<input size="30" type="text" class="text" name="${escape(ID)}" id="${escape(ID)}" onblur="${escape(ONBLUR)}" # IF C_DISABLED # disabled="disabled" # ENDIF #/>
		<a href="javascript:refresh_img_{CAPTCHA_INSTANCE}()"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/refresh.png" alt="" class="valign_middle" /></a>
		<span id="onblurContainerResponse{ID}"></span>
		<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
	</dd>
</dl>
# INCLUDE ADD_FIELD_JS #

# ENDIF #