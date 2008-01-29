-- MySQL dump 10.10
--
-- Host: localhost    Database: sprinkles
-- ------------------------------------------------------
-- Server version	5.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_sessions`
--

DROP TABLE IF EXISTS `admin_sessions`;
CREATE TABLE `admin_sessions` (
  `session_id` bigint(20) NOT NULL auto_increment,
  `creation_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `username` varchar(255) default NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Table structure for table `http_cache`
--

DROP TABLE IF EXISTS `http_cache`;
CREATE TABLE `http_cache` (
  `url` varchar(1024) default NULL,
  `headers` blob,
  `content` blob,
  `fetched_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `oauth_tokens`
--

DROP TABLE IF EXISTS `oauth_tokens`;
CREATE TABLE `oauth_tokens` (
  `token` varchar(32) default NULL,
  `token_secret` varchar(128) default NULL,
  `username` varchar(255) default NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Table structure for table `site_links`
--

DROP TABLE IF EXISTS `site_links`;
CREATE TABLE `site_links` (
  `url` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_links`
--

LOCK TABLES `site_links` WRITE;
/*!40000 ALTER TABLE `site_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `sprinkles_root_url` varchar(1024) default NULL,
  `background_color` varchar(255) default NULL,
  `contact_email` varchar(255) default NULL,
  `contact_phone` varchar(255) default NULL,
  `contact_address` text,
  `map_url` text,
  `faq_type` varchar(255) default NULL,
  `logo_data` blob,
  `configured` char(1) default NULL,
  `company_id` varchar(255) default NULL,
  `oauth_consumer_key` varchar(12) default NULL,
  `oauth_consumer_secret` varchar(32) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('#86fff6','foo@bar.com','555-1212','1 Fabulous Court','',NULL,'‰PNG\r\n\Z\n\0\0\0\rIHDR\0\0\0Ï\0\0\0,\0\0\0M3{$\0\0\nEiCCPICC Profile\0\0xœSgTSé=÷ŞôBKˆ€”KoR RB‹€Ti¢’\0¡„@ìˆ¨Àˆ¢\"‚qÀÑ±\"Š…A±÷y(ãà(6TŞŞ\Z}³æ½7oö¯½ö9gïœ}>\0F`°Dš…ªdJòˆ\0<6.\'w\nT €@˜-‰ô\0àûñğìˆ\0øàÍm@\0\0nØ†á8üPÊä\n\0$\0¦‹ÄÙB\0¤\02r2\02\n\0ì¤t™\0%\0\0[€j\0;e’O\0vÒ$÷\0¶(S*@£\0@&Ê‰\0Ğ\0X—£‹\0°`\0(Ê‘ˆs°›\0`’¡Ì”\0`ï\0€)d\0`¢Sö\0ÀGEğ\03(Œ”¯xÒW\\!ÎS\0\0ğ²d‹å’”Tn!´Ä\\]¹x 87C¬PØ„	„é¹çeeÊÒÅ\0“3\0€FvD€Î÷ã9;¸:;Û8Ú:|µ¨ÿ\Zü‹ˆ‹ÿ—?¯Â\0„ÓõEû³¼¬\Z\0î\0¶ñ‹–´ e\r€Öı/šÉ\0ÕB€æ«_ÍÃáûñğT…Bæfg—››k+m…©_õùŸ	_õ³åûñğß×ƒûŠ“Êàƒ³2²”r<[&Šq›?ñß.üówL‹\'‹åb©PŒGKÄ¹i\nÎË’Š$\nI–—Hÿ“‰³ì˜¼k\0`Õ~öB[P»Êì—. °è€%ì\0äwß‚©Ñ\01ƒ“w\00ù›ÿh\0 Ù’\0€…•òœÉ\0€4P6hƒ>ƒØ€#¸€;xÌ†Pˆ‚8X\0BH…LC.,…UP%°¶Bì†Z¨‡F8-pÎÂ¸×à<€^€ç0\no`A2ÂDXˆ6b€˜\"Öˆ#ÂEf!~H0Ä!‰H\n\"E”ÈRd5R‚”#UÈ^¤ù9œE.!=È=¤F~C> Ê@Ù¨j†Ú¡\\Ô\rB£Ğùh\nºÍGÑ\rh%ZƒB›Ñ³èôÚ‹>GÇ0Àè3Äl0.ÆÃB±x,“cË±b¬«Á\Z±6¬»õb#Ø{‰À\"à‚;!0— $,\",\'”ªÍ„Â\rBa”ğ™È$ê­‰nD>1–˜BÌ%+ˆuÄcÄóÄ[Äâ‰Ä!™“\\H¤8R\Zi	©”´“ÔD:Cê!õ“ÆÈd²6ÙšìA%È\nry;ùù4ù:y€üB§P)ş”xŠ”R@© ¤œ¢\\§RÆ©jTSª5”*¢.¦–Qk©mÔ«Ôê8MfNó EÑÒh«h•´FÚyÚCÚ+:nDw¥‡Ó%ô•ôJúaúEzı=CƒaÅà1JÆÆ~ÆÆ=Æ+&“iÆôbÆ3Ì\rÌzæ9æcæ;–Š­\n_E¤²B¥Z¥YåºÊUªª©ª·êÕ|Õ\nÕ£ªWUGÔ¨jfj<5ÚrµjµãjwÔÆÔYêê¡ê™ê¥êÕ/©i5Ì4ü4D\Z…\Zû4Îiô³0–1‹Ç²V³jYçYlÛœÍg§±KØß±»Ù£š\Zš34£5ó4«5Ojör0‡ÏÉà”qpns>LÑ›â=E<eı”Æ)×§¼Õšªå¥%Ö*ÖjÒº¥õA×öÓN×Ş¤İ¢ıH‡ c¥®“«³Kç¼ÎÈTöT÷©Â©ÅSL½¯‹êZéFè.Ñİ§Û¥;¦§¯ \'ÓÛ®wNoDŸ£ï¥Ÿ¦¿Eÿ”ş°Ë`–Ä`‹Áiƒg¸&îgà•x>j¨kh¨4ÜkØm8ndn4×¨À¨Éè‘1Í˜kœl¼Å¸İxÔÄÀ$Äd©IƒÉ}Sª)×4Õt›i§é[3s³³µf-fCæZæ|ó|óó‡LO‹E57-I–\\ËtË–×¬P+\'«T«j««Ö¨µ³µÄz§uÏ4â4×iÒi5ÓîØ0l¼mrl\Zlúl9¶Á¶¶-¶/ìLìâí6ÙuÚ}¶w²Ï°¯µà á0Û¡À¡Íá7G+G¡cµãÍéÌéşÓWLoşr†õñŒ]3î:±œBœÖ:µ;}rvq–;7:»˜¸$ºìp¹ÃesÃ¸¥Ü‹®DW×®\'\\ß»9»)Ü¸ıênãî~Ğ}h¦ùLñÌÚ™ıF½½³ğY‰³öÌêõ4ôxÖx>ñ2öyÕy\rz[z§yò~ácï#÷9æó–çÆ[Æ;ã‹ùøûvûiøÍõ«ò{ìoäŸâßà?\Zà°$àL 10(pSà¾_È¯çÎv™½lvG#(2¨*èI°U°<¸-\r™²9äáÓ9Ò9-¡Êİú(Ì<lQØá¤ğ°ğêğ§K#:#Y‘#F¾‰ò‰*‹z0×b®rn{´jtBt}ôÛß˜ò˜ŞX»Øe±Wâtâ$q­ñäøèøºø±y~ó¶ÎHpJ(J¸=ß|~ŞüKtd,8¹Pu¡`áÑDbbLâÁÄ‚PA`,‰Ÿ´#iTÈn>y‰¶ˆ†Åârñ`²GryòPŠGÊæ”áTÏÔŠÔ	OR%y™˜¶;ímzhúşô‰Œ˜Œ¦LJfbæq©†4]Ú‘¥Ÿ•—Õ#³–Éz¹-ÚºhT$¯ËF²çg·*Ø\n™¢Ki¡\\£ìË™•Só.7:÷hz4¯k±Õâõ‹óıó¿]BX\"\\Ò¾Ôpéª¥}Ë¼—í],OZŞ¾ÂxEáŠ•+¬¢­J_õS}AyÁëÕ1«Û\nõ\nWö¯	XÓP¤R$/º³Ö}íîu„u’uİë§¯ß¾şs±¨ør‰}IEÉÇRaéåo¾©üfbCò†î2ç²]I¥ooòÜt \\½<¿¼sÈææ-ø–â-¯·.Üz©bFÅîm´mÊm½•Á•­ÛM¶oÜş±*µêVµOuÓİëw¼İ)Úy}—×®Æİz»KvØ#ÙswoÀŞæ\Z³šŠ}¤}9ûÖF×v~Ëı¶¾N§®¤îÓ~éşŞ:ê]êëê,k@”\rÃ‡]ûÎ÷»ÖF›Æ½Mœ¦’ÃpXyøÙ÷‰ßß>t¤ı(÷hã¦?ì8Æ:VÜŒ4/nmImémkí9>ûx{›{Û±mÜÂğDõIÍ“e§h§\nOMœÎ?=vFvfälÊÙşö…íÎÅ»ÙŞÑ}>èüÅşÎuzw¾èqñÄ%·KÇ/s/·\\q¾ÒÜåÔuì\'§Ÿu;w7_u¹ÚzÍõZ[ÏÌS×=¯Ÿ½á{ãÂMşÍ+·æÜê¹=÷öİ;	wzïŠîİË¸÷ò~Îıñ+?R{TñX÷qÍÏ–?7õ:÷ìóíëzùäA¿°ÿù?²ÿñq ğ)óiÅ Á`ıãĞ‰aÿákÏæ=x.{>>Rô‹ú/;^X¼øáW¯_»FcG^Ê_NüVúJûÕş×3^·…=~“ùfümñ;íwŞsßw~ˆù08û‘ü±ò“å§¶ÏAŸNdNLü˜óü\0Ÿ`û\0\0\0	pHYs\0\0\0\0\0šœ\0\0IDATxœí\\kÇu=·ª»ç½Kî._¢(Q‹zX4eÇŠDÛ’Ÿp,À@b’OI€äwøŸò%_‚ØI€Ø@GŠ%ëa[QDÙ¦DŠ\"%jùÚ]îÎÎL?ê|èî™î™Ù¥,KŒ\Z w¦êvuÕ­[ç>z„$>Å§øHàM~¤À¦\"$<Á‚À—~T)bb]5Á’™ŞlC‘\0uÁâôf»¿é‡(í“‚qm[Wœ°¥À5Áı>Vèäm‚ÎE°‚† G\0¸ÏÇ!;ŞìR‚‹1|A Ø&|à\0ûöª#ï%¸”ÀxÀ¶Âœ°ÿÿÆ•q]ñr¡âÅG,,ğëŞÿPÃûıâbŒ×ú ! ) ñÚ\0W’R³ó1Î„0@-oÖW¼ÚÇ†îå¦bœ	!D\0 e0P¼ÚÇú¤}² CŞ¦À‹}n+Z @HÄÄSMiŞ^›¯¯x¾OğÃ…6@BÁ—â	\0t/ô²f O´¾Ô˜gt/öéWJœnÊíM[F\Z´æ°á\01á%‘ˆkî8Èßn(bÂJ ¿”0À¶âFş¼×B8jFÂ>°éĞÓ ½Ÿ &M±u»›·#9œóÒİ6ï´~ü±í È¬xéóFù§‘UÍ\0$Ä€hÏsÓ)Òb¢§X¸½1ø?¡¬Ğ6 ûü6CÃL}ŞXáåGZ¸)Íö`î?LiŸ8Œ´`Bª@\ns‘nÄ$ÛŒ·©X²2É–ÈSëË6{ØH+Õf_læ2{?Ç´82RÖ§HãœÒ>q(îDÑW8‚ùåˆ¾\"¹m[ÛàÁšl¹Ñ#+ÑSl;<H0´mDBôqjû	G„D_³?çBR%m@¬´x·Ê‘4\"R$?·pÃI¹-ñ@M<àÛ.s[_lÊİÁÈÀÄ\n±\"‘ÌLÙ^õq¸Rµ.Js\0	x;né1”x›ËC\0Iá¹Áå˜JìÒU Ù¶8˜àc,Ü[“#¾\\OÁ+µ2Ow`:-Emì«¹‘Î!\'¤»ÛO4Š¼\rš†ÊPÀf‘\'îÒaRàò\0gúGM9ü±Ï|5Mß)‹›–ô¯J×r6œœä=KûÄ¡¤m$´jÚÛMã»yÑbÃág]~{AöAë#İV4îôeÅÛU<³§¸éšƒørÔ»‹n1ñ~ÄÕ„x8˜ÚüšŸQºª{ :[Ú‡ªn!1 b\"Ê%| .#§{oHã5²¯¥Q¥Z ãaÑ”Ö·ÄÛ&Ÿ—@\rpÄÆüÇFJq^ëé×:™M<3Ğ·XôĞ1ØrøyÄ;}~¾if›Ì÷cş²ÇºÁ~‹˜x}ÀsO7Mmf·-åÛLK,ğvˆ·B}¢%û«ôT‰˜ğ§dÇ§©ÁŞÔc†´Åí+®:¬&ÜRø_Ä¬äáÂÜgå ÅŠ[íÖ/„¼#T\002ò¯‡ÿ$ÈXÙ=¿Ğ4Ãã­`ÛˆJffeñ6êÀz‚˜ğo‡|­%G¬9Ğ0x+D z²1Ze—ĞXîˆ›/vY30Àºƒ\05Áõ¿èéÓíQ¯$¦Ø\\“øy—=Å‚EWAÀtëòÛ©tª§x³¯ë\nx‚ıÔKzœ|ùpJÈWâÌ‘š†Á²-™a¢8É,vL¥¥ÿ©”V7X±³ŒzH¼á½˜Ğ´êƒÑè-à\nDÀµ„c´Ç}¹;ØUJOñ?=}\'†ìó°dálî6¥L4å	0pè*~;@LırÛ¤C(¤ãÌL\0ŠTÎæH;%ùsvÀ(Š)şğNˆ‡ë£fË{íì@\0¢W0®uÁ1®%<7öÊìğBÈµSêå]Å;ªg×ÿsSrÀƒ/‰wB¾é×ÌP“\r)‘·‚– nF÷ºâbX=;\"Òy´!÷æG¸2›d¾`Ñ–TÿZŒ+ñTi}¥œlÊ}U„àºÃ¯ûÜ\"\Z‚6`€¢?eáD`–`@¼òR‚ÇêÒ™©q«1_è²G,\Z$¶™ióbÈ.Ô\r8âxÀ”ï|’:”â½»‡\01á‰x‚ˆ(ŒŒÛN‘”paÊİÒl÷ŒÀ•x¤mcXKheüqà«q¦m	ñü&Š¶Áµ$°ÙtøEW¿™§†£Y0s\'ÌÌcÈé+ÛR…Ü_3iûÔ¼Õ,{ãµƒ³ª†‘Í?ÛÒ˜ò`½¤\ZW¼Ô§m“­h¥‚y¹x‚	Ûæã\r90¥´ìİÏo«\'²`@`På?—ÆÎ,p\0=òF‚qm+ùÑ‡¡bb5í‘€MÇ“\r1ùãUÒjéüg»g¢#IåÔÅÑò¶+ih§Wc®%\\ô$)~4Ş‹x%æ!_	\05“1î¹ @ÃÈK]Şá£i²ô |Á–›{[ %òJ—wZß›Š—zê€¶•–€rrekd€¶AWñb_i™ıµ}«1Ÿëj ¨W…,†¨ü&åß•m[oPI©È<$6!7ùæT^¨”0ãªcˆ•3è±#…P÷Hg-UJ‡UÆÛDŠ$u#ám‘Z·”‡ \0|Sˆ…™²[fƒ€t•Wb½§fà+ÕS½#©šr5ÖÔXøU¡¢c³ˆé\"\"Ì™¥\0¾AM`ËÊ‘Vøm9¼ÒÓ¯µMÑÄöÏwi Ì_À±¢“kY)OªdåÈbbí¦‚@Íàá¦ùbÓùê&d¬hAĞ\'c¥ÅJô•Èğ4 Â™á)}Çšˆ-ôR ¯dn¡deßg\rp (º×782õu@f)™–î9ÜAâfÔ\0à|ÈÕ˜)«Zú¼]Eİà¨\'MÄÄM‡ë	=AC*îz‚×ûüBs´ /oëÍ„KL*Æ¶\'&n&<èËñ\\	J¶mÚ\nÆÄgjæÑ¦$»œ!Á~OZeƒD°§p¤o2%ˆÈPagj1ÁH¹F¼l0¤™Í™¡º˜ì:_™‚2)[Ä´’O¤tĞ$D4rãH@…ìYÛ”HM¼•4\0Qaww¤s«ÀÙ¶pÜAÁ¶âDM¬Ë×¼áğj[.’#õÁ…ˆ\'ê’ÓWc¾İ×¶êøŞ#\"\"*ïØ¡QP &òÍ…QJ©¤m°Us‡o-åB\"t”\"u˜ÍgóqÇeŞëfkš¦IùcÛ)&ºŠF!ÌæˆÒšùdæJÏŞ• \"DpĞ¤\"Túk½{¤¦àzÂu‡–d¬¼xÓM‡{œ¬*-^¶ò¥–üä¦n;ë±Óâõ›ŠwB>Ú\0çC’°9:BÅ–cÇÊa_‚‰i!ˆÜYÃ±rör,ŞÆÊ0·N	)ÍN0÷tº§sı¼WUr‡6G\rÆo—ÿ>oD&¾ˆÉÔ:Ë,\r›ù‚	°­hÍÿrTD¬+ŸîØÔénŒ]‡æüÒúÄºãS»hÀå	aŠïQ¤¡€”®™TÉÀz\n(ê¦0E®;âˆó\":¡Ê!qSy²aèÌ—\nßÁ\'Mñ¡TSf*[u™5&Ìiuñ”¢²—N$ˆ˜×Eî´c–t¾ø¼uÉ77¼²£C`J€,ƒ/xºcOå¬bŸÍh\\:ãm¶4à«ö±\\Ú–Ò¢dØRRÛWàBÄsSb\0<‘To ¨åÆI\0+XO@ «Üvl\Z¦ØpúHÃ>5ñD´ªÑ­6d1‹‰Ù‰RM-nÕç³Ø^Úk¢…Å¸I1øÂÂN(ök[ñ\0—WxKı”èX9İ1¦êLÁ‚ÅŠ/íBP®ã‰/ˆ	“¦ËÊ™‰Oè˜ÊÙAÇâ€o\n	ô’¢…²”érŞ9Íaè¦ô\Z	r×Í\"%‰\"™`:„J|®µZ5Ò6™™øoYã*ãj³‚yƒig÷bU¯\"oc~¸O\n-jwÇJÛÊzÂÉğ³uƒ“M3;Æ[DÇÈ‚•«q…‡C añè<o¶Å\nRÉº\'DH¦y¶¹V«çH#~yDŒ !İÄ–&Ğ#–=YÚSV¤m©s>-t5Ò™[eg°ÊF¦§[4=ô*Qeqµ\\„7†„Õ\\“å“´Òş¡|L{‚}®ÅUô2/lŞ=w1‚E‹BVÌ‰õ¡bvµÁ˜´2Éín–Ïİƒ·Kl;6Œ‚®ã}uÁfL£‘Ó‰İc¤m-#vÊ¡i€Ë/„z|÷3Q†d€\né‘âbÄCU+v-æ•X¥rTÄõD1…g_¨Ğ$§Æ’RÚPù¼c.Ø‚‘ôMÇIl$\\õ®y¦¥c%™âûoÄ¼2´\0pÊX€b’ —<íé*A` `Ù—Sùq\"*¨)#ÚkåçHÛ–<éXYKX™+K€_sÏ®È=UGzÁ”ä\0ÿ»ímJ}â@z¥ë¶&l\0Vp¶§—šzlbm~ÓÓõ&BP\0°”Ìi(bŒğeš/lêáåQ{Gš.Í/oé!‡bª!j.?q&.{RŸHLAò’!|#G|Ü_3¾Éºò©\"€KÏ]®#Õò÷ÕÍ•-W™·Àºã?\\ïxÇj¨íT¹ß“â`Ÿ/—\"Ö&Z\n°\ZóG7Ü3K¶“ß;!ş{Ó½¾­Şô\rš\0ÿ²æ¾³„{\n‹óÛ¾şd#Á”S[€Ã~é“JÙcŞU—†É¨ı|à|¨-~¼e—}kC	4-&«\rî¬IË\"Ô\niğ»¾®_oÛe?‹KÏ¶äMõ–\"à¾š|®µƒÚ¸é*bÅ>OÚvüÛ1mãLr¿#J†ìx]^ØšJİ,ÿ±[Á¥³û¬|¶eŸèŒ\nˆN4Ìë]W™\0ğ·îïVõş†iIˆ‘~i ³Â÷¸™ğ¯ÅŸ©ÛeO\\‰x>tV¤ÒItDËbÙ3â‹ÅdÑÊ’\'ïEZiÖ=àjÄÕ+ÇÏÔÇ{zÁ-ì‡•%+ï:­d{¾àZÌ­©\'¥5àXÍ>¹`îÌ¥İU“† bE)Š!~¹­K&mÿPàù0!j}ÅÍ„4Í©‚‚’(š’“ï©*²JÚvoİÌ¹Î°äéí˜ä»ó§ëÉ¥>»â§Fî®š,z²éXéÍø‚®ò•nF™¬daÕÙ÷IxÎô²#Ú\0¦ÌÆ€ørËk˜ÑI:-j=i0jšw:Y€á}\'úéÏôÜùşà€wO¡Fèá–97Po¶´‰I\0gzîíûÁŠ_Ã\0h9V“3}<1\0t?^wGŞ_—Ã¾)»(.…üİ@×¶x’Y}/uõ@Ò´±Ê‡ÜÓçÌÌö,Œ“´Ó‹ö|¨î–‹– .x£§wlº§÷Y\0\r#_YğşéF,ÓU¹h<fWháí¢WB,\Z9UˆVpSŞ›r¦ñDÃü—•P¹û`GŠÀ ¯ü·µäoÃ=ü@Ãt¬ô´\"ª²£´ˆø×µäoøé¶y¢cÎ‡\ZiÅi“†ô.ôÂ\0À“,\Zçîü¢•!»MŸ$Ş\r9Ò¶‰İ¨·ê_ù{ëæ‰×K²;İâåŞØvC\r8Ù6\'ê¦çFoG4—‘â«û¼vaMvìUÄ¢\'ŸoÙ¾ÛË$x‚+/…#ƒÛ±ò…¶ìIš®Ç|woÑÊ“-iö›cWú¾\\šŞ}‡PAÀÊ¨®sHÅH¸X nS‡±Wm«p@¿±Ï^ôLO»ö³¦DŸŒI+À\0Ï,{««Ñz2GŒêÖÑWœ^°µKÏ£œZ’Yy¼>¹hŞìÉjÌ=½¸Åk1ïoŒ>ybÁé¹Ë!÷SJÈ«lfj›k1_İvõİ‰bnÌb\"(øX02r1/}N†ä‘ö=k[Å\0­àÙŞ\réºì§µnåJe˜û=ùÁ¿e²M¶ûË»—Û§Zæ™¥ñ}Õ´PÊT2¡RM#ß[ñš{ÒrÔ\"êÏ®x{°p$êeçñûì©–éå?±›DßqÛ¶„•Ü…r$ıá„r?:…İëYZ½\ZFşâ ÿä‚‰1ç[àâıñ·ÏÕÌ_òø²íFAÿÙ×ÀÁ\n¾·âj™C¼»õNÏÓöûüIí9VÏäo®®„h[­²`Gkæ¯ù‡}ô4ãv»Ú\'€\'8:aÌæ/ú‡™KšÈİåáÁŸì÷îØˆÌ£¸›	7ŞˆÙW~}Ñ»·°\'ÚVÙkÌ“~!‘û§¹K;Áşğ‡?¬üÂy¨iær¤	I1sæ+Òß:øşaÂm{r²m\"åjÄP)JöM V8òş†ùóƒşMûpË.Z¹i×Íê•(br%ï.û_İg+›µ­ôœ¨?zŸ0­Àã·ö{\'šÕiº\'\'ÛÖ—#†JLCq0}åS‹Ş©N…À¶\'§Ú–ÄåˆıéóP”ÖS|iÁ~~Bš\0wÕÍ¬Å\\sŒór„”U–Ì«C‚‚ã5ûİeïî²ùmZÙvx³¯V$ı]˜ØHĞ°ò§+%¼{ÈË‘âW]÷fÏ½;`˜ùÄ;RBúøŞŠïLö÷A¨/oéÙ¾[‹³‰Ò´¸§.µ¼GÚ¥Uè:¾´é~Ós„©U(õ²‚c5ùlË~±cg³¢˜øéZòÂ¦‹ówø‘¯í³ßØ¿óÏZ¯†|yË½=p×ã”kWÌ	Oäô‚ıÎ’7{u®D|eËí»ëQjEª¥ù\"wÌ3K¾?ı¹”ø]_ßØvï†º­ñ’‚Dr,0Ÿm™#S2!±âÇ7âW¶@š§_ñåÏ–ıÏìõGqwÖ¶á(¯Fz5BWÙwÕ5zÃ–°Ï7\'\Z²ËĞS¾7àMÇCHz@ÃJÇÊ,MO”%Ä{¡®\'ÜNĞ#\rĞ0Ò²Xñå`ºŒ‹¡^°§l¹».Gçáí¡òıˆ	û}óµŒ­Éñ1Ê–.ÕğBåû!7\\µ´¦‘»ê²û,êFÂÕˆ]eß!V }¥Ï¢fĞ2²ß“ş¦\0wúz)ÔˆØïÉM3í]Ìİ`·Úö)>Å­ã¶ş™×Oñ1Ã§Úö)>:üÍ\0NåUh‘\0\0\0\0IEND®B`‚',null,'4', NULL, NULL);
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions` (
  `session_id` bigint(20) NOT NULL auto_increment,
  `creation_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `username` varchar(255) default NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(255) default NULL,
  `password` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dump completed on 2008-01-15  0:51:57

