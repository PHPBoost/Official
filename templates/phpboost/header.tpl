<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION} {TITLE}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/content.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/bbcode.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/framework/content/syndication/syndication.css" type="text/css" media="screen, print, handheld" />
		{ALTERNATIVE_CSS}
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
		<link rel="alternate" href="{PATH_TO_ROOT}/news/syndication.php" type="application/rss+xml" title="RSS {SITE_NAME}" />
		
		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
		-->
		</script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/global.js"></script>
	</head>
	<body>

# IF C_ALERT_MAINTAIN #
<div style="position:absolute;top:5px;width:99%;">
	<div style="position:relative;width:400px;margin:auto;" class="warning">
		{L_MAINTAIN_DELAY}
		<br /><br />
		<script type="text/javascript">
			document.write('<div id="release">{L_LOADING}{PATH_TO_ROOT}.</div>');
		</script>
		<noscript>
			<strong>{DELAY}</strong>
		</noscript>
	</div>
</div>
<script type="text/javascript">
<!--
function release(year, month, day, hour, minute, second)
{
	if( document.getElementById('release') )
	{
		var sp_day = 86400;
		var sp_hour = 3600;
		var sp_minute = 60;
		
		now = new Date();
		end = new Date(year, month, day, hour, minute, second);
		
		release_time = (end.getTime() - now.getTime())/1000;
		if( release_time <= 0 )
		{
			document.location.reload();
			release_time = '0';
		}
		else
			timeout = setTimeout('release('+year+', '+month+', '+day+', '+hour+', '+minute+', '+second+')', 1000);
		
		release_days = Math.floor(release_time/sp_day);
		release_time -= (release_days * sp_day);
		
		release_hours = Math.floor(release_time/sp_hour);
		release_time -= (release_hours * sp_hour);

		release_minutes = Math.floor(release_time/sp_minute);
		release_time -= (release_minutes * sp_minute);

		release_seconds = Math.floor(release_time);
		release_seconds = (release_seconds < 10) ? '0' + release_seconds : release_seconds;
		
		document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> {L_DAYS} <strong>' + release_hours + '</strong> {L_HOURS} <strong>' + release_minutes + '</strong> {L_MIN} <strong>' + release_seconds + '</strong> {L_SEC}';
	}
}
release({L_RELEASE_FORMAT});
-->
</script>
# ENDIF #

