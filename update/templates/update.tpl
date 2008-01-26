<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>{L_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="PHPBoost" />
		<link type="text/css" href="templates/update.css" title="phpboost" rel="stylesheet" />
		<link rel="shortcut" href="../favicon.ico" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
			var speed_progress = 20;
			var timeout = null;
			var max_percent = 0;
			var info_progress_tmp = '';
			var step = {NUM_STEP};
			
			function progress_bar(percent_progress, info_progress, restart_progress)
			{
				bar_progress = (percent_progress * 55) / 100;
				
				if (arguments.length == 5)
				{
					result_id = arguments[3];
					result_msg = arguments[4];
				}
				else
				{
					result_id = "";
					result_msg = "";
				}	
				
				// D�claration et initialisation d'une variable statique
			    if( typeof this.percent_begin == 'undefined' || restart_progress == 1 ) 
				{	
					this.percent_begin = 0;
					max_percent = 0;
					if( document.getElementById('progress_bar') )
						document.getElementById('progress_bar').innerHTML = '';
				}
			
				if( this.percent_begin <= bar_progress )
				{
					if( document.getElementById('progress_bar') )
						document.getElementById('progress_bar').innerHTML += '<img src="templates/images/loading.png" alt="" />';
					if( document.getElementById('progress_percent') )
						document.getElementById('progress_percent').innerHTML = Math.round((this.percent_begin * 100) / 55);
					if( document.getElementById('progress_info') )
					{	
						if( percent_progress > max_percent )
						{	
							max_percent = percent_progress;
							info_progress_tmp = info_progress;
						}
						document.getElementById('progress_info').innerHTML = info_progress_tmp;
					
					}
					//Message de fin
					if( this.percent_begin == 55 && result_id != "" && result_msg != "" )
						document.getElementById(result_id).innerHTML = result_msg;
					timeout = setTimeout('progress_bar(' + percent_progress + ', "' + info_progress + '", 0, "' + result_id + '", "' + result_msg.replace(/"/g, "\\\"") + '")', speed_progress);
				}
				else
					this.percent_begin = this.percent_begin - 1;
				this.percent_begin++;
			}
		-->
		</script>
		<div id="header">
			<img src="templates/images/header_boost.jpg" alt="PHPBoost" />
		</div>

		<div id="sub_header">
			<div id="sub_header_left">
			</div>
			<div id="sub_header_right">
			</div>
		</div>
		<div id="left_menu">
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_STEPS_LIST}
					</td>
				</tr>
				# START link_menu #
					{link_menu.ROW}
				# END link_menu #
			</table>
			
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_INSTALL_PROGRESS}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<div style="margin:auto;width:235px">
							<div style="text-align:center;">{L_STEP}</div>
							<div style="float:left;height:12px;border:1px solid black;background:white;width:192px;padding:2px;padding-left:3px;padding-right:1px;">
								{PROGRESS_BAR_PICS}
							</div>&nbsp;{PROGRESS_LEVEL}%
						</div>
					</td>
				</tr>						
			</table>
			
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_APPENDICES}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/help.png" alt="{L_DOCUMENTATION}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_DOCUMENTATION}">{L_DOCUMENTATION}</a>
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/intro.png" alt="{L_RESTART_INSTALL}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_RESTART}" onclick="return confirm('{L_CONFIRM_RESTART}');">{L_RESTART_INSTALL}</a>
					</td>
				</tr>					
			</table>
		</div>
		
		<div id="main">
			<table class="table_contents">
				<tr> 
					<th colspan="2">
						{L_STEP}
					</th>
				</tr>
				
				<tr> 					
					# START intro #
					<td class="row_contents">						
						<span style="float:left;padding:8px;padding-top:0px">
							<img src="templates/images/phpboost.png" alt="Logo PHPBoost" />
						</span>
						Vous �tes sur le point de mettre � jour PHPBoost de la version 1.6.0 � la 2.0.
						<br />
						L'installation se fera en plusieurs parties, en un premier temps vous mettrez � jour le noyau de PHPBoost (la partie fixe) et ensuite chaque module un par un.
						<fieldset class="submit_case">
							<a href="{L_NEXT_STEP}" title="{L_START_INSTALL}" ><img src="templates/images/right.png" alt="{L_START_INSTALL}" /></a>
						</fieldset>		
					</td>
					# END intro #
					
					# START kernel_update #
					<td class="row_contents">						
						Cette �tape concerne la mise � jour du noyau, c'est � dire l'importation dans la nouvelle structure des anciennes donn�es principales dans la nouvelle structure. Les mises � jour concernant chaque module se feront ult�rieurement.
						<br />
						<div class="warning">
							Les messages priv�s ne seront pas conserv�s.
							<br />
							Certaines de vos configurations seront perdues, pensez � noter la configuration actuelle.
						</div>
						# START error #
							<br />
							<div class="error">
								{kernel_update.error.ERROR}
							</div>
						# END error #
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}" ><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />
							</fieldset>		
						</form>
					</td>
					# END kernel_update #
					
					# START articles_update #
					<td class="row_contents">						
						Vous allez ici mettre � jour la table articles. Vos anciens articles et cat�gories seront import�s.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Nouveaut�: Gestion totale de la parution de l'article, date de d�but/fin d'affichage, intervalle d'affichage.</li>
									<li>Nouveaut�: Gestion des sous-cat�gories infinies.</li>
									<li>Possibilit� d'afficher les cat�gories sur plusieurs colonnes (configurable).</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END articles_update #
								
					# START calendar_update #
					<td class="row_contents">						
						Tous les �vennements du calendrier seront import�s.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Mini calendrier de saisie</li>
									<li>Corrections, am�liorations et int�gration du mini-calendrier en popup pour la saisie des dates.</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END calendar_update #
					
					
					# START forum_update #
					<td class="row_contents">						
						Tous les �v�nements du calendrier seront import�s.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Nouveaut�: Cr�ation de sous-forums illimit�.
									</li><li>Nouveaut�: Gestion compl�te des mutligroupes, gestion des droits tr�s fine (lecture, �criture, �dition) pour chaque cat�gorie et pour chaque groupes. Autorisations globales des groupes sur le forum (flood, marqueurs d'�dition, etc..).
									</li><li>Nouveaut�: Suppression des messages instantan�e sur le forum (sans rechargement de la page gr�ce � Ajax).
									</li><li>Nouveaut�: Int�gration du module de gestion des m�dias, ajout d'images sur le forum automatis� par attachement de l'image au message.
									</li><li>Nouveaut�: Possibilit� de choisir d'�tre pr�venu (ou non) lors d'un nouveau message par messages priv�s ou par mails (si d�connect� du site), pour chaque sujets suivis du forum. Ajout d'une option de suppression des sujets suivis.
									</li><li>Nouveaut�: Possibilit� d'afficher les derniers messages lu, afin de faciliter leur suivi.
									</li><li>Nouveaut�: Possibilit� de masquer les menus de gauche et droite.
									</li><li>Possibilit� de pr�d�finir un texte ins�r� devant le topic, ajout� automatiquement � l'�dition (ex: [R�solu] Nom du topic).
									</li><li>Possibilit� de choisir le contenu du message envoy� lors de l'avertissement/mise en lectures seule d'un membre.
									</li><li>Nouvelle page de statistiques, ajout de la moyenne de sujets/messages par jour et du nombre de sujets/messages total et de la journ�e.
									</li><li>Possibilit� de mettre � jour les donn�es en cache (recompte le nombre de topics et de messages pour chaque cat�gories).</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END forum_update #

					# START gallery_update #
					<td class="row_contents">						
						Toutes les images de votre galerie seront import�s
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Nouveaut�: Refonte compl�te du module, avec gestion des sous-albums infinis.
									<li>Nouveaut�: Nouveau mode d'affichage des images (plein �cran, agrandissement, etc...).</li>
									<li>Nouveaut�: Interface de visualisation avec d�filement des miniatures.</li>
									<li>Menu d�filant avec affichage de plusieurs photos (configurable) dans ordre al�atoire (mini galerie).</li>
								</ul>
						</fieldset>
						<br />
						# START error #
							<div class="error">
								{gallery_update.error.ERROR}
							</div>
						# END error #
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END gallery_update #

					# START guestbook_update #
					<td class="row_contents">						
						Les anciens messages du livre d'or vont �tre copi�s vers la nouvelle version.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Configuration du livre d'or dans l'administration, rang pour pouvoir poster, balises interdites...</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END guestbook_update #
					
					# START news_update #
					<td class="row_contents">						
						Toutes les news seront r�cup�r�es.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Nouveaut�: Gestion totale de la parution des news, date de d�but/fin d'affichage, intervalle d'affichage.</li>
									<li>Nouveaut�: Gestion des cat�gories pour les news, avec description et ic�ne associ� � la news (d�sactivable), lien permettant l'affichage des news par cat�gories.</li>
									<li>Nouveaut�: Possibilit� de tronquer l'affichage de la news, un lien permet de lire la suite.</li>
									<li>Nouveaut�: Gestion du syst�me de m�dia int�gr� aux news, permet l'ajout simplifi� des images.</li>
									<li>Nouveaut�: Possibilit� de changer la date de parution de la news (classement des news possible).</li>
									<li>Nouveaut�: Possibilit� d'afficher les news sur plusieurs colonnes (configurable).</li>
									<li>Ajout du titre de la news dans l'url rewriting.</li>
								</ul>
						</fieldset>
						<br />
						<div class="warning">
							Toutes les news seront approuv�es.
						</div>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END news_update #
					
					# START pages_update #
					<td class="row_contents">						
						Toutes les news seront r�cup�r�es.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Gestion des cat�gories infinies</li>
									<li>Langage hybride : HTML ou BBCode</li>
									<li>Commentaires en option pour chaque page</li>
									<li>Possibilit� de cr�er des redirections d'une page vers une autre</li>
									<li>Optimisation en ce qui concerne le r�f�r�ncement de vos pages</li>
								</ul>
						</fieldset>
						<div class="warning">
							La syntaxe des pages ayant chang� on ne peut pas garantir l'exactitude de l'importation des pages. Il est donc vivement conseill� de sauvegarder vos pages (par votre client ftp t�l�charger le contenu du dossier page) car � la fin du traitement elles seront supprim�es.
							<br />
							Veillez aussi � reprendre les autorisations pour chaque page, elles ne seront pas conserv�es.
						</div>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END pages_update #

					# START shoutbox_update #
					<td class="row_contents">						
						Importation des messages de la shoutbox.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>D�lestage automatique des messages, configurable et d�sactivable.</li>
									<li>Configuration de la shoutbox dans l'administration, rang pour pouvoir poster, balises interdites...</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END shoutbox_update #
					
					# START web_update #
					<td class="row_contents">						
						Importe les liens web.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Liens en dur (adresse directe sur le bouton) avec compteur.</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END web_update #
					
					# START download_update #
					<td class="row_contents">						
						Importation des fichiers en t�l�chargement.
						<br />
						<fieldset>
							<legend>Nouveaut�s</legend>							    
								<ul>
									<li>Nouveaut�: Gestion totale de la parution du t�l�chargement, date de d�but/fin d'affichage, intervalle d'affichage.</li>
									<li>Possibilit� d'afficher les cat�gories sur plusieurs colonnes (configurable).</li>
									<li>Force le t�l�chargement des fichiers.</li>
									<li>Mise en cache des cat�gories.</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre � jour ce module vous pouvez ignorer cette �tape en cliquant sur le bouton associ� : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END download_update #
					
					# START cache #
					<td class="row_contents">						
						Finalisation de l'installation (r�g�n�ration du cache, mise en place des menus).
						<br />
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END cache #
					
					# START end #
					<td class="row_contents">						
						<fieldset>
							<legend>Mise � jour termin�e</legend>
							<div class="success">
								Votre portail PHPBoost est d�sormais � jour. Vos donn�es ont �t� import�es, mais certaines de vos configurations n'ont pas pu �tre reconduites. Nous vous prions de v�rifier chacune d'elles afin d'utiliser PHPBoost � votre sauce.
							</div>
							<br />
							Merci de nous faire confiance depuis un certain temps et de continuer. Bonne continuation sur PHPBoost.
						</fieldset>
						<fieldset>
							<legend>Rejoindre votre site</legend>
							<div class="warning">
								Il est important de supprimer le dossier update de votre site, cela pourrait vous poser des probl�mes de s�curit�.
							</div>
							<div style="text-align:center;">
								<a href="../news/news.php"><img src="templates/images/go-home.png" alt="Go home" /></a>
								<br />
								<a href="../news/news.php">Rejoindre le site</a>
							</div>
						</fieldset>
					</td>
					# END end #
				</tr>
			</table>		
		</div>
		<div id="footer">
			<span class="text_small">{L_GENERATED_BY}</span>
		</div>
	</body>
</html>