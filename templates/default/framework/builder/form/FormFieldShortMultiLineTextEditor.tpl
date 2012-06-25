<div id="${escape(ID)}" # IF C_HIDDEN # style="display:none;" # ENDIF #>
	<dl id="${escape(ID)}_field">
		<dt>
			<label for="${escape(ID)}">
				# IF C_REQUIRED # * # ENDIF #
				{LABEL}
				# IF C_HAS_CONSTRAINTS #
				&nbsp;
				<span style="display:none" id="onblurContainerResponse${escape(ID)}"></span>
				<div style="font-weight:bold;display:none" id="onblurMesssageResponse${escape(ID)}"></div>
				# ENDIF #
			</label>
			# IF C_DESCRIPTION #
			<br />
			<span class="text_small">{DESCRIPTION}</span>
			# ENDIF #
		</dt>
		<dd>
		# START fieldelements #
			{fieldelements.ELEMENT}
		# END fieldelements #
		</dd>
	</dl>
</div>
# INCLUDE ADD_FIELD_JS #