<?php
defined('TYPO3_MODE') or die();
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('filemetadata')) {
    $GLOBALS['TCA']['sys_file_metadata']['columns']['fe_groups']['config']['foreign_table_where'] = ' AND fe_groups.sys_language_uid IN (-1,0) ORDER BY fe_groups.title';
}