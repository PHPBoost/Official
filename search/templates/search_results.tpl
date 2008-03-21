        <br />
		<script type="text/javascript">
		<!--
		    const RESULTS = 'Results';
		    const RESULTS_TITLE = 'ResultsTitle';
		    const INFOS_RESULTS = 'infosResults';
		    const RESULTS_LIST = 'ResultsList';
		    const PAGINATION_RESULTS = 'PaginationResults';
            const NB_RESULTS_PER_PAGE = {NB_RESULTS_PER_PAGE};
            
            var nbResults = new Array();
            nbResults['All'] = {NB_RESULTS};
            
		    var modulesResults = new Array('All');
		    # START results #
		        modulesResults.push('{results.MODULE_NAME}');
		    # END results #
		    
		    var idSearch = new Array();
		    # START results #
		        idSearch['{results.MODULE_NAME}'] = '{results.ID_SEARCH}';
		    # END results #
		    
		    var calculatedResults = new Array('All');
		    
		    function HideResults()
		    // Cache tous les r�sultats
		    {
		        for( var i = 0; i < modulesResults.length; i++ )
		            hide_div(RESULTS + modulesResults[i]);
		    }
		    
		    function ChangeResults()
		    // Change le cadre des r�sultats
		    {
		        var module = document.getElementById('ResultsChoice').value;
		        HideResults();
		        show_div(RESULTS + module);
				if( !inArray(module, calculatedResults) )
				{
					load_progress_bar(1, '{THEME}', module, 55);
		            XMLHttpRequest_search_module(module);
				}
			}
		    
		    function GetFormData()
		    // Reconstitution d'une chaine "POSTABLE" � partir des formulaires
		    {
		        var dataString = "";
		        var form = document.getElementById('SearchForm');
		        var elements = form.elements;
		        
		        for( var i = 0; i < form.length; i++ )
		        {
					if( elements[i].name )
		            {
		                dataString += elements[i].name + "=" + escape_xmlhttprequest(elements[i].value);
						if( (i + 1) < form.length )
		                    dataString += "&";
		            }
		        }
		        
		        return dataString;
		    }
            
            function XMLHttpRequest_search_module(module)
		    // Affiche les r�sultats de la recherche pour le module particulier <module>
		    {
                var xhr_object = xmlhttprequest_init('../search/searchXMLHTTPRequest.php');
                xhr_object.onreadystatechange = function()
                {
                    if( xhr_object.readyState == 1 )
                        progress_bar(25, "{L_QUERY_LOADING}");
                    else if( xhr_object.readyState == 2 )
                        progress_bar(50, "{L_QUERY_SENT}");
                    else if( xhr_object.readyState == 3 )
                        progress_bar(75, "{L_QUERY_PROCESSING}");
                    else if( xhr_object.readyState == 4 )
                    {
                        if( xhr_object.status == 200 )
                        {
                            progress_bar(100, "{L_QUERY_SUCCESS}");
//                             document.getElementById('DEBUG').innerHTML = xhr_object.responseText;
                            // Si les r�sultats sont toujours en cache, on les r�cup�re.
                            eval(xhr_object.responseText);
                            if( !syncErr )
                            {
                                document.getElementById(INFOS_RESULTS + module).innerHTML = resultsAJAX['nbResults'];
                                document.getElementById(RESULTS_LIST + module).innerHTML = resultsAJAX['results'];
                                ChangePagination(0, Math.ceil(nbResults[module] / NB_RESULTS_PER_PAGE), PAGINATION_RESULTS + module, 'results' + module, 2, 2);
                                
                                // Met � jour la liste des r�sultats affich�, pour ne pas les rechercher
                                // dans la base de donn�e si ils sont d�j� dans le html.
                                calculatedResults.push(module);
                            }
                            else alert('SYNCHRONISATION ERROR');
                        }
                        else
                            progress_bar(99, "{L_QUERY_FAILURE}");
                    }
                }
                xmlhttprequest_sender(xhr_object, GetFormData() + '&moduleName=' + module + '&idSearch=' + idSearch[module]);
		    }
		-->
		</script>

		<div id="results" class="module_position">
		    <div class="module_top_l"></div>
		    <div class="module_top_r"></div>
		    <div class="module_top">{L_SEARCH_RESULTS}
		        <div id="resultsChoices" class="resultsChoices" style="display:none">
		            <span>{L_PRINT}</span>
		            <select id="ResultsChoice" name="ResultsSelection" onChange="ChangeResults();">
		                <option value="All">{L_TITLE_ALL_RESULTS}</option>
		                # START results #
		                    <option value="{results.MODULE_NAME}">---> {results.L_MODULE_NAME}</option>
		                # END results #
		            </select>
		        </div>
		    </div>
		    <div class="module_contents">
		        <div id="ResultsAll" class="results">
		            <span id="ResultsTitleAll" class="title">{L_TITLE_ALL_RESULTS}</span><br />
		            <div id="infosResultsAll" class="infosResults">
                        # IF NB_RESULTS #
                            {NB_RESULTS}
                        # ENDIF #
                        {L_NB_RESULTS_FOUND}
                    </div>
		            <div id="ResultsListAll" class="ResultsList">
                        {ALL_RESULTS}
		            </div>
		            <div id="PaginationResultsAll" class="PaginationResults">{PAGINATION}</div>
		        </div>
                <div id="DEBUG"></div>
		        # START results #
		            <div id="Results{results.MODULE_NAME}" class="results" style="display:none">
		                <span id="ResultsTitle{results.MODULE_NAME}" class="title">{results.L_MODULE_NAME}</span><br />
		                <div id="infosResults{results.MODULE_NAME}" class="infosResults">
                            <div style="margin:auto;width:500px;"> 
                                <div id="progress_info{results.MODULE_NAME}" style="text-align:center;"></div>
                                <div id="progress_bar{results.MODULE_NAME}" style="float:left;height:12px;border:1px solid black;background:white;width:448px;padding:2px;padding-left:3px;padding-right:1px;"></div> 
                                &nbsp;<span id="progress_percent{results.MODULE_NAME}">0</span>%
                            </div>
                        </div>
		                <div id="ResultsList{results.MODULE_NAME}" class="ResultsList"></div>
		                <div id="PaginationResults{results.MODULE_NAME}" class="PaginationResults"></div>
		            </div>
		        # END results #
		    </div>
		    <div class="module_bottom_l"></div>
		    <div class="module_bottom_r"></div>
		    <div class="module_bottom" style="text-align:center;">{L_HITS}</div>
		</div>
		<script type="text/javascript">
		<!--
            ChangePagination(0, Math.ceil(nbResults['All'] / NB_RESULTS_PER_PAGE), PAGINATION_RESULTS + 'All', 'resultsAll');
		    if( browserAJAXFriendly() )
                show_div('resultsChoices');
		-->
		</script>
