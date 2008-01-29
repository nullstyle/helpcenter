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
INSERT INTO `site_settings` VALUES ('#86fff6','foo@bar.com','555-1212','1 Fabulous Court','',NULL,'�PNG\r\n\Z\n\0\0\0\rIHDR\0\0\0�\0\0\0,\0\0\0M3{$\0\0\nEiCCPICC Profile\0\0x��SgTS�=���BK���KoR RB��Ti��\0��@숨���\"�q����\"��A��y(��(6T��\Z}��7o����9g��}>\0F`�D���dJ�\0<6.\'w\nT ��@�-��\0�����\0���m@\0\0n���8�P��\n\0$\0����B\0�\02r2\02\n\0�t�\0%\0\0[�j\0;e�O\0v�$�\0�(S*@�\0@&��\0�\0X���\0�`\0(ʑ�s��\0`��̔\0`�\0��)d\0`�S�\0��GE�\03(���x�W\\!�S\0\0�d�咔Tn!��\\]�x�87C�P؄	����ee���\0�3\0�FvD����9;�:;�8�:|���\Z�������?��\0���E����\Z\0�\0�񋖴�e\r���/��\0�B��_������T�B�fg���k+m��_���	_�������׃�������2��r<[&�q�?��.��wL�\'��b�P�GKĹi\n�˒�$\nI��H�������k\0`�~�B[P���. ��%�\0�w߂��\01��w\00���h\0�ْ\0�����\0�4P6h�>�؀#��;x�̆P��8X\0BH�L�C.,�UP%��B�Z��F8-p�����<�^��0\no`A2�DX�6b��\"ֈ#�Ef!~H0��!�H\n\"E��Rd5R��#U�^��9��E.!=�=�F~C>��@٨j�ڡ\\�\rB���h\n��G�\rh%Z�B�ѳ��ڋ>G�0��3�l0.��B�x,�c˱b���\Z�6����b#�{��\"��;!�0� $,\",\'��̈́�\rBa���$���nD>1��B�%+�u�c���[����!��\\H��8R\Zi	�����D:C�!����d�6ٚ�A%�\nry;��4�:y���B�P)��x��R@�����\\�RƩjTS�5�*�.��Qk�mԫ��8M�fN�E��h�h��F�y�C�+:�nDw���%���J�a�Ez�=C�a��1J��~��=�+&�i��b�3�\r�z�9�c�;���\n_E��B�Z�Y��U�������|�\nգ�WUGԨjfj<5��r�j��jw���Y������/�i�5�4�4D\Z�\Z�4�i��0�1���V�jY�Ylۜ�g��K�߱�٣�\Z�34�5�4�5Oj�r0������q�pns>Lћ�=E<e���)ק�՚��%�*�jҺ��A���N�ޤݢ�H��c�����K���T�T��©�S�L����Z�F�.�ݧۥ;����\'�ۮwNoD��寧��E�����`���`��i�g�&�g��x>j�kh�4�k�m8ndn4ר����1͘k�l�Ÿ�x���$�d�I��}S�)�4�t�i��[3s���f-fC�Z�|�|��LO�E57-I�\\�t˝�׬P+\'�T�j��֨����z�u�4�4�i�i5���0l�mrl\Zl�l9����-�/�L���6�u�}�w�ϰ����0ۡ����7G+G�c��������WLo��r���]3�:��B��:�;}rvq�;7:���$��p��esø�܋�DW��\'\\߻9�)܎���n��~�}h��L��ڙ�F�����Y������4�x�x>�2�y�y\rz[z�y�~�c�#�9����[�;����v�i�����{�o����?\Z��$�L 10(pS��_ȯ��v��lvG#(2�*�I�U�<�-\r��9���9�9-����(�<lQ؏�����K#:#Y�#F���*�z0�b�rn{�jtBt}��ߘ��X��e�W�t�$q���������y~��HpJ(J�=�|~��Ktd,8�Pu�`��DbbL��ď�PA�`,���#iT�n>y������r�`�Gry�P�G���T�Ԋ�	OR%y���;�mzh��􉌘��LJfb�q��4]ڑ�����#���z�-ںhT$��F��g�*�\n��Ki�\\��˙�S��.7:�h�z�4�k��������]BX\"\\Ҿ�p骥}˼��]�,OZ޾�xEኁ�+���J_�S�}Ay���1��\n�\nW��	X�P�R$/���}��u�u�u�맯߾�s���r�}IE��Ra��o���fbC��2�]I�oo��t�\\�<��s���-���-��.�z�bF��m�m�m������M�o���*��V�Ou���w��)�y}�׮��z�Kv�#�swo���\Z���}�}9���F�v~����N�����~���:�]���,k@�\rÇ]�����F�ƽM����pXy������>t��(�h��?�8�:V܌4/nmIm�m�k�9>�x{�{۱m���D�I͓e�h�\nOM��?=vFvf�l�������Ş����}>�����uzw���q��%�K�/s/�\\q�����u�\'���u;w7_u��z��Z[�̞S�=����{��M��+����=���;	wz���˸��~���+?R{T�X�q�ϖ?7�:������z��A����?���q��)�iŠ�`���Љa��k��=x.{>>R��/;^X���W�_�FcG^�_N�V�J����3^����=~��f�m�;�w�s�w~��08������姶�A�NdNL����\0�`�\0\0\0	pHYs\0\0\0\0\0��\0\0IDATx��\\k��u=����K�._�(Q�zX4eǊDے�p,�@b�OI��w���%_��I��@G�%�a[QD٦D�\"%j��]���L?�|��ٝ�,K�\Z w��vuխ[�>z�$>ŧ�H�M~���\"$<�����~T)bb]5�����lC�\0u���f���(퓂qm[W�����5��>�V��m��E����G\0���!;��R��1|A �&|��\0���#�%���x��������ƕq]�r���G,,��ލ�P����b����!�) ��\0W�R��1΄0@-o�W��ǆ��b�	!D\0�e0P������}� Cަ��}n+Z @H��SMi�^���x�O�Å6@B���	\0t/��f�O��Ԙ�gt/��WJ�n��M[F\Z���\01�%���k�8��n(b�J ��0���F���B8jF�>��Н� �� �&M�u���#9����6�~���Ȭx��F���U�\0$Āh�sӐ�)�b��X���1�?���6 ��6C�L}�X��GZ�)��`�?Li�8���`B�@\ns�n�$ی��X�2ɖ�S��6{�H+�f_l�2{?Ǵ82R֧H��>q(�D�W8��刾\"�m[����l��#+�Sl;<H0�mDB�qj�	G�D_�?�BR%m@��x�ʑ4\"R$?�p�I�-�@M<����.s[_l������\n�\"��L�^�q�R�.Js\0	x;n�1�x��C\0I���J��U ٶ8��c,�[�#�\\O��+�2Ow`:-Em�쫹��!\'����O4��\r���P�f�\'��aR��\0g�GM9���|5�M�)�����J�r6���=K�ġ�m$�j��M���y�b��g]~{A�A�#�V4��e��U<���隃��r���n1�~�Մx8�����Q���{ :[ڇ�n!1 b\"�%|�.#�{oH�5���Q�Z��aєַ��&��@\rp����FJq^���:�M<3зX��1�r�y�;}~�if���c��Ǻ�~��x}�sO7Mmf�-��LK,�v��B}�%���T���dǧ����c����+�:�&�R�_�������g�Ŋ�[��/��#T\002��$�X�=��4��`ۈJffe��6��z���o�|��%G�9�0x+D z�1Ze��X/vY30���\05�������Q�$��\\��y�=łEWA�t�����t��x���\nx����Kz�|�pJ�W�̑�����-�a�8�,vL�����V7X���zH�὘������-�\nD���c��}�;�UJO�?=}\'���d�l�6�L4�	0p�*~;@L�rۤC(�����L\0�T��H;%��sv��(�)��N���f�{��@\0�W0�u�1�%<�7����BȵS��]�;�g���sSr��/�wB����P�\r��)�����nF���bX=;\"�y�!��G�2�d�`іT�Z�+�Ti}���l�}U��ï��\"\Z�6`���?e�D`��`@��R���ҙ�q�1_�G,\Z$��i�b�.�\r8�x���|�:�⽻�\01�x��(���N��paʞ��l�����x�mcXKhe�q��q�m	��&����$��t�EW������Y0s\'��c��+��R��_3i�ԼՍ,{㵃�����?�Ҙ�`��\ZW�ԧm��h���y�x�	����\r90���ݐ�o�\'�`@`P�?���,p\0=�F�qm+�ч�bb5푀MǓ\r1��U�j���g�g�#I�����+ih�Wc�%\\�$)~4ދx%�!_�	\05�1� @��K]��i���|���{[�%�J�wZߛ��zꀶ���rrekd��AW�b�_i���}�1��j �W�,���&�ߕm[oPI��<$6!7��T^��0�c��3�#��P�Hg-UJ�U��D�$u#�m�Z��� \0|�S����[f��t�Wb��f�+�S�#��r5��X�U���c���\"\"̙�\0�AM`�ʑV�m9��ӯ�M����wi ��_����kY)O�d�ȁbb�@����b���&d�hA��\'c���J�����4 �)}ǚ�-�R��dn�de�g\rp (��782�u@f)���9�A�f�\0�|�՘)�Z��]E��\'M��M��	=AC*�z����Bs� /o�̈́K�L*Ǝ�\'&n&<���\\	J�m�\n��gj�Ѧ$��!�~OZe�D��p�o2%��Pagj1�H�F�l0��͙����:_��2)[Ĵ�O�t�$D4r�H@��Y۔HM��4\0Qaww�s��ِ�p�A���DM��׼��j�[.��#�����\'��Wc��׶����#\"\"*�ءQP &�ͅQJ��m�Us��o-�B\"t�\"u��g�q�e��f�k��I�c�)&��F!�戞���d�J��� \"Dp��\"T�k�{�����z�u��d��x�M�{��*-^����n;������wB>�\0�C��9:BŖc��a_��i!��Yñr�r,���0�N	)�N0�t��s��WU�r�6G\r�o���>oD&����:�,\r���	��h��rTD�+�����n��]�����ĺ�S�h��	a��Q�������T��z\n(�0E�;��\":��!qSy�a��̗\n��\'M�TSf*[u��5&�iu�����N$���E���c�t���u�77���C`J�,�/x�cO�b��h\\:�m�4����\\ږҢd�RR�W�B�sSb�\0<�To����I\0+XO@���vl\Z��p�H�>5��D���ѭ6d1���ىRM-n���^�k��ŸI1���N(�k[�\0�WxK���X9�1��L��Ŋ/�BP��/�	���ʙ�O����A��o\n	�������r�9�a��\Z	r��\"%��\"�`:�J|��Z5�6���oY�*�j��y�ig�bU�\"oc~�O\n-jw�J��z���u��M3;�[D�Ȃ��q��C�a��<o��\nRɺ\'DH�y��V��H#~yD� !�Ė&�#�=Y�SV�m�s>-t5ҙ[eg��F��[4=�*Qeq�\\�7���\\�哴���|L{�}��U�2/l�=w1�E�BV̉��bv�����2��n��݃�Kl;6����}u��fL���Ӊ�c�m-#vʡi��/�z|�3Q�d��\n��b�CU+v-�X�rT��D1�g_���$�ƒR�P��c.؂��M�Il$\\���y��c%���oļ2��\0p�X�b� �<��*A`�`ٗS�q\"*�)#�k��Hۖ<�XYKX�+K�_sϮȁ=UGz���\0���mJ}�@z��&l\0Vp����zlbm~���&BP\0����i(b��e�/l���Q{G�.�/o�!�b�!j.?q&.{R�HL�A�!|#G|�_3����\"�Kύ]��#����͕-W�����?\\��x�j��T�ߓ�`�/�\"�&Z\n�\Z�G7�3K���;!�{ӽ����\r�\0��澳�{\n��۾�d#��S[��~�J�c�U��ɨ�|�|�-~�e�}kC	4-&�\r�I�\"�\ni𻾮_�o�e?�Kϐ��M��\"ྚ|������*b�>O�v��1m�Lr�#J��x]^ؚJ�,��[������|�e��\n�N4��]W�\0�����V���iI��~i �������ş��eO\\�x>tV��ItD�b�3��d�ʒ\'�EZi�=�j��+����{z�-쇎�%+�:�d{��Z���\'�5�X�>�`�̥�U�� bE)�!~��K&m�P����0!j}�̈́�4ͩ���(����*�J�vo�̹�ΰ������ɥ�>��F,z��X������nF��da���Ix���#�\0���ƀ�r�k��I:-j=i0j�w:Y��}\'����������wO�F��97Po���I\0gz�����_�\0h9V�3}�<1\0t?^wG�_�þ)�(.���@���x�Y}/u��@Ҵ��ʇ������,���Ӌ�|����.x��wl���Y\0\r#_Y���F,�U�h<fWh��WB,\Z9U�VpSޛr��D����P��`G��������o�=�@�t���\"������׵�o���y�c·\Zi�i���.��\0���,�\Z�����!�M�$�\r9Ҷ�ݨ��_�{�扎�K�;�����vC\r8�6\'��FoG4�����vaMv�UĢ\'�oپ��$x�+/�#�۱��I���|w��o�ʓ-i��cW��\\�ލ}�PA�ʨ�sH�H�X�nS��Wm�p@���^��LO�����D��I+�\0�,{���z2G����W�^���Kϣ�Z�Yy�>�h���j�=���k1�o�>yb����!�SJȫlf�j�k1_�v�݉bn�b\"(�X02r1/}N���=k[�\0����\r�짵n�Je��=���e�M��������Z晥�}մP�T2�RM#�[��{�r�\"�Ϯx{�p$�e���쩖��?��D�q�۶����r$��r?:���YZ�\ZF���䂉1�[����Ϗ��_����FA�����\n���j��C���Nώ����I�9V��o���h[��`Gk���}�4�v��\'�\'8:a��/���K������������؈̣��	7ވ�W~}ѻ��\'�V�k̓~!����K;����?���y�i��r�	I1s�+��:��a�m{r�m\"�j�P)�J�M V8������M�p�.Z�i���(br%��.�_�g+������?z�0����{\'��i��\'\'���#�JLCq0}�S�ީN���\'�ږ�����P��S|i�~~B�\0w�͝���\\s��r��U�̫C���5��e���mZ�vx��V$�]��Hа�+%�{Ȏˑ�W]�fϽ;`���;RB��ފ�L��A�/o�پ[����Ҵ��.���GڥU�:���~�s��U(���c5�l�~�cg�����Z�¦��w�����ؿ��Z��|y˽=p��kW�	O���Β7{u�D|e˝��QjE���\"�w�3K�?����]_��v��Dr,0�m�#S2!���7�W�@��_��ϖ����Gqwֶ�(�Fz5BW�w�5zÖ��7\'\Z���S�7�MǞCHz@�J���,MO�%�{��\'�N�#\r�0ҲX��`�����^��l��.G������	�}�����1ʖ.��B��!7\\�������,�F�Ո]e�!V }�Ϣf�2�ߓ��\0�w�z)Ԉ���M3�]��`���)>ŭ����O�1ç��)>:��\0N�Uh�\0\0\0\0IEND�B`�',null,'4', NULL, NULL);
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

