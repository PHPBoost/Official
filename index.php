<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright          : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PATH_TO_ROOT', '.');
require_once('./kernel/begin.php');
define('ALTERNATIVE_CSS', 'news');
define('TITLE', 'Votre site � port�e de main');
require_once('./kernel/header.php');

?>
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left">
                    <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)" onmouseout="HideSyndication(this)">
                        <a href="#" title="{L_SYNDICATION}">
                            <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                        </a>
                    </span>&nbsp;
                    <h3 class="title valign_middle">Derni�res news</h3>
                </div>
                <div style="float:right"></div>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="cache/syndication/news.js"></script>
                <script type="text/javascript">
                <!--
                    document.write(get_news_feed(10));
                -->
                </script>
                <div class="text_center"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Dossiers</h3>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="cache/syndication/articles.js"></script>
                <script type="text/javascript">
                <!--
                    document.write(get_articles_feed(4));
                -->
                </script>
                <div class="spacer"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:760px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle">Le projet PHPBoost</h3>
            </div>
            <div class="news_content">
                <img src="http://www.phpboost.com/upload/boostor_mini.jpg" class="img_right" alt="" />
                PHPBoost est un CMS (<em>Content Managing System</em> ou <em>syst�me de gestion de contenu</em>) <strong>fran�ais</strong>. Ce logiciel permet � n'importe qui de cr�er son site de fa�on tr�s simple, tout est assist�. Con�u pour satisfaire les d�butants, il devrait aussi ravir les utilisateurs exp�riment�s qui souhaiteraient pousser son fonctionnement ou encore d�velopper leurs propres modules.<br>
PHPBoost est un <strong><a href="http://fr.wikipedia.org/wiki/Logiciel_libre">logiciel libre</a></strong> distribu� sous la <a href="http://fr.wikipedia.org/wiki/Licence_publique_g%C3%A9n%C3%A9rale_GNU">licence GPL</a>.<br>

<br>
Comme son nom l'indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des op�rations dans la base de donn�es. Il s'installe sur un serveur Web et se param�tre � distance.<br>
<br>
Comme pour une grande majorit� de logiciels libres, la communaut� de PHPBoost lui permet d'avoir � la fois une fiabilit� importante car beaucoup d'utilisateurs ont test� chaque version et les ont ainsi approuv�es. Il b�n�ficie aussi par ailleurs d'une �volution rapide car nous essayons d'�tre le plus possible � l'�coute des commentaires et des propositions de chacun. M�me si tout le monde ne participe pas � son d�veloppement, beaucoup de gens nous ont aid�s, rien qu'en nous donnant des id�es, nous sugg�rant des modifications, des fonctionnalit�s suppl�mentaires.<br>
<br>
Si vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br>
<ul class="bb_ul"><li class="bb_li">Projet Open Source sous licence GNU/GPL
</li><li class="bb_li">Code XHTML 1.0 strict et s�mantique
</li><li class="bb_li">Multilangue
</li><li class="bb_li">Facilement personnalisable gr�ce aux th�mes et templates
</li><li class="bb_li">Gestion fine des droits et des groupes multiples pour chaque utilisateur
</li><li class="bb_li">Url rewriting
</li><li class="bb_li">Installation et mise � jour automatis�es des modules et du noyau
</li><li class="bb_li">Aide au d�veloppement de nouveaux modules gr�ce au framework de PHPBoost</li>
            </ul></div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:760px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle">Le site du moment</h3>
            </div>
            <div class="news_content">
                <a href="http://www.mussotrail.com" title="Mussotrail"><img src="./upload/theme.jpg" class="img_right" alt="" /></a>
                <h3 class="sub_title">Mussotrail</h3>
				<p>
					<br />
					Le site du Mussotrail, est un site d'un team de BMX et VTT �voluant dans le sud de la france.
					<br />
					Il s'agit du site � l'origine de PHPBoost, le projet est issu du d�veloppement de ce site.
					<br />
					Sa personnalisation pouss�e en font une r�f�rence en terme de sites utilisant le moteur PHPBoost.
					<br /><br /><br />
					Pour visiter le site: <a href="http://www.mussotrail.com" title="Mussotrail">Mussotrail.com</a>
				</p>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers Modules</h3>
            </div>
            <div class="news_content">
                <ul style="margin:0;padding:0;list-style-type:none;">
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">29/04</span> <a href="">Banni�re</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">25/04</span> <a href="">Team</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">23/04</span> <a href="">Agenda</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">22/04</span> <a href="">Match</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">20/04</span> <a href="">Wiki</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">18/04</span> <a href="">Blogs</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">24/03</span> <a href="">Bloc notes</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">12/03</span> <a href="">Bugshack</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">04/03</span> <a href="">Todo</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">19/02</span> <a href="">Partenariats</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">10/02</span> <a href="">Sitemap</a></li>
                </ul>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers Th�mes</h3>
            </div>
            <div class="news_content">
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701143224.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Medieval</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Voir</a></p>
                </div>
                <div class="spacer"></div>
                
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701280722.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Heroic fantasy</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Voir</a></p>
                </div>
                <div class="spacer"></div>
                
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701284286.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Warcraft III</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Voir</a></p>
                </div>
                <div class="spacer"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers sujets du forum</h3>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="./cache/rss_forum.html"></script>  
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derni�res vid�os</h3>
            </div>
            <div class="news_content">
                <ul style="margin:0;padding:0;list-style-type:none;">
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">29/04</span> <a href="">Banni�re</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">25/04</span> <a href="">Ajouter une news</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">24/03</span> <a href="">Ajouter des photos dans la galerie</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">12/03</span> <a href="">Cr�er des articles</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">09/03</span> <a href="">Ajouter des menus</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">04/03</span> <a href="">Fonctionnalit�s avanc�es du BBcode</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">28/02</span> <a href="">Sauvegarder votre base de donnn�es</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">19/02</span> <a href="">Transf�rer des fichiers sur votre FTP</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">10/02</span> <a href="">Installer PHPBoost</a></li>
                </ul>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>

<?php

require_once('./kernel/footer.php');

?>