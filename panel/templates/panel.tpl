		<div style="clear:both;width:100%;border:1px solid;">
		# START top #
		<div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {top.GET_FEED_MENU}
                <h3 class="title valign_middle">{top.NAME}</h3>
            </div>
            <div class="news_content">
                {top.GET_CONTENT}
            </div>
        </div>
		# END top #
		</div>
		
		<div style="float:left;width:50%;border:1px solid;">
		# START aboveleft #
		<div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {aboveleft.GET_FEED_MENU}
                <h3 class="title valign_middle">{aboveleft.NAME}</h3>
            </div>
            <div class="news_content">
                {aboveleft.GET_CONTENT}
            </div>
        </div>
		<br />
		# END aboveleft #
		</div>
		
		<div style="float:right;width:50%;border:1px solid;">
		# START aboveright #
		<div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {aboveright.GET_FEED_MENU}
                <h3 class="title valign_middle">{aboveright.NAME}</h3>
            </div>
            <div class="news_content">
                {aboveright.GET_CONTENT}
            </div>
        </div>
		<br />
		# END aboveright #
		</div>
		
		<div style="clear:both;width:100%;border:1px solid;">
		# START center #
        <div class="news_container">
            <div class="news_top">
                <h3 class="title valign_middle">Le projet PHPBoost</h3>
            </div>
            <div class="news_content">
            <img src="http://www.phpboost.com/upload/boostor_mini2.jpg" class="img_right" alt="" />
                PHPBoost est un CMS (<em>Content Managing System</em> ou <em>syst�me de gestion de contenu</em>) <strong>fran�ais</strong>. Ce logiciel permet � n'importe qui de cr�er son site de fa�on tr�s simple, tout est assist�. Con�u pour satisfaire les d�butants, il devrait aussi ravir les utilisateurs exp�riment�s qui souhaiteraient pousser son fonctionnement ou encore d�velopper leurs propres modules.<br />
                PHPBoost est un <strong><a href="http://fr.wikipedia.org/wiki/Logiciel_libre">logiciel libre</a></strong> distribu� sous la <a href="http://fr.wikipedia.org/wiki/Licence_publique_g%C3%A9n%C3%A9rale_GNU">licence GPL</a>.<br />

                <br />
                Comme son nom l'indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des op�rations dans la base de donn�es. Il s'installe sur un serveur Web et se param�tre � distance.<br />
                <br />
                Comme pour une grande majorit� de logiciels libres, la communaut� de PHPBoost lui permet d'avoir � la fois une fiabilit� importante car beaucoup d'utilisateurs ont test� chaque version et les ont ainsi approuv�es. Il b�n�ficie aussi par ailleurs d'une �volution rapide car nous essayons d'�tre le plus possible � l'�coute des commentaires et des propositions de chacun. M�me si tout le monde ne participe pas � son d�veloppement, beaucoup de gens nous ont aid�s, rien qu'en nous donnant des id�es, nous sugg�rant des modifications, des fonctionnalit�s suppl�mentaires.<br />
                <br />
                Si vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br />
                <ul class="bb_ul">
                    <li class="bb_li">Projet Open Source sous licence GNU/GPL</li>
                    <li class="bb_li">Code XHTML 1.0 strict et s�mantique</li>
                    <li class="bb_li">Multilangue</li>
                    <li class="bb_li">Facilement personnalisable gr�ce aux th�mes et templates</li>
                    <li class="bb_li">Gestion fine des droits et des groupes multiples pour chaque utilisateur</li>
                    <li class="bb_li">Url rewriting</li>
                    <li class="bb_li">Installation et mise � jour automatis�es des modules et du noyau</li>
                    <li class="bb_li">Aide au d�veloppement de nouveaux modules gr�ce au framework de PHPBoost</li>
                </ul>
            </div>
        </div>
		<br />
		# END center #
		</div>
		
		<div style="float:left;width:50%;border:1px solid;">
		# START belowleft #
		<div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {belowleft.GET_FEED_MENU}
                <h3 class="title valign_middle">{belowleft.NAME}</h3>
            </div>
            <div class="news_content">
                {belowleft.GET_CONTENT}
            </div>
        </div>
		<br />
		# END belowleft #
		</div>

		<div style="float:right;width:50%;border:1px solid;">
		# START belowright #
		<div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {belowright.GET_FEED_MENU}
                <h3 class="title valign_middle">{belowright.NAME}</h3>
            </div>
            <div class="news_content">
                {belowright.GET_CONTENT}
            </div>
        </div>
		<br />
		# END belowright #
		</div>
		
		<div style="clear:both;width:100%;border:1px solid;">
		# START bottom #
		<div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {bottom.GET_FEED_MENU}
                <h3 class="title valign_middle">{bottom.NAME}</h3>
            </div>
            <div class="news_content">
                {bottom.GET_CONTENT}
            </div>
        </div>
		<br />
		# END bottom #
		</div>
		