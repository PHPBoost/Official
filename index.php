<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright            : (C) 2007 CrowkaiT
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
require_once('./kernel/framework/content/syndication/feed.class.php');

global $LANG;
?>
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left">
                    <span id="news_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                        <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                    </span>&nbsp;
                    <?php echo get_feed_menu('/news/syndication.php'); ?>
                    <h3 class="title valign_middle">Derni�res news</h3>
                </div>
                <div style="float:right"></div>
            </div>
            <div class="news_content">
                <?php echo Feed::get_parsed('news', 0); ?>
                <div style="text-align:right;"><a href="./news/news.php" class="small_link">Plus de news...</a></div>
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
                <span id="articles_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/articles/syndication.php'); ?>
                <h3 class="title valign_middle">Dossiers</h3>
            </div>
            <div class="news_content">
                <?php
            		load_module_lang('articles');
                    echo Feed::get_parsed('articles', 0, array('L_READ' => $LANG['read_feed'], 'L_POSTED_ON' => $LANG['posted_on'], 'L_ON' => $LANG['on']) , 3);
                ?>
                <div style="text-align:right;"><a href="./articles/articles.php" class="small_link">Tous les Dossiers...</a></div>
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
                <h3 class="sub_title">Le Lien Lact�</h3>
				<br />
				<p>
					<a href="http://lelienlacte.com" title="Le lien lact�"><img src="./upload/lelienlacte.jpg" class="img_right" alt="" /></a>
					Le Lien Lact� est un site d'information et de soutien � l'allaitement maternel cr�� par une maman en novembre 2002. Construit � l'origine avec un autre CMS, sa conceptrice a opt� pour PHPBoost d�but 2008, s�duite par ses possibilit�s aussi bien au niveau du contenu (en particulier le module de Wiki) qu'au niveau des possibilit�s de personnalisation gr�ce au syst�me des templates.
					<br />
					<br />
					Ce site est avant tout une communaut� de mamans et leur propose une riche documentation sur l'allaitement maternel r�unie dans le wiki rebaptis� "encyclo-lact�e" (bibliographie, questions fr�quentes, dossiers pratiques, t�moignages, etc..) mais �galement une galerie de photos, un forum de discussion, des documents � t�l�charger au format pdf et un annuaire de liens tr�s fourni.
					<br />
					<br />
					Le th�me graphique principal � l'origine cr�� par un membre de l'�quipe du CMS GuppY a �t� adapt� � PHPBoost par la "webcheftaine" du site.
					<br /><br /><br />
					Pour visiter le site: <a href="http://lelienlacte.com" title="Mussotrail">lelienlacte.com</a>
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
                <span id="download_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/download/syndication.php'); ?>
                <h3 class="title valign_middle">Derniers Modules</h3>
            </div>
            <div class="news_content">
                <?php
            		load_module_lang('download');
                    global $DOWNLOAD_LANG;
                    $dw_langs = array('L_READ' => $DOWNLOAD_LANG['read_feed'], 'L_ON' => $LANG['on']);
                    echo Feed::get_parsed('download', 0, $dw_langs, 5);
                    // echo Feed::get_parsed('download', 24, false, 3);
                ?>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="download_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/download/syndication.php'); ?>
                <h3 class="title valign_middle">Derniers Th�mes</h3>
            </div>
            <div class="news_content">
                <?php
                    echo Feed::get_parsed('download', 0, $dw_langs, 5);
                    //echo Feed::get_parsed('download', 24, false, 3);
                ?>
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
                <span id="forum_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/forum/syndication.php'); ?>
                <h3 class="title valign_middle">Derniers sujets du forum</h3>
            </div>
            <div class="news_content">
                <?php echo Feed::get_parsed('forum', 0); ?>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="forum_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/wiki/syndication.php'); ?>
                <h3 class="title valign_middle">Derni�res articles de la documentation</h3>
            </div>
            <div class="news_content">
                <?php echo Feed::get_parsed('wiki', 0); ?>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>

<?php

require_once('./kernel/footer.php');

?>