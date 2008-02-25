<script type="text/javascript">
<!--
    var modules = new Array("ResultsAll" # START forms # , "Form{name}" # END forms #);
    
    function ShowForm(module)
    /*
     * Montre les r�sultats de ce module
     */
    {
        document.getElementById('Form'.module).style.display = 'block';
    }
    
    function HideForms()
    /*
     * Cache tous les r�sultats
     */
    {
        for ( var i = 0; i < modules.length; i++)
        {
            document.getElementById('Form'.modules[i]).style.display = 'none';
        }
    }
    
    function ChangeForm(module)
    /*
     * Change le cadre des r�sultats
     */
    {
        HideForms();
        ShowForm(module);
    }
    
    function check_form_post()
    {
        var textSearched = document.getElementById("search").value;
        
        if ( textSearched.length > 3 )
        {
            textSearched = escape_xmlhttprequest(textSearched);
            return true;
        }
        else
        {
            alert('{WARNING_LENGTH_STRING_SEARCH}');
            return false;
        }
    }
-->
</script>

<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{TITLE}</div>
    <div class="module_contents">
        <div class="spacer">&nbsp;</div>
        <form action="../search/search.php#results" onsubmit="return check_form_post();" method="post">
            <fieldset>
                <legend>{TITLE_SEARCH}</legend>
                <dl>
                    <dt><label for="search">{SEARCH_MIN_LENGTH}</label></dt>
                    <dd><label><input type="text" size="35" id="search" name="search" value="{TEXT_SEARCHED}"  class="text" /></label></dd>
                </dl>
            </fieldset>
            <div class="choices">
                <fieldset>
                    <legend>{FORMS}</legend>
                    <dl>
                        # START forms #
                            <dt>
                                <div class="choice">
                                    <span onClick="ChangeForm('{forms.MODULE_NAME}');">{forms.MODULE_NAME}</span>
                                </div>
                            </dt>
                        # END forms #
                    </dl>
                </fieldset>
            </div>
            # START forms #
                <div class="module_position">
                    <fieldset>
                        <legend>{forms.MODULE_NAME}</legend>
                        {forms.SEARCH_FORM}
                    </fieldset>
                </div>
            # END forms #
            <fieldset class="fieldset_submit">
                <legend>{title_search}</legend>
                <input type="submit" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
            </fieldset>
        </form>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>