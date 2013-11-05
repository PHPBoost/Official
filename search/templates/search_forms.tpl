        <script type="text/javascript">
        <!--
            const FORM = 'form_';
            const SPECIALIZED_FORM_LINK = 'specialize_form_link_';
            var LastSpecializedFormUsed = 'all';
            
            function ChangeForm(module)
            // Change le cadre des résultats
            {
                hide_div(FORM + LastSpecializedFormUsed);
                show_div(FORM + module);

                
                document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).className = '';

                LastSpecializedFormUsed = module;
                document.getElementById('search_in').value = module;
                
                document.getElementById(SPECIALIZED_FORM_LINK + module).className = 'SFL_current';
            }
            
            function check_search_form_post()
            // V�rifie la validité du formulaire
            {
                var textSearched = document.getElementById("TxTsearched").value;
                if ( textSearched.length > 3 && textSearched != '{L_SEARCH}...')
                {
                    textSearched = escape_xmlhttprequest(textSearched);
                    return true;
                }
                else
                {
                    alert({L_WARNING_LENGTH_STRING_SEARCH});
                    return false;
                }
            }
        -->
        </script>

       <section>
           <header>
				<h1>{L_TITLE_SEARCH}</h1>
			</header>
            <div class="content">
                <div class="spacer">&nbsp;</div>
                <form action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
                    <div class="search_field"><input type="text" id="TxTsearched" name="q" value="{TEXT_SEARCHED}" class="field-xlarge" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';"></div>
                    <div class="spacer">&nbsp;</div>
					<div id="forms_selection" class="options">
						<a id="specialize_form_link_all" href="javascript:ChangeForm('all');" class="SFL_current">{L_SEARCH_ALL}</a>
						# START forms #
							<a id="specialize_form_link_{forms.MODULE_NAME}" href="javascript:ChangeForm('{forms.MODULE_NAME}');">{forms.L_MODULE_NAME}</a>
						# END forms #
					</div>
                    <div id="form_all" class="SpecializedForm">
                        <fieldset class="searchFieldset">
                            <div class="form-element">
                                <label>{L_SEARCH_IN_MODULES}<br /><span>{L_SEARCH_IN_MODULES_EXPLAIN}</span></label>
                                <div class="form-field">
                                    <select id="searched_modules" name="searched_modules[]" size="5" multiple="multiple" class="list_modules">
                                    # START searched_modules #
                                        <option value="{searched_modules.MODULE}" id="{searched_modules.MODULE}"{searched_modules.SELECTED}>{searched_modules.L_MODULE_NAME}</option>
                                    # END searched_modules #
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    # START forms #
                    <div id="form_{forms.MODULE_NAME}" class="SpecializedForm" style="display:none">
                        <fieldset class="searchFieldset">
                        # IF forms.C_SEARCH_FORM #{forms.SEARCH_FORM}# ELSE #<p class="center">{forms.SEARCH_FORM}</p># ENDIF #
                        </fieldset>
                    </div>
                    # END forms #
                    <fieldset class="fieldset_submit">
                        <legend>{L_SEARCH}</legend>
                        <input type="hidden" id="search_in" name="search_in" value="all">
                        <input type="hidden" id="query_mode" name="query_mode" value="0">
                        <button type="submit" id="search_submit" name="search_submit" value="{L_SEARCH}" class="submit"><i class="icon-search"></i> {L_SEARCH}</button>
                        <input type="hidden" name="token" value="{TOKEN}">
                    </fieldset>
                </form>
            </div>
             <footer></footer>
        </section>
        <script type="text/javascript">
        <!--
            ChangeForm('{SEARCH_MODE_MODULE}');
        -->
        </script>