DROP TABLE IF EXISTS phpboost_articles, phpboost_articles_cats, phpboost_articles_models;

CREATE TABLE `phpboost_articles` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `id_models` int(11) NOT NULL default '1',
  `title` varchar(100) NOT NULL default '',
  `description` text,
  `contents` mediumtext NOT NULL,
  `sources` text,
  `icon` varchar(255) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `views` mediumint(9) NOT NULL default '0',
  `users_note` text,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  `auth` text,
  `extend_field` text,
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

CREATE TABLE `phpboost_articles_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `id_models` int(11) NOT NULL default '1',
  `c_order` int(11) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `description` text,
  `nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
  `nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `auth` text,
  `options` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

CREATE TABLE `phpboost_articles_models` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `description` text,
  `pagination_tab` tinyint(1) NOT NULL default '0',
  `extend_field` text,
  `options` text,
  `tpl_articles` varchar(100) NOT NULL default 'articles.tpl',
  `tpl_cats` varchar(100) NOT NULL default 'articles_cat.tpl',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


INSERT INTO phpboost_articles (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES ('9', '0', '1', 'dz', '', 'dz', 'a:0:{}', '', '1253876941', '0', '0', '0', '1', '14', '', '0', '0', '0', '0', '', ''), ('10', '12', '1', 'test article en attente', '', 'dz', 'a:0:{}', '', '1253876941', '0', '0', '0', '1', '2', '', '0', '0', '0', '0', '', ''), ('13', '10', '1', 'Cr�er DVD/Blu-ray : 3 logiciels PC en test', '', 'xz', 'a:0:{}', '02450882.jpg', '1253877241', '1', '0', '0', '1', '3', '', '0', '0', '0', '0', '', ''), ('15', '10', '1', 'Les tuners TNT HD USB au banc d''essai', '', 'd', 'a:0:{}', '02449506.jpg', '1253877301', '1', '0', '0', '1', '2', '', '0', '0', '0', '0', '', ''), ('16', '10', '1', 'Top des logiciels pour Facebook, Twitter et MySpace', '', 'd', 'a:0:{}', '02458698.jpg', '1253877361', '1', '0', '0', '1', '5', '', '0', '0', '0', '0', '', ''), ('29', '0', '1', 'dz', '', 'gvzsdc[page]  dzdz[/page]cd', 'a:0:{}', '', '1253889481', '0', '0', '0', '1', '191', '', '0', '0', '3', '0', '', ''), ('30', '0', '1', 'test autorisation sp�', '', 'dzqssz<br />\r\n<br />\r\nszsaxqsa', 'a:2:{i:0;a:2:{s:7:"sources";s:6:"clubic";s:3:"url";s:14:"www.clubic.com";}i:1;a:2:{s:7:"sources";s:1:"z";s:3:"url";s:4:"dacq";}}', '02446110.jpg', '1253968321', '1', '0', '0', '1', '14', '', '0', '0', '0', '0', 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}', 'a:0:{}'), ('31', '10', '1', 'Comparatif de six encyclop�dies en ligne', '', 'dz', 'a:0:{}', '02462842.jpg', '1254022981', '1', '0', '0', '1', '30', '', '0', '0', '0', '0', '', ''), ('32', '18', '2', 'AMD Radeon HD 5850 : DirectX 11 � 250 euros', 'Test de la description d''un article, cela permet un affichage tpl beaucoup plus interressant pour l''utilisateur.', '[page]pre[/page]<br />\r\nVotre site boost� par PHPBoost 3 est bien install�. Afin de vous aider � prendre votre site en main, l''accueil de chaque module contient un message pour vous guider pour vos premiers pas. Voici �galement quelques recommandations suppl�mentaires que nous vous proposons de lire avec attention : <br />\r\n<br />\r\n<br />\r\nN''oubliez pas de supprimer le r�pertoire ''install''<br />\r\n<br />\r\nSupprimez le r�pertoire /install � la racine de votre site pour des raisons de s�curit� afin que personne ne puisse recommencer l''installation.<br />\r\n<br />\r\n<br />\r\nAdministrez votre site<br />\r\n<br />\r\nAcc�dez au panneau d''administration de votre site afin de le param�trer comme vous le souhaitez! Pour cela : <br />\r\n<br />\r\n<br />\r\n&#8226;Mettez votre site en maintenance en attendant que vous le configuriez � votre guise. <br />\r\n&#8226;Rendez vous � la Configuration g�n�rale du site. <br />\r\n&#8226;Configurez les modules disponibles et donnez leur les droits d''acc�s (si vous n''avez pas install� le pack complet, tous les modules sont disponibles sur le site de phpboost.com dans la section t�l�chargement). <br />\r\n&#8226;Choisissez le langage de formatage du contenu par d�faut du site. <br />\r\n&#8226;Configurez l''inscription des membres. <br />\r\n&#8226;Choisissez le th�me par d�faut de votre site pour changer l''apparence de votre site (vous pouvez en obtenir d''autres sur le site de phpboost.com). <br />\r\n&#8226;Modifiez l''�dito de votre site. <br />\r\n&#8226;Avant de donner l''acc�s de votre site � vos visiteurs, prenez un peu de temps pour y mettre du contenu. <br />\r\n&#8226;Enfin d�sactivez la maintenance de votre site afin qu''il soit visible par vos visiteurs.<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\nQue faire si vous rencontrez un probl�me ?<br />\r\n<br />\r\nN''h�sitez pas � consulter la documentation de PHPBoost ou de poser vos question sur le forum d''entraide.<br />\r\n<br />\r\n[page]deux[/page]<br />\r\ndzcs<br />\r\n[page]troisi[/page]<br />\r\nz<br />\r\n&amp;efgf<br />\r\n[page]quatr[/page]<br />\r\ndzcszzzzzzzzz<br />\r\n[page]cinq[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]six[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]sept[/page]<br />\r\ndzcs<br />\r\n[page]huit[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]neuf[/page]<br />\r\ndzcs<br />\r\n[page]dix[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]onze[/page]<br />\r\ndzcs<br />\r\n[page]douze[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]treize[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]quatorze[/page]<br />\r\ndzcs<br />\r\n[page]quinze[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]seize[/page]<br />\r\ndzcs<br />\r\n[page]dix[/page]<br />\r\n<br />\r\n&amp;efgf', 'a:0:{}', '02464020.jpg', '1254024241', '1', '0', '0', '1', '622', '1', '1', '3', '0', '0', '', 'a:1:{s:4:"TYPE";a:2:{s:4:"name";s:4:"TYPE";s:8:"contents";s:0:"";}}');
INSERT INTO phpboost_articles (id, idcat, id_models, title, description, contents, sources, icon, timestamp, visible, start, end, user_id, views, users_note, nbrnote, note, nbr_com, lock_com, auth, extend_field) VALUES ('33', '0', '1', 'TEST onglet  sans carrousel', '', 'daq<br />\r\n[page]deczsde[/page]<br />\r\nczscs<br />\r\n[page]deczsfzde[/page]<br />\r\ndzdz<br />\r\n[page] [/page]dz<br />\r\n<img src="/images/smileys/top.gif" alt=":top" class="smiley" /> z<br />\r\n<br />\r\n[page] [/page]s<br />\r\n<img src="/images/smileys/top.gif" alt=":top" class="smiley" /> z<br />\r\n[style=notice]dzdz[/styles]', 'a:0:{}', 'articles.png', '1254022501', '1', '0', '0', '1', '331', '', '0', '0', '1', '0', '', ''), ('20', '10', '1', 'zczczcz', '', 'czcz', '', '', '1253877961', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0', '', ''), ('25', '10', '1', 'vzsvzs', '', 'czcz', '', '', '1253878501', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0', '', ''), ('22', '10', '1', 'cz', '', 'cz', '', '', '1253878141', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0', '', ''), ('26', '10', '1', 'Comment partager ses fichiers entre plusieurs PC ?', '', '[page]test[/page]<br />\r\ndz<br />\r\n[page]teszt[/page]<br />\r\nfzcfzf<br />\r\n<br />\r\nfzfzfz<br />\r\nfzfc<br />\r\nz<br />\r\nc<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\n[page]tedzszt[/page]<br />\r\nfzcfzf<br />\r\n<br />\r\nfzfzfz<br />\r\nfzfc<br />\r\nz<br />\r\nc<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz', 'a:0:{}', '02454836.jpg', '1253878561', '1', '0', '0', '1', '83', '', '0', '0', '0', '0', '', ''), ('27', '10', '1', 'wwwwwwwwww', '', 'wwwwwwwwwwwww', '', '', '1253878561', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0', '', ''), ('34', '0', '1', 'TEST source', 'description', 'contenu', 'a:4:{i:0;a:2:{s:7:"sources";s:6:"google";s:3:"url";s:9:"google.fr";}i:1;a:2:{s:7:"sources";s:6:"clubic";s:3:"url";s:21:"http://www.clubic.com";}i:2;a:2:{s:7:"sources";s:5:"aucun";s:3:"url";s:0:"";}i:3;a:2:{s:7:"sources";s:8:"phpboost";s:3:"url";s:16:"www.phpboost.com";}}', '', '1254278101', '1', '0', '0', '1', '23', '', '0', '0', '0', '0', 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}', ''), ('35', '0', '1', 'TEST onglet avec carouselle', '', '[page]un[/page]<br />\r\ncontenu un<br />\r\n<br />\r\ntest<br />\r\n[page]deux[/page]<br />\r\ncontenu deux<br />\r\ntest<br />\r\n[page]trois[/page]<br />\r\ncontenu trois<br />\r\ntest<br />\r\n[page]quatre[/page]<br />\r\ncontenu quatre<br />\r\ntest<br />\r\n[page]cinq[/page]<br />\r\ncontenu cinq<br />\r\ntest<br />\r\n[page]six[/page]<br />\r\ncontenu six<br />\r\ntest<br />\r\n[page]sept[/page]<br />\r\ncontenu sept<br />\r\ntest<br />\r\n[page]huit[/page]<br />\r\ncontenu huit<br />\r\n<br />\r\ntest<br />\r\n[page]neuf[/page]<br />\r\ncontenu neuf<br />\r\n<br />\r\ntest<br />\r\n[page]dix[/page]<br />\r\ncontenu dix<br />\r\ntest<br />\r\n[page]onze[/page]<br />\r\ncontenu onze<br />\r\n<br />\r\ntest<br />\r\n[page]douze[/page]<br />\r\ncontenu douze<br />\r\n<br />\r\ntest<br />\r\n[page]treize[/page]<br />\r\ncontenu treize<br />\r\ntest<br />\r\n[page]quatorze[/page]<br />\r\ncontenu quatorze<br />\r\n<br />\r\ntest<br />\r\n[page]quinze[/page]<br />\r\ncontenu quinze<br />\r\n<br />\r\ntest<br />\r\n[page]seize[/page]<br />\r\ncontenu seize<br />\r\n<br />\r\ntest', 'a:0:{}', '', '1255185601', '1', '0', '0', '1', '34', '', '0', '0', '0', '0', '', '');
INSERT INTO phpboost_articles_cats (`id`, `id_parent`, `id_models`, `c_order`, `name`,`description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`, `options`) VALUES 
('10', '0', '1', '1', 'TEST  tpl perso', 'dz', '5', '4', 'articles.png', '1', '', 'a:6:{s:4:"note";b:1;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}'), 
('11', '10', '1', '1', 'trucs et astuces', 'gvsdx', '0', '0', 'http://www.phpboost.com/templates/tornade/images/rss.png', '1', '', 'a:6:{s:4:"note";b:1;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:0;}'), 
('12', '0', '1', '2', 'ved', 'z', '0', '0', 'http://www.phpboost.com/templates/tornade/images/rss.png', '1', '', 'a:6:{s:4:"note";b:1;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}'), 
('13', '0', '1', '3', 'TEST CHAMPS SUP', 'test', '1', '0', '../articles/articles.png', '1', '', 'a:6:{s:4:"note";b:1;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}'), 
('18', '0', '2', '4', 'Test mod�les', '', '0', '0', '../articles/articles.png', '1', '', '');
INSERT INTO phpboost_articles_models (`id`, `name`, `description`, `pagination_tab`, `extend_field`, `options`, `tpl_articles`, `tpl_cats`) VALUES ('1', 'Mod�le par defaut', 'Mod�le par defaut', '1', 'a:0:{}', 'a:6:{s:4:"note";b:1;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}', 'articles.tpl', 'articles_cat.tpl'), ('2', 'Mod�le personnalis�', '<ul class="bb_ul">\r\n<li class="bb_li">Utilisation de deux templates personnalis�s :<br />\r\n<ul class="bb_ul">\r\n<li class="bb_li">un templates red�finissant la mise en page de la liste des articles dans les cat�gories.\r\n</li><li class="bb_li">un templates red�finissant la mise en page des articles en ajoutant un tableau regroupant toutes les informations de l''article.<br />\r\n</li></ul>\r\n</li><li class="bb_li">Suppression des notes pour les articles\r\n</li><li class="bb_li">Activation de la pagination par onglet\r\n</li><li class="bb_li">Ajout d''un champs TYPE<br />\r\n</li></ul>', '1', 'a:1:{i:0;a:2:{s:4:"name";s:4:"TYPE";s:4:"type";s:0:"";}}', 'a:6:{s:4:"note";b:0;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}', 'articles_info_in_tab.tpl', 'articles_cat_info_list.tpl');
