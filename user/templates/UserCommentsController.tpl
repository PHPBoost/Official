<section id="module-user-comments">
	<header class="section-header">
		<h1>{@comments.list}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# INCLUDE MSG #
				# INCLUDE MODULE_CHOICE_FORM #
				# IF C_COMMENTS #
					<form method="post" class="fieldset-content">
						# IF C_PAGINATION #
							<div class="align-center">
								# INCLUDE PAGINATION #
							</div>
						# ENDIF #
						# INCLUDE COMMENTS #
						# IF C_DISPLAY_DELETE_BUTTON #
							<label for="delete-all-checkbox" class="checkbox" aria-label="${LangLoader::get_message('select.all.elements', 'common')}">
								<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {COMMENTS_NUMBER});">
								<span>&nbsp;</span>
							</label>
							<input type="hidden" name="token" value="{TOKEN}" />
							<button type="submit" id="delete-all-button" name="delete-selected-comments" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">${LangLoader::get_message('delete', 'common')}</button>
						# ENDIF #
						# IF C_PAGINATION #
							<div class="align-center">
								# INCLUDE PAGINATION #
							</div>
						# ENDIF #
					</form>
				# ELSE #
					<div class="align-center">
						${LangLoader::get_message('no_item_now', 'common')}
					</div>
				# ENDIF #
			</div>			
		</div>
	</div>
</section>
