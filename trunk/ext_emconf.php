<?php

########################################################################
# Extension Manager/Repository config file for ext: "sr_feuser_register"
#
# Auto generated 21-06-2006 14:17
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Front End User Registration',
	'description' => 'A front end user self-registration variant of Kasper Sk�rh�j\'s Front End User Admin extension.',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms,static_info_tables',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => 0,
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => 'fe_users',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Stanislas Rolland',
	'author_email' => 'stanislas.rolland@fructifor.ca',
	'author_company' => 'Fructifor Inc.',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '2.5.0',
	'_md5_values_when_last_written' => 'a:31:{s:9:"ChangeLog";s:4:"adfb";s:21:"ext_conf_template.txt";s:4:"3a54";s:12:"ext_icon.gif";s:4:"ad8f";s:17:"ext_localconf.php";s:4:"9c0a";s:14:"ext_tables.php";s:4:"bdeb";s:14:"ext_tables.sql";s:4:"dc58";s:13:"locallang.xml";s:4:"f26f";s:16:"locallang_db.xml";s:4:"cd07";s:7:"tca.php";s:4:"aad1";s:37:"pi1/class.tx_srfeuserregister_pi1.php";s:4:"eedf";s:48:"pi1/class.tx_srfeuserregister_pi1_adodb_time.php";s:4:"cdb8";s:50:"pi1/class.tx_srfeuserregister_pi1_urlvalidator.php";s:4:"b2a7";s:18:"pi1/ext_tables.php";s:4:"b0ed";s:23:"pi1/flexform_ds_pi1.xml";s:4:"bc95";s:19:"pi1/icon_delete.gif";s:4:"f914";s:21:"pi1/internal_link.gif";s:4:"12b9";s:32:"pi1/internal_link_new_window.gif";s:4:"402a";s:17:"pi1/locallang.xml";s:4:"c019";s:36:"pi1/tx_srfeuserregister_htmlmail.css";s:4:"0570";s:42:"pi1/tx_srfeuserregister_htmlmail_xhtml.css";s:4:"f65b";s:41:"pi1/tx_srfeuserregister_pi1_css_tmpl.html";s:4:"fb70";s:38:"pi1/tx_srfeuserregister_pi1_sample.txt";s:4:"297e";s:37:"pi1/tx_srfeuserregister_pi1_tmpl.tmpl";s:4:"95d3";s:14:"doc/manual.sxw";s:4:"8a5e";s:48:"hooks/class.tx_srfeuserregister_hooksHandler.php";s:4:"3d7e";s:28:"scripts/jsfunc.updateform.js";s:4:"e838";s:30:"static/old_style/constants.txt";s:4:"4bde";s:30:"static/old_style/editorcfg.txt";s:4:"cfad";s:26:"static/old_style/setup.txt";s:4:"1c8b";s:31:"static/css_styled/constants.txt";s:4:"d0ba";s:27:"static/css_styled/setup.txt";s:4:"862a";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'static_info_tables' => '2.0.0-',
			'php' => '4.1.0-',
			'typo3' => '4.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'newloginbox' => '',
			'kb_md5fepw' => '',
			'fh_library' => '',
		),
	),
	'suggests' => array(
	),
);

?>