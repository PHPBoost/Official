		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				{TOOLS}
				
				# START warning #
					<br /><br />
					<div class="row3">{warning.UPDATED_ARTICLE}</div>
					<br />
				# END warning #
						
				# START redirect #
					<div class="row2" style="width:30%;">
					{redirect.REDIRECTED}
						# START remove_redirection #
							<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" title="{redirect.remove_redirection.L_REMOVE_REDIRECTION}" onclick="javascript: return confirm('{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}');"><img src="{WIKI_PATH}/images/delete.png" alt="{redirect.remove_redirection.L_REMOVE_REDIRECTION}" style="vertical-align:middle;" /></a>
						# END remove_redirection #
					</div>
					<br />
				# END redirect #
				
				# START status #
					<br /><br />
					<div class="row3">{status.ARTICLE_STATUS}</div>
					<br />
				# END status #
				
				# START message #
					{message.ARTICLE_DOES_NOT_EXIST}
				# END message #
				
				# START menu #
					<div class="row3" style="width:60%">
						<div style="text-align:center;"><strong>{L_TABLE_OF_CONTENTS}</strong></div>
						{menu.MENU}
					</div>
				# END menu #
				<br /><br /><br />
				{CONTENTS}
				<br /><br />
				# START cat #
					<hr />
					<br />
					<strong>{L_SUB_CATS}</strong>
					<br /><br />
					# START list_cats #
						<img src="{WIKI_PATH}/images/cat.png"  style="vertical-align:middle;" alt="" />&nbsp;<a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a><br />
					# END list_cats #
					
					# START no_sub_cat #
					{cat.no_sub_cat.NO_SUB_CAT}<br />
					# END no_sub_cat #
					
					<br />
					<strong>{L_SUB_ARTICLES}</strong> &nbsp; {cat.RSS}
					<br /><br />
					# START list_art #
						<img src="{WIKI_PATH}/images/article.png"  style="vertical-align:middle;" alt="" />&nbsp;<a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a><br />
					# END list_art #
					
					# START no_sub_article #
					{cat.no_sub_article.NO_SUB_ARTICLE}
					# END no_sub_article #
					
				# END cat #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center;">{HITS}</div>
		</div>
		