		<section>					
			<header>
				<h1>
					<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" title="${LangLoader::get_message('syndication', 'main')}" class="fa fa-syndication"></a>
					{TITLE}
				</h1>
			</header>
			<div class="content">
				# INCLUDE wiki_tools #
				<div class="entete">
					<img class="pbt-entete-img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
					<div class="pbt-entete-content">
						<p class="pbt-entete-title">Bienvenue dans la documentation de PHPBoost.</p>
						<span class="pbt-entete-desc">
						<br />Que vous soyez un utilisateur d�butant ou confirm�, nous esp�rons que vous trouverez sur ces pages toutes les informations dont vous avez besoin. Dans le cas contraire, utilisez le <a href="{PATH_TO_ROOT}/forum/">Forum du projet</a> pour votre demande d'information.</span>
					</div>
				</div>
				
				<hr style="margin:25px 0px;" />

				<section class="block">
					<header>
						<h1>A propos de PHPBoost</h1>
					</header>
					<div class="content">
						<ul class="no-list pbt-content-about">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost">Pr�sentation de PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/download/">T�l�charger PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/installation-de-phpboost">Installation de PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-son-site-vers-une-nouvelle-version">Comment migrer vers une nouvelle version</a>
							</li>
						</ul>
						
						<ul class="no-list pbt-content-about">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/forum/">Forum</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/bugtracker/">Rapporter un bug</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-theme">Cr�er un th�me</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-module">Cr�er un module</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>

				<section>
					<header>
						<h1>
							<a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost">Pr�sentation de PHPBoost</a>
							<span class="more">Tout savoir sur le projet</span>
						</h1>
					</header>
					<div class="content">
						<p class="more">Les articles<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost">Pr�sentation de PHPBoost</a>
								<span class="more"> - Tout savoir sur le projet</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/phpboost-4-0">PHPBoost 4.0</a>
								<span class="more"> - Les nouveaut�s de la version 4.0 de PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/phpboost-3-0">PHPBoost 3.0</a>
								<span class="more"> - Les nouveaut�s de la version 3.0 de PHPBoost</span>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>
				
				<section>
					<header>
						<h1>
							<a href="{PATH_TO_ROOT}/wiki/installation-et-migration-de-phpboost">Installation et migration de PHPBoost</a>
							<span class="more">Installer et migrer PHPBoost tr�s facilement</span>
						</h1>
					</header>
					<div class="content">
						<p class="more">Les cat�gories<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/installation-de-phpboost">Installation de PHPBoost</a>
								<span class="more"> - Obtenir une documentation d�taill�e sur l'installation de PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/changement-d-hebergement-ou-de-serveur">Changement d'h�bergement ou de serveur</a>
								<span class="more"> - Changer de serveur sans aucun probl�me</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-son-site-vers-une-nouvelle-version">Migrer son site vers une nouvelle version</a>
								<span class="more"> - Passer aux nouvelles versions majeures pour obtenir plus de fonctionnalit�s</span>
							</li>
						</ul>
						
						<p class="more">Les articles<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/liste-des-modules-supportes-et-contenus-dans-votre-distribution">Liste des modules support�s et contenus dans votre distribution</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>		
			
				<section>
					<header>
						<h1>
							<a href="{PATH_TO_ROOT}/wiki/utilisation-de-phpboost">Utilisation de PHPBoost</a>
							<span class="more">Bien commencer avec PHPBoost</span>
						</h1>
					</header>
					<div class="content">
					
						<p class="more">Les cat�gories<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/panneau-d-administration">Panneau d'administration</a>
								<span class="more"> - Documentation relative � l'administration et � son utilisation</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/panneau-de-contribution">Panneau de contribution</a>
								<span class="more"> - Laisser vos membres int�ragir avec votre site</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/panneau-de-moderation">Panneau de mod�ration</a>
								<span class="more"> - Tout savoir sur la mod�ration de PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/modules">Modules</a>
								<span class="more"> - Documentation des modules officiels de PHPBoost</span>
							</li>
						</ul>
						
						<p class="more">Les articles<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/utiliser-phpboost-en-ligne-de-commande">Utiliser PHPBoost en ligne de commande</a>
							</li>
						</ul>
						
					</div>
					<footer></footer>
				</section>	
				
				
				<section>
					<header>
						<h1>
							<a href="{PATH_TO_ROOT}/wiki/design-et-interface">Design et Interface</a>
							<span class="more">Personnaliser l'aspect de votre site</span>
						</h1>
					</header>
					<div class="content">
					
						<p class="more">Les cat�gories<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-theme">Cr�er un th�me</a>
								<span class="more"> - De A � Z</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/personnaliser-un-theme">Personnaliser un th�me</a>
								<span class="more"> - Modifier un th�me existant</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-un-theme-vers-une-nouvelle-version">Migrer un th�me vers une nouvelle version</a>
								<span class="more"> - Faire �voluer votre th�me</span>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>	
				
				<section>
					<header>
						<h1>
							<a href="{PATH_TO_ROOT}/wiki/developper-avec-phpboost">D�velopper avec PHPBoost</a>
							<span class="more">Cr�er des modules et du contenu dynamique</span>
						</h1>
					</header>
					<div class="content">
					
						<p class="more">Les cat�gories<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-module">Cr�er un module</a>
								<span class="more"> - Tout savoir sur la cr�ation d'un module</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-menu">Cr�er un menu</a>
								<span class="more"> - Tout savoir sur la cr�ation d'un menu</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-un-module-vers-une-nouvelle-version">Migrer un module vers une nouvelle version</a>
								<span class="more"> - Faire �voluer votre module</span>
							</li>
						</ul>
						
						<p class="more">Les articles<p>
						<ul class="no-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/conventions-de-developpement">Conventions de d�veloppement</a>
								<span class="more"> - Styles de d�veloppement adopt�s sur le projet PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/debogage-dans-phpboost">D�bogage dans PHPBoost</a>
								<span class="more"> - Les outils mis � disposition pour d�boger votre code</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/constantes-chargees-avec-l-environnement-phpboost">Constantes charg�es avec l'environnement PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/utilisateur-courant">Utilisateur courant</a>
								<span class="more"> - Obtenir des renseignements et son niveau d'autorisation sur l'utilisateur courant</span>
							</li>
							<li>
								<a href="{PATH_TO_ROOT}/wiki/developper-avec-phpboost">
									<span class="more" style="font-style:normal;">[+ d'articles]</span>
								</a>
							</li>
						</ul>
						
					</div>
					<footer></footer>
				</section>	

				<hr style="margin:5px 0px 25px 0px;" />
				
				# IF IS_MODERATOR #
					<div style="text-align:center;">	
						<a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}" class="pbt-button-a">
							<button class="big">
								<i class="fa fa-folder-open"></i> {L_EXPLORER}
							</button>
						</a>
					</div>
					
					<br />
					# START last_articles #	
					<hr style="margin:0px 0px 25px 0px;" />			
					<br />
					<table class="module-table">
						<tr>
							<th colspan="2">
								<strong><em>{last_articles.L_ARTICLES}</em></strong> {last_articles.RSS}
							</th>
						</tr>
						<tr>
							# START last_articles.list #
							{last_articles.list.TR}
								<td style="width:50%">
									<img src="{PICTURES_DATA_PATH}/images/article.png" class="valign-middle" alt="" />&nbsp;<a href="{PATH_TO_ROOT}/wiki/{last_articles.list.U_ARTICLE}">{last_articles.list.ARTICLE}</a>
								</td>
							# END last_articles.list #
							{L_NO_ARTICLE}
						</tr>
					</table>
					# END last_articles #
				# ENDIF #
			</div>
			<footer></footer>
		</section>		