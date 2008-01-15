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
  `background_color` varchar(255) default NULL,
  `contact_email` varchar(255) default NULL,
  `contact_phone` varchar(255) default NULL,
  `contact_address` text,
  `map_url` text,
  `faq_type` varchar(255) default NULL,
  `logo_data` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('#f0b0ff','ez@ra.com','585-101-8888','1010 O\'Reilly Ave \\ foobar','http://nowheres.com',NULL,'‰PNG\r\n\Z\n\0\0\0\rIHDR\0\0\0Ï\0\0\0,\0\0\0M3{$\0\0\nEiCCPICC Profile\0\0xœSgTSé=÷ÞôBKˆ€”KoR RB‹€Ti¢’\0¡„@ìˆ¨Àˆ¢\"‚qÀÑ±\"Š…A±÷y(ãà(6TÞÞ\Z}³æ½7oö¯½ö9gïœ}>\0F`°Dš…ªdJòˆ\0<6.\'w\nT €@˜-‰ô\0àûñðìˆ\0øàÍm@\0\0nØ†á8üPÊä\n\0$\0¦‹ÄÙB\0¤\02r2\02\n\0ì¤t™\0%\0\0[€j\0;e’O\0vÒ$÷\0¶(S*@£\0@&Ê‰\0Ð\0X—£‹\0°`\0(Ê‘ˆs°›\0`’¡Ì”\0`ï\0€)d\0`¢Sö\0ÀGEð\03(Œ”¯xÒW\\!ÎS\0\0ð²d‹å’”Tn!´Ä\\]¹x 87C¬PØ„	„é¹çeeÊÒÅ\0“3\0€FvD€Î÷ã9;¸:;Û8Ú:|µ¨ÿ\Zü‹ˆ‹ÿ—?¯Â\0„ÓõEû³¼¬\Z\0î\0¶ñ‹–´ e\r€Öý/šÉ\0ÕB€æ«_ÍÃáûñðT…Bæfg—››k+m…©_õùŸ	_õ³åûñðß×ƒûŠ“Êàƒ³2²”r<[&Šq›?ñß.üówL‹\'‹åb©PŒGKÄ¹i\nÎË’Š$\nI–—Hÿ“‰³ì˜¼k\0`Õ~öB[P»Êì—. °è€%ì\0äwß‚©Ñ\01ƒ“w\00ù›ÿh\0 Ù’\0€…•òœÉ\0€4P6hƒ>ƒØ€#¸€;xÌ†Pˆ‚8X\0BH…LC.,…UP%°¶Bì†Z¨‡F8-pÎÂ¸×à<€^€ç0\no`A2ÂDXˆ6b€˜\"Öˆ#ÂEf!~H0Ä!‰H\n\"E”ÈRd5R‚”#UÈ^¤ù9ŽœE.!=È=¤F~C> Ê@Ù¨j†Ú¡\\Ô\rB£Ðùh\nºÍGÑ\rh%ZƒB›Ñ³èôÚ‹>GÇ0Àè3Äl0.ÆÃB±x,“cË±b¬«Á\Z±6¬»õb#Ø{‰À\"à‚;!0— $,\",\'”ªÍ„Â\rBa”ð™È$ê­‰nD>1–˜BÌ%+ˆuÄcÄóÄ[Äâ‰Ä!™“\\H¤8R\Zi	©”´“ÔD:Cê!õ“ÆÈd²6ÙšìA%È\nry;ùù4ù:y€üŽB§P)þ”xŠ”R@© ¤œ¢\\§RÆ©jTSª5”*¢.¦–Qk©mÔ«Ôê8MfNó EÑÒh«h•´FÚyÚCÚ+:nDw¥‡Ó%ô•ôJúaúEzý=CƒaÅà1JÆÆ~ÆÆ=Æ+&“iÆôbÆ3Ì\rÌzæ9æcæ;–Š­\n_E¤²B¥Z¥YåºÊUªª©ª·êÕ|Õ\nÕ£ªWUGÔ¨jfj<5ÚrµjµãjwÔÆÔYêê¡ê™ê¥êÕ/©i5Ì4ü4D\Z…\Zû4Îiô³0–1‹Ç²V³jYçYlÛœÍg§±KØß±»Ù£š\Zš34£5ó4«5Ojör0Ž‡ÏÉà”qŽpns>LÑ›â=E<eý”Æ)×§¼Õšªå¥%Ö*ÖjÒº¥õA×öÓN×Þ¤Ý¢ýH‡ c¥®“«³Kç¼ÎÈTöT÷©Â©ÅSL½¯‹êZéFè.ÑÝ§Û¥;¦§¯ \'ÓÛ®wNoDŸ£ï¥Ÿ¦¿Eÿ”þ°Ë`–Ä`‹Áiƒg¸&îgà•x>j¨kh¨4ÜkØm8ndn4×¨À¨Éè‘1Í˜kœl¼Å¸ÝxÔÄÀ$Äd©IƒÉ}Sª)×4Õt›i§é[3s³³µf-fCæZæ|ó|óó‡LO‹E57-I–\\ËtË–×¬P+\'«T«j««Ö¨µ³µÄz§uÏ4â4×iÒi5ÓîØ0l¼mrl\Zlúl9¶Á¶¶-¶/ìLìâí6ÙuÚ}¶w²Ï°¯µà á0Û¡À¡Íá7G+G¡cµãÍéÌéþÓWLoþr†õñŒ]3î:±œBœÖ:µ;}rvq–;7:»˜¸$ºìp¹ÃesÃ¸¥Ü‹®DW×®\'\\ß»9»)ÜŽ¸ýênãžî~Ð}h¦ùLñÌÚ™ýF½½³ðY‰³öÌêõ4ôxÖx>ñ2öyÕy\rz[z§yò~ácï#÷9æó–çÆ[Æ;ã‹ùøûvûiøÍõ«ò{ìoäŸâßà?\Zà°$àL 10(pSà¾_È¯çÎv™½lvG#(2¨*èI°U°<¸-\r™²9äáÓ9Ò9-¡ÊÝú(Ì<lQØá¤ð°ðêð§K#:#Y‘#F¾‰ò‰*‹z0×b®rn{´jtBt}ôÛß˜ò˜ÞX»Øe±Wâtâ$q­ñäøèøºø±y~ó¶ÎHpJ(J¸=ß|~ÞüKtd,8¹Pu¡`áÑDbbLâÁÄ‚PA`,‰Ÿ´#iTÈn>y‰¶ˆ†Åârñ`²GryòPŠGÊæ”áTÏÔŠÔ	OR%y™˜¶;ímzhúþô‰Œ˜Œ¦LJfbæq©†4]Ú‘¥Ÿ•—Õ#³–Éz¹-ÚºhT$¯ËF²çg·*Ø\n™¢Ki¡\\£ìË™•Só.7:÷hžzž4¯k±Õâõ‹óýó¿]BX\"\\Ò¾Ôpéª¥}Ë¼—í]Ž,OZÞ¾ÂxEáŠ•+¬¢­J_õS}AyÁëÕ1«Û\nõ\nWö¯	XÓP¤R$/º³Ö}íîu„u’uÝë§¯ß¾þs±¨ør‰}IEÉÇRaéåo¾©üfbCò†î2ç²]I¥ooòÜt \\½<¿¼sÈææ-ø–â-¯·.Üz©bFÅîm´mÊm½•Á•­ÛM¶oÜþ±*µêVµOuÓÝëw¼Ý)Úy}—×®ÆÝz»KvØ#ÙswoÀÞæ\Z³šŠ}¤}9ûžÖF×v~Ëý¶¾N§®¤îÓ~éþÞ:ê]êëê,k@”\rÃ‡]ûÎ÷»ÖF›Æ½Mœ¦’ÃpXyøÙ÷‰ßß>t¤ý(÷hã¦?ì8Æ:VÜŒ4/nmImémkí9>ûx{›{Û±mÜÂðDõIÍ“e§h§\nOMœÎ?=vFvfälÊÙþö…íÎÅž»ÙÞÑ}>èüÅþÎuzwž¾èqñÄ%·KÇ/s/·\\q¾ÒÜåÔuì\'§ŸŽu;w7_u¹ÚzÍõZ[ÏÌžS×=¯Ÿ½á{ãÂMþÍ+·æÜê¹=÷öÝ;	wzïŠîÝË¸÷ò~Îýñ+?R{TñX÷qÍÏ–?7õ:÷žìóíëzùäA¿°ÿù?²ÿñq ð)óiÅ Á`ýãÐ‰aÿákÏæ=x.{>>Rô‹ú/;^X¼øáW¯_»FcG^Ê_NüVúJûÕþ×3^·…=~“ùfümñ;íwÞsßw~ˆù08žû‘ü±ò“å§¶ÏAŸNdNLü˜óü\0Ÿ`û\0\0\0	pHYs\0\0\0\0\0šœ\0\0IDATxœí\\kÇu=·ª»ç½Kî._¢(Q‹zX4eÇŠDÛ’Ÿp,À@b’OI€äwøŸò%_‚ØI€Ø@GŠ%ëa[QDÙ¦DŠ\"%jùÚ]îÎÎL?êž|èî™î™žÙ¥,KŒ\Z w¦êvuÕ­[ç>z„$>Å§øHàM~¤À¦\"$<Á‚À—~T)bb]5Á’™ÞlC‘\0uÁâôf»¿é‡(í“‚qm[Wœ°¥À5Áý>ŽVèäm‚ÎE°‚† G\0¸ÏÇ!;ÞìR‚‹1|A Ø&|à\0ûöª#ï%¸”ÀxÀ¶Âœ°ÿÿÆ•q]ñr¡âÅG,,ðëÞÿPÃûýâbŒ×ú ! ) ñÚ\0W’R³ó1Î„0@-oÖW¼ÚÇ†îå¦bœ	!D\0 e0P¼ÚÇúž¤}² CÞ¦À‹}n+Z @HÄÄSMiÞ^›¯¯x¾OðÃ…6@BÁ—â	\0t/ô²f O´¾Ô˜gt/öéWJœnÊíM[F\Z´æ°á\01á%‘ˆkî8Èßn(bÂJ ¿”0À¶âFþ¼×B8jFÂ>°éÐÓ ½Ÿ ž&M±u»›·#9œóÒÝ6ï´~ü±í È¬xéóFù§‘UÍ\0$Ä€hÏsÓ)Òb¢§X¸½1ø?¡¬Ð6 ûü6CÃL}ÞXáåGZ¸)Íö`î?LiŸ8Œ´`Bª@\ns‘nÄ$ÛŒ·©X²2É–ÈSëË6{ØH+Õf_læ2{?Ç´82RÖ§HãœÒ>q(îDÑW8‚ùåˆ¾\"¹m[ÛàÁšl¹Ñ#+ÑSl;<H0´mDBôqjû	G„D_³?çBR%m@¬´x·Ê‘4\"R$?·pÃI¹-ñ@M<àÛ.s[_lÊÝÁÈÀÄ\n±\"‘ÌLÙ^õq¸Rµ.Js\0	x;né1”x›ËC\0Iá¹Áå˜JìÒU Ù¶8˜àc,Ü[“#¾\\OÁ+µ2Ow`:-Emì«¹‘Î!\'¤»ÛO4Š¼\rš†ÊPÀf‘\'îÒaRàò\0gúGM9ü±Ï|5ŽMß)‹›–ô¯J×r6œœä=KûÄ¡¤m$´jÚÛMã»yÑbÃág]~{AöAë#ÝV4îôeÅÛU<³§¸éšƒŽørÔ»‹n1ñ~ÄÕ„x8˜ÚüšŸQºªŽ{ :[Ú‡ªn!1 b\"Ê%| .#§{oHã5²¯¥Q¥Z ãaÑ”Ö·ÄÛ&Ÿ—@\rpÄÆüÇFJq^ëé×:™M<3Ð·XôÐ1ØrøyÄ;}~¾if›Ì÷cþ²ÇºÁ~‹˜x}ÀsO7Mmf·-åÛLK,ðvˆ·B}¢%û«ôT‰˜ð§dÇ§©ÁÞÔc†´Åí+®:¬&ÜRø_Ä¬äáÂŽÜgå ÅŠ[íÖ/„¼#T\002ò¯‡ÿ$ÈXÙ=¿Ð4Ãã­`ÛˆJffeñ6êÀz‚˜ðo‡|­%G¬9Ð0x+D z²1Ze—ÐXîˆ›/vY30Àºƒ\05Áõ¿èéÓíQ¯$¦Ø\\“øy—=Å‚EWAÀtžëòÛ©tª§x³¯ë\nx‚ýÔKzœ|ùpJÈWâÌ‘š†Á²-™a¢8É,vL¥¥ÿ©”V7X±³ŒzH¼á½˜Ð´êƒÑè-à\nDÀµ„c´Ç}¹;ØUJOñ?=}\'†ìó°dálî6¥L4å	0pè*~;@LýrÛ¤C(¤ŽãÌL\0ŠTÎæŽH;%ùsvÀ(Š)þðNˆ‡ë£fË{íì@\0¢W0®uÁ1®%<7öÊìðBÈµSêå]Å;ªg×ÿsSrÀƒ/‰wB¾é×ÌP“\r)‘·‚– nF÷ºâbX=;\"Òy´!÷æG¸2›d¾`Ñ–TÿZŒ+ñTi}¥œlÊ}U„àºÃ¯ûÜ\"\Z‚6`€¢?eáD`–`@¼òR‚ÇêÒ™©q«1_è²G,\Z$¶™ióbÈ.Ô\r8âxÀ”ï|’:”â½»‡\01á‰x‚ˆ(ŒŒÛN‘”paÊžÝÒl÷ŒÀ•x¤mcXKheüqà«q¦m	ñü&Š¶Áµ$°ÙtøEW¿™§†£Y0s\'ÌÌcÈé+žÛR…Ü_3iûÔ¼Õ,{ãµƒ³ª†‘Í?ÛÒ˜ò`½¤\ZW¼Ô§m“­h¥‚y¹x‚	žÛæã\r90¥´ìÝÏo«\'²`@`På?—ÆÎ,p\0=òF‚qm+ùÑ‡¡bb5í‘€MÇ“\r1ùãUÒjéüg»g¢#IåÔÅÑò¶+ih§Wc®%\\ô$)~4Þ‹x%æ!_	\05“1î¹ @ÃÈK]Þá£i²ô |Á–›{[ %òJ—wZß›Š—zê€¶•–€rrekd€¶AWñb_i™ýµ}«1Ÿëj ¨W…,†¨ü&åß•m[oPI©È<$6!7ùæT^¨”0ãªcˆ•3è±#…P÷Hg-UJ‡UÆÛDŠ$u#ám‘Z·”‡ \0|Sˆ…™²[fƒ€t•Wb½§fà+ÕS½#©šr5ÖÔXøU¡¢c³ˆé\"\"Ì™¥\0¾AM`ËÊ‘Vøm9¼ÒÓ¯µMÑÄöÏwi Ì_À±¢“kY)OªdåÈbbí¦‚@Íàá¦ùbÓùê&d¬hAžÐ\'c¥ÅJô•Èð4 Â™á)}Çšˆ-ôR ¯dn¡deßg\rp (º×782õu@f)™–î9ÜAâfÔ\0à|ÈÕ˜)«Zú¼]EÝà¨\'MÄÄM‡ë	=AC*îz‚×ûüBs´ /oëÍ„KžL*ÆŽ¶\'&n&<èËñ\\	J¶mÚ\nÆÄgjæÑ¦$»œ!Á~OZeƒD°§p¤o2%ˆÈPagj1ÁH¹F¼l0¤™Í™¡º˜ì:_™‚2)[Ä´’O¤tÐ$D4rãH@…ìYÛ”HM¼•4\0Qaww¤s«ÀÙ¶pÜAÁ¶âDM¬Ë×¼áðj[.’Ž#õÁ…ˆ\'ê’ÓWc¾Ý×¶êøÞ#\"\"*ïØ¡QP &òÍ…QJ©¤m°Us‡o-åB\"t”\"u˜ÍgóqÇeÞëfžkš¦IùcÛ)&ºŠF!ÌæˆžÒšùdæJÏÞ• \"DpÐ¤\"Túk½{¤ž¦ŽàzÂu‡–d¬¼xÓM‡{œ¬*-^¶ò¥–üä¦n;ë±Óâõ›ŠwB>Ú\0çC’°9:BÅ–cÇÊa_‚‰i!ˆÜYÃ±rör,ÞÆÊ0·N	)ÍN0÷tº§sý¼WUŽr‡6G\rÆo—ÿ>oD&¾ˆÉÔ:Ë,\r›ù‚	°­hÍÿrTD¬+ŸîØÔénŒ]‡æüÒúÄºãS»hÀå	aŠïQ¤¡€”®™TÉÀz\n(ê¦0E®;âˆó\":¡Ê!qSy²ažèÌ—\nßÁ\'Mñ¡TSf*[u™5&Ìiuñ”¢²—N$ˆ˜×EŽîž´c–t¾ø¼uÉ77¼²£C`J€,ƒ/xºcOå¬bŸÍh\\:ãm¶4à«ö±\\Ú–Ò¢dØRRÛWàBÄsSb\0<‘To ¨åÆI\0+XO@ «Üvl\Z¦ØpúHÃ>5ñDž´ªÑ­6d1‹‰Ù‰RM-nÕç³Ø^Úk¢…Å¸I1øÂÂN(ök[ñ\0—WxKý”èX9Ý1¦êLÁ‚ÅŠ/íBP®ã‰/ˆ	“¦ËÊ™‰Ožè˜ÊÙAÇâ€o\n	ô’¢…²”érÞ9Íaè¦ô\Z	r×Í\"%‰\"™`:„J|®µZ5Ò6™™øoYã*ãj³‚yƒig÷bU¯\"oc~¸O\n-jwÇJÛÊzÂÉð³uƒ“M3;Æ[DÇÈ‚•«q…‡C añè<o¶Å\nRÉº\'DH¦y¶¹V«çH#~yDŒ !ÝÄ–&Ð#–=YÚSV¤m©s>-t5Ò™[eg°ÊF¦§[4=ô*Qeqµ\\„7†„Õ\\“å“´Òþ¡|L{‚}®ÅUô2/lÞ=w1‚E‹BVÌ‰õ¡bvµÁ˜´ž2Éín–ÏÝƒ·Kl;6Œ‚®ã}uÁfL£‘ŽÓ‰Ýc¤m-#vÊ¡i€Ë/„z|÷3Q†d€\né‘âbÄCU+v-æ•X¥rTÄõD1…g_¨Ð$§Æ’RÚPù¼c.Ø‚‘ôMÇIl$\\õ®y¦¥c%™âûoÄ¼2´\0pÊX€b’ —<íé*A` `Ù—Sùq\"*¨)#ÚkåçHÛ–<éXYKX™+K€_sÏ®È=UGzÁ”ä\0ÿ»ímJ}â@z¥ë¶&l\0Vp¶§—šzlbm~ÓÓõ&BP\0°”Ìi(bŒðeš/lêáåQ{Gš.Í/oé!‡bª!j.?q&.{RŸHLAò’!|#G|Ü_3¾Éºò©\"€KÏ]®#Õò÷ÕÍ•-W™·Àºã?\\ïxÇj¨íT¹ß“â`Ÿ/—\"Ö&Z\n°\ZóG7Ü3K¶“ß;!þ{Ó½¾­Þô\rš\0ÿ²æ¾³„{\n‹óÛ¾þd#Á”S[€Ã~é“JÙcÞU—†É¨ý|à|¨-~¼e—}kC	4-&«\rî¬IË\"Ô\nið»¾®_oÛe?‹KÏ¶äMõ–\"à¾š|®µƒÚ¸é*bÅ>OÚvüÛ1mãLr¿#J†ìx]^ØšJÝ,ÿ±[ÁŽ¥³û¬|¶eŸèŒ\nˆN4Ìë]W™\0ð·îïVõþ†iIˆ‘~i ³Â÷¸™ð¯ÅŸ©ÛeO\\‰x>tV¤ÒItDËbÙ3â‹ÅdÑÊ’\'ïEZiÖ=àjÄÕ+ÇÏÔÇ{zÁ-ì‡Ž•%+ï:­d{¾àZÌ­©\'¥5àXÍ>¹`îÌ¥ÝU“† bE)Š!~¹­K&mÿPàùž0!j}ÅÍ„4Í©‚‚’(š’“ï©*²JÚvoÝÌ¹Î°äéí˜ä»ó§ëÉ¥>»â§Fî®š,z²éXéÍø‚®ò•nF™¬daÕÙ÷IxÎô²#Ú\0¦ÌÆ€ørËk˜ÑI:-j=i0jšw:Y€á}\'úéÏôÜùþà€wO¡Fèá–97Po¶´‰I\0gzîíûÁŠ_Ã\0h9V“3}<1\0t?^wGÞ_—Ã¾)»(.…üÝ@×¶x’Y}/uõŽ@Ò´±Ê‡ÜÓçÌÌö,Œ“´Ó‹ö|¨î–‹– .x£§wlº§÷Y\0\r#_YðþéF,ÓU¹h<fWháí¢WB,\Z9UˆVpSÞ›r¦ñDÃü—•P¹û`GŠÀ ¯ü·µäoÃ=ü@Ãt¬ô´\"ª²£´ˆø×µäoøé¶y¢cÎ‡\ZiÅi“†ô.ôÂ\0žÀ“,\Zçîü¢•!»MŸ$Þ\r9Ò¶‰Ý¨·ê_ù{ëæ‰Ž×K²;ÝâåÞØvC\r8Ù6\'ê¦çFoG4—‘â«û¼vaMvìUÄ¢\'ŸoÙ¾ÛË$x‚+/…#ƒÛ±ò…¶ìIš®Ç|woÑÊ“-iö›cWú¾\\šÞ}‡PAÀÊ¨®sHÅH¸X nS‡±Wm«p@¿±Ï^ôLO»ö³¦DŸŒI+À\0Ï,{««Ñz2GŒêÖÑWœ^°µKÏ£œZ’Yy¼>¹hÞìÉjÌ=½¸Åk1ïoŒ>ybÁžé¹Ë!÷SJÈ«lfžj›k1_ÝvõÝ‰bnÌb\"(øX02r1/}N†ä‘ö=k[Å\0­àÙÞ\réºì§µnåJe˜û=ùÁ¿e²M¶ûË»—Û§Zæ™¥ñ}Õ´PÊT2¡RM#ß[ñš{ÒrÔ\"êÏ®x{°p$êeçñûì©–éå?±›DßqÛ¶„•Ü…r$ýá„r?:…ÝëYZ½\ZFþâ ÿä‚‰1ç[àâýñ·ÏÕÌ_òø²íFAÿÙ×ÀÁ\n¾·âj™žC¼»õNÏŽÓöûüIí9VÏäo®®„h[­²`Gkæ¯ù‡}ô4ãv»Ú\'€\'8:aÌæ/ú‡™KšžÈÝåáÁŸì÷žîØˆÌ£¸›	7ÞˆÙW~}Ñ»·°\'ÚVÙkÌ“~!‘û§¹K;Áþð‡?¬üÂy¨iær¤	I1sæ+Òß:øþaÂm{r²m\"åjÄP)JöM V8òþ†ùóƒþMûpË.Z¹i×Íê•(br%ï.û_Ýg+›µ­ôœ¨?zŸ0­Àã·ö{\'šÕiºŽ\'\'ÛÖ—#†JLCq0}åS‹Þ©N…À¶\'§Ú–ÄåˆýéóP”ÖS|iÁ~~Bš\0wÕÍ¬Å\\sŒór„”U–Ì«C‚‚ã5ûÝeïî²ùmZÙvx³¯V$ý]˜ØHÐ°ò§+%¼{ÈŽË‘âW]÷fÏ½;`˜ùÄ;RBúøÞŠïLö÷A¨/oéÙ¾[‹³‰Ò´¸§.µ¼GÚ¥Uè:¾´é~Ós„©U(õ²‚c5ùlË~±cg³¢˜øéZòÂ¦‹ówø‘¯í³ßØ¿óÏZ¯†|yË½=p×ã”kWÌ	Oäô‚ýÎ’7{u®D|eËí»ëQjEª¥ù\"wÌ3K¾?ý¹”ø]_ßØvï†º­ñ’‚Dr,0Ÿm™#S2!±âÇ7âW¶@š§_ñåÏ–ýÏìõGqwÖ¶á(¯Fz5BWÙwÕ5zÃ–°Ï7\'\Z²ËÐS¾7àMÇžCHz@ÃJÇÊ,MO”%Ä{¡®\'ÜNÐ#\rÐ0Ò²XñåŽ`ŽºŒ‹¡^°§l¹».Gçáí¡òýˆ	û}óµŒ­Éñ1Ê–.ÕðBåû!7\\µ´¦‘»ê²û,êFÂÕˆ]eß!V }¥Ï¢fÐ2²ß“þ¦\0wúz)ÔˆØïÉM3í]ÌÝ`·Úö)>Å­ã¶þ™×Oñ1Ã§Úö)>:üÍ\0NåUh‘\0\0\0\0IEND®B`‚');
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

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('ezra','knockknock');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-01-15  0:51:57