<div id="scroll_top_page" />
<div id="global">
	<div id="header">
		<h1 style="display:none;font-size:9px;">{SITE_NAME}</h1>
		{MODULES_MINI_HEADER_CONTENT}
		<div class="dynamic_menu" style="padding-left:190px;top:113px">
			<ul>
				<li onmouseover="show_menu('l0', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="{PATH_TO_ROOT}/index.php" title="PHPBoost"><img src="{PATH_TO_ROOT}/templates/phpboost/images/admin/admin_mini.png" class="valign_middle" alt="" />PHPBoost</a></h5>
                    <ul id="smenul0">
                        <li><a href="{PATH_TO_ROOT}/index.php" title="Accueil" style="background-image:url({PATH_TO_ROOT}/templates/phpboost/images/admin/admin_mini.png);">Accueil</a></li>
                        <li><a href="http://demo.phpboost.com" title="D�monstration PHPBoost" style="background-image:url({PATH_TO_ROOT}/templates/phpboost/images/admin/com_mini.png);">D�monstration</a></li>
                    </ul>
				</li>
				<li onmouseover="show_menu('l1', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="{PATH_TO_ROOT}/download/download-52+phpboost-2-0.php" title="T�l�charger"><img src="{PATH_TO_ROOT}/download/download_mini.png" class="valign_middle" alt="" />T�l�charger</a></h5>
					<ul id="smenul1">
						<li><a href="{PATH_TO_ROOT}/download/download-52+phpboost-2-0.php" style="background-image:url({PATH_TO_ROOT}/download/download_mini.png);">PHPBoost 2</a></li>
                        <li><a href="{PATH_TO_ROOT}/download/category-24+modules.php" style="background-image:url({PATH_TO_ROOT}/templates/phpboost/images/admin/modules_mini.png);">Modules</a></li>
						<li><a href="http://themes.phpboost.com" style="background-image:url({PATH_TO_ROOT}/templates/phpboost/images/admin/themes_mini.png);">Th�mes</a></li>
					</ul>
				</li>
				<li onmouseover="show_menu('l2', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="{PATH_TO_ROOT}/wiki/wiki.php" title="Documentation PHPBoost"><img src="{PATH_TO_ROOT}/wiki/wiki_mini.png" class="valign_middle" alt="" /> Documentation</a></h5>
					<ul id="smenul2"><li><a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost" style="background-image:url({PATH_TO_ROOT}/images/doc/presentation_mini.png);">Pr�sentation</a></li>
						<li><a href="{PATH_TO_ROOT}/wiki/installation" style="background-image:url({PATH_TO_ROOT}/images/doc/installation_mini.png);">Installation</a></li>
						<li><a href="{PATH_TO_ROOT}/wiki/utilisation" style="background-image:url({PATH_TO_ROOT}/images/doc/utilisation_mini.png);">Utilisation</a></li>
						<li><a href="{PATH_TO_ROOT}/wiki/modules" style="background-image:url({PATH_TO_ROOT}/images/doc/modules_mini.png);">Modules</a></li>
						<li><a href="{PATH_TO_ROOT}/wiki/personnalisation-de-phpboost" style="background-image:url({PATH_TO_ROOT}/images/doc/personnalisation_mini.png);">Personnalisation</a></li><li><a href="{PATH_TO_ROOT}/wiki/developpement" style="background-image:url({PATH_TO_ROOT}/images/doc/developpement_mini.png);">D�veloppement</a></li>
						<li><a href="{PATH_TO_ROOT}/pages/videos-de-demonstration" style="background-image:url({PATH_TO_ROOT}/images/doc/videos_mini.png);">Vid�os</a></li>
						<li><a href="{PATH_TO_ROOT}/faq/faq.php" style="background-image:url({PATH_TO_ROOT}/faq/faq_mini.png);">FAQ</a></li>
					</ul>
				</li>
				<li>
					<h5 class="links"><a href="{PATH_TO_ROOT}/articles/articles.php" title="Dossiers"><img src="{PATH_TO_ROOT}/articles/articles_mini.png" class="valign_middle" alt="" /> Dossiers</a></h5>
				</li>
				<li onmouseover="show_menu('l4', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="{PATH_TO_ROOT}/forum/index.php" title="Communaut�"><img src="{PATH_TO_ROOT}/forum/forum_mini.png" class="valign_middle" alt="" /> Communaut�</a></h5>
					<ul id="smenul4">
						<li><a href="{PATH_TO_ROOT}/forum/index.php" style="background-image:url({PATH_TO_ROOT}/forum/forum_mini.png);">Forum</a></li>
						<li><a href="{PATH_TO_ROOT}/shoutbox/shoutbox.php" style="background-image:url({PATH_TO_ROOT}/shoutbox/shoutbox_mini.png);">Discussion</a></li>
						<li><a href="{PATH_TO_ROOT}/newsletter/newsletter.php" style="background-image:url({PATH_TO_ROOT}/newsletter/newsletter_mini.png);">Newsletter</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div id="sub_header">
		{MODULES_MINI_SUB_HEADER_CONTENT}
	</div>
	<div id="links_vertical">
		<div id="links_vertical_top">
			<p style="margin:0px;"><img onclick="new Effect.ScrollTo('scroll_top_page',{duration:1.2}); return false;" style="cursor:pointer;" src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></p>
			<hr style="width:50%;margin:auto;" />
		</div>
		
		<div id="links_vertical_central">
			<p style="margin:0;margin-top:5px;"><a href="{PATH_TO_ROOT}/news/news.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/admin_mini.png" alt="" /></a></p>
	# IF C_MEMBER_CONNECTED #
			<p style="margin:0"><a href="{PATH_TO_ROOT}/member/member{U_MEMBER_ID}" class="small_link" title="{L_PRIVATE_PROFIL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /></a></p>
			<p style="margin:0"><a href="{U_MEMBER_PM}" class="small_link" title="{L_NBR_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_PM}" class="valign_middle" alt="" /></a></p>
			# IF C_ADMIN_AUTH #
			<p style="margin:0"><a href="{PATH_TO_ROOT}/admin/admin_index.php" class="small_link" title="{L_ADMIN_PANEL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /></a></p>
			# ENDIF #
			# IF C_MODO_AUTH #
			<p><a href="{PATH_TO_ROOT}/member/moderation_panel.php" class="small_link" title="{L_MODO_PANEL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modo_mini.png" alt="" class="valign_middle" /></a></p>
			# ENDIF #
	# END ENDIF #
		</div>
		
		<div id="links_vertical_bottom">
			<hr style="width:50%;margin:auto;" />
			<p style="margin:0;margin-top:5px;"><img onclick="new Effect.ScrollTo('scroll_bottom_page',{duration:1.2}); return false;" style="cursor:pointer;" src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></p>
		</div>
	</div>
	
	
	# IF C_COMPTEUR #
	<div id="compteur">
		<span class="text_strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
		<br />
		<span class="text_strong">{L_TODAY}:</span> {COMPTEUR_DAY}
	</div>
	# ENDIF #
	
	# IF C_START_LEFT #
	<div id="left_menu">
		<p class="text_center">
			<a href="{PATH_TO_ROOT}/download/download-52+phpboost-2-0.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/theme/download_phpboost.png" id="download_phpboost" alt="" onmouseover="change_img_path('download_phpboost', '{PATH_TO_ROOT}/templates/{THEME}/images/theme/download_phpboost2.png')" onmouseout="change_img_path('download_phpboost', '{PATH_TO_ROOT}/templates/{THEME}/images/theme/download_phpboost.png')" /></a>
		</p>
		{MODULES_MINI_LEFT_CONTENT}
	</div>
	# ENDIF #
	
	# IF C_START_RIGHT #
	<div id="right_menu">
		{MODULES_MINI_RIGHT_CONTENT}
	</div>
	# ENDIF #
	
	<div id="main">
		<div id="links">
			&nbsp;&nbsp;<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a>
			# START link_bread_crumb #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" /> <a class="small_link" href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}">{link_bread_crumb.TITLE}</a>
			# END link_bread_crumb #
		</div>	
		<div id="top_contents">
			{MODULES_MINI_TOPCENTRAL_CONTENT}
		</div>
