        <script type="text/javascript">
        <!--
            const FORM = 'form_';
            var modulesForms = new Array();
            # START forms #
                modulesForms.push("{forms.MODULE_NAME}");
            # END forms #
            
            function ShowAdvancedSearchForms()
            // Montre les champs de recherche avanc�e
            {
                HideAdvancedSearchForms();
                
                document.getElementById('searched_modules_DL').style.display = 'none';
                document.getElementById('forms_selection_DL').style.display = 'block';
                
                hide_div('advanced_search');
                show_div('simple_search');
            }
            
            function HideAdvancedSearchForms()
            // Cache les champs de recherche avanc�e
            {
                HideForms();
                
                document.getElementById('searched_modules_DL').style.display = 'block';
                document.getElementById('forms_selection_DL').style.display = 'none';
                hide_div('simple_search');
                show_div('advanced_search');
            }
            
            function HideForms()
            // Cache tous les r�sultats
            {
                for ( var i = 0; i < modulesForms.length; i++)
                {
                    hide_div(FORM + modulesForms[i]);
                }
            }
            
            function ChangeForm(module)
            // Change le cadre des r�sultats
            {
                HideForms();
                show_div(FORM + module);
            }
            
            function check_search_form_post()
            // V�rifie la validit� du formulaire
            {
                var textSearched = document.getElementById("TxTsearched").value;
                
                if ( textSearched.length > 3 )
                {
                    textSearched = escape_xmlhttprequest(textSearched);
                    return true;
                }
                else
                {
                    alert('{L_WARNING_LENGTH_STRING_SEARCH}');
                    return false;
                }
            }
        -->
        </script>

        <div class="module_position">
            <div class="module_top_l"></div>
            <div class="module_top_r"></div>
            <div class="module_top">{L_TITLE_SEARCH}</div>
            <div class="module_contents">
                <div class="spacer">&nbsp;</div>
                <form id="search_form" action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
                    <fieldset class="SearchForm">
                        <legend>{L_TITLE_SEARCH}</legend>
                        <dl>
                            <dt><label for="TxTsearched">{L_SEARCH_KEYWORDS}<br /><span>{L_SEARCH_MIN_LENGTH}</span></label></dt>
                            <dd><label><input type="text" size="35" id="TxTsearched" name="search" value="{TEXT_SEARCHED}" class="search_field" /></label></dd>
                        </dl>
                        <dl id="searched_modules_DL" style="display:none">
                            <dt>
                                <label>{L_SEARCH_IN_MODULES}<br /><span>{L_SEARCH_IN_MODULES_EXPLAIN}</span></label>
                            </dt>
                            <dd>
                                <select id="searched_modules[]" name="searched_modules[]" size="5" multiple="multiple" class="list_modules">
                                # START searched_modules #
                                    <option value="{searched_modules.MODULE}" id="{searched_modules.MODULE}"{searched_modules.SELECTED}>{searched_modules.L_MODULE_NAME}</option>
                                # END searched_modules #
                                </select>
                            </dd>
                        </dl>
                        <div id="forms_selection_DL" style="text-align:center; display:none;">
                        <label>{L_SEARCH_SPECIALIZED_FORM}</label>
                        <p id="forms_selection">
                            # START forms #
                                <a href="javascript:ChangeForm('{forms.MODULE_NAME}');" class="small_link">{forms.L_MODULE_NAME}</a> |
                            # END forms #
                        </p>
                        </div>
                        <dl>
                            <dt>
                                <label id="advanced_search" style="display:none">
                                    <a href="javascript:ShowAdvancedSearchForms();">{L_ADVANCED_SEARCH}</a>
                                </label>
                                <label id="simple_search" style="display:none">
                                    <a href="javascript:HideAdvancedSearchForms();">{L_SIMPLE_SEARCH}</a>
                                </label>
                            </dt>
                            <dd></dd>
                        </dl>
                    </fieldset>
                    # START forms #
                        <div id="form_{forms.MODULE_NAME}" style="display:none">
                            <fieldset>
                                <legend>{L_ADVANCED_SEARCH} - {forms.L_MODULE_NAME}</legend>
                                {forms.SEARCH_FORM}
                            </fieldset>
                        </div>
                    # END forms #
                    <fieldset class="fieldset_submit">
                        <legend>{L_SEARCH}</legend>
                        <input type="submit" name="search_submit" id="search_submit" value="{L_SEARCH}" class="submit" />
                    </fieldset>
                </form>
            </div>
            <div class="module_bottom_l"></div>
            <div class="module_bottom_r"></div>
            <div class="module_bottom" style="text-align:center;">{HITS}</div>
        </div>

        <script type="text/javascript">
        <!--
            // On cache les �l�ments ne devant pas s'afficher au d�but
            HideAdvancedSearchForms();
        -->
        </script>