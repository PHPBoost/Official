#{resources('install/install')}
<fieldset class="submit_case">
    # IF HAS_PREVIOUS_STEP #
    <a href="{PREVIOUS_STEP_URL}" title="${i18n('step.previous')}" >
        <img src="templates/images/left.png" alt="${i18n('step.previous')}" class="valign_middle" />
    </a>
    # END #
    <input src="templates/images/right.png" title="${i18n('step.next')}" class="img_submit" type="image">
    <input name="submit" value="next" type="hidden">
</fieldset> 