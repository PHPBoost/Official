		</div>
		# IF C_MENUS_BOTTOM_CENTRAL_CONTENT #
        <div id="bottom_contents">
			{MENUS_BOTTOMCENTRAL_CONTENT}
		</div>
		# ENDIF #
	</div>
	# IF C_MENUS_TOP_FOOTER_CONTENT #
	<div id="top_footer">
		{MENUS_TOP_FOOTER_CONTENT}
		<div class="spacer"></div>
	</div>
	# ENDIF #
</div>
	<div id="footer">
		<div id="footer_columns_container">
			<div class="footer_columns">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/partners.png" align="center"  width="25px" />
					Les partenaires
				</div>
				<div class="footer_columns_partners">
					<a href="http://www.nuxit.com/" style="text-decoration:none; ">
						<p style="font-size:9px;color:#dfa959;line-height:0px;margin-bottom: 0px;font-weight:bold;">H�bergement de site web</p>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/nuxit.png" align="center" />
						<p style="font-size:9px;font-style:italic;color:#8bb9ff;">Qualit�, fiabilit�, Support</p>
					</a>
				</div>
			</div>
			<div class="footer_columns">
				<div class="footer_columns_title"> 
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" align="center"  width="25px" />
					Le projet PHPBoost
				</div>
				<ul>
					<li><a href="#">Fonctionnalit�s</a></li>
					<li><a href="#">T�l�charger</a></li>
					<li><a href="http://demo.phpboost.com">D�monstration</a></li>
					<li><a href="{PATH_TO_ROOT}/pages/aider-phpboost">Contribuer au projet</a></li>
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/community.png" align="center"  width="25px" />
					Participer au Projet
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/wiki/creer-un-theme">Cr�er un Th�me</a></li>
					<li><a href="{PATH_TO_ROOT}/wiki/creer-un-module">Cr�er un Module</a></li>
					<li><a href="{PATH_TO_ROOT}/doc/">A.P.I.</a></li>
					<li><a href="{PATH_TO_ROOT}/bugtracker/">Rapport de bugs</a></li>
					
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/support.png" align="center"  width="25px" />
					Support PHPBoost
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/faq/">Foire aux Questions</a></li>
					<li><a href="{PATH_TO_ROOT}/forum/">Forum</a></li>
					<li><a href="{PATH_TO_ROOT}/news/">News</a></li>
					<li><a href="{PATH_TO_ROOT}/wiki/">Documentation</a></li>
				</ul>
			</div>	
		</div>
		<div class="spacer"></div>
		<div id="footer_links">
			# IF C_MENUS_FOOTER_CONTENT #
			{MENUS_FOOTER_CONTENT}
			# ENDIF #
			<span>
				{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
			</span>	
			# IF C_DISPLAY_BENCH #
			<span>
				&nbsp;|&nbsp;		
				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ}
			</span>	
			# ENDIF #
			# IF C_DISPLAY_AUTHOR_THEME #
			<span>
				| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}" style="font-size:10px;">{L_THEME_AUTHOR}</a>
			</span>
			# ENDIF #
		</div>
	</div>
	</body>
</html>