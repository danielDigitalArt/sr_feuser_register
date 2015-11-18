<?php
namespace SJBR\SrFeuserRegister\Hooks;

/*
 *  Copyright notice
 *
 *  (c) 2008-2011 Franz Holzinger <franz@ttproducts.de>
 *  (c) 2012-2015 Stanislas Rolland <typo3(arobas)sjbr.ca>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Hooks for the usergroup field
 */
class UsergroupHooks
{
	/**
	 * Modifies the form fields configuration depending on the $cmdKey
	 *
	 * @param array $conf: the configuration array
	 * @param string $cmdKey: the command key
	 * @return void
	 */
	public function modifyConf(&$conf, $cmdKey) {
		// Add usergroup to the list of fields and required fields if the user is allowed to select user groups
		// Except when only updating password
		if ($cmdKey !== 'password') {
			if ($conf[$cmdKey . '.']['allowUserGroupSelection']) {
				$conf[$cmdKey . '.']['fields'] = implode(',', array_unique(GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['fields'] . ',usergroup', true)));
				$conf[$cmdKey . '.']['required'] = implode(',', array_unique(GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['required'] . ',usergroup', true)));
			} else {
				// Remove usergroup from the list of fields and required fields if the user is not allowed to select user groups
				$conf[$cmdKey . '.']['fields'] = implode(',', array_diff(GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['fields'], true), array('usergroup')));
				$conf[$cmdKey . '.']['required'] = implode(',', array_diff(GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['required'], true), array('usergroup')));
			}
		}
		// If inviting and administrative review is enabled, save original reserved user groups
		if ($cmdKey === 'invite' && $conf['enableAdminReview']) {
			$this->savedReservedValues = $this->getReservedValues();
		}
	}

	/**
	 * Gets allowed values for user groups
	 *
	 * @param array $conf: the configuration array
	 * @param string $cmdKey: the command key
	 * @return void
	 */
	public function getAllowedValues(
		$conf,
		$cmdKey,
		&$allowedUserGroupArray,
		&$allowedSubgroupArray,
		&$deniedUserGroupArray
	) {
		$allowedUserGroupArray = GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['allowedUserGroups'], true);
		$allowedSubgroupArray = GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['allowedSubgroups'], true);
		$deniedUserGroupArray = GeneralUtility::trimExplode(',', $conf[$cmdKey . '.']['deniedUserGroups'], true);
	}

	/**
	 * Gets the array of user groups reserved for control of the registration process
	 *
	 * @param array $conf: the plugin configuration
	 * @return array the reserved user groups
	 */
	public function getReservedValues($conf)
	{
		$reservedValues = array_merge(
			GeneralUtility::trimExplode(',', $conf['create.']['overrideValues.']['usergroup'], true),
			GeneralUtility::trimExplode(',', $conf['invite.']['overrideValues.']['usergroup'], true),
			GeneralUtility::trimExplode(',', $conf['setfixed.']['APPROVE.']['usergroup'], true),
			GeneralUtility::trimExplode(',', $conf['setfixed.']['ACCEPT.']['usergroup'], true)
		);
		return array_unique($reservedValues);
	}

	/**
	 * Removes reserved user groups from the usergroup field of an array
	 *
	 * @param array $row: array
	 * @return void
	 */
	public function removeReservedValues(&$row){
		if (isset($row['usergroup'])) {
			$reservedValues = $this->getReservedValues();
			if (is_array($row['usergroup'])) {
				$userGroupArray = $row['usergroup'];
				$bUseArray = true;
			} else {
				$userGroupArray = explode(',', $row['usergroup']);
				$bUseArray = false;
			}
			$userGroupArray = array_diff($userGroupArray, $reservedValues);
			if ($bUseArray) {
				$row['usergroup'] = $userGroupArray;
			} else {
				$row['usergroup'] = implode(',', $userGroupArray);
			}
		}
	}

	public function removeInvalidValues(
		$conf,
		$cmdKey,
		&$row
	) {
		if (isset($row['usergroup']) && $conf[$cmdKey . '.']['allowUserGroupSelection']) {

// Todo
		} else {
			$row['usergroup'] = ''; // the setting of the usergropus has not been allowed
		}
	}

	public function getAllowedWhereClause(
		$theTable,
		$pid,
		$conf,
		$cmdKey,
		$bAllow = true
	) {
		$whereClause = '';
		$subgroupWhereClauseArray = array();
		$pidArray = array();
		$tmpArray = GeneralUtility::trimExplode(',', $conf['userGroupsPidList'], true);
		if (count($tmpArray)) {
			foreach ($tmpArray as $value) {
				$valueIsInt = MathUtility::canBeInterpretedAsInteger($value);
				if ($valueIsInt) {
					$pidArray[] = intval($value);
				}
			}
		}
		if (count($pidArray) > 0) {
			$whereClause = ' pid IN (' . implode(',', $pidArray) . ') ';
		} else {
			$whereClause = ' pid=' . intval($pid) . ' ';
		}

		$whereClausePart2 = '';
		$whereClausePart2Array = array();

		$this->getAllowedValues(
			$conf,
			$cmdKey,
			$allowedUserGroupArray,
			$allowedSubgroupArray,
			$deniedUserGroupArray
		);

		if ($allowedUserGroupArray['0'] != 'ALL') {
			$uidArray = $GLOBALS['TYPO3_DB']->fullQuoteArray($allowedUserGroupArray, $theTable);
			$subgroupWhereClauseArray[] = 'uid ' . ($bAllow ? 'IN' : 'NOT IN') . ' (' . implode(',', $uidArray) . ')';
		}

		if (count($allowedSubgroupArray)) {
			$subgroupArray = $GLOBALS['TYPO3_DB']->fullQuoteArray($allowedSubgroupArray, $theTable);
			$subgroupWhereClauseArray[] = 'subgroup ' . ($bAllow ? 'IN' : 'NOT IN') . ' (' . implode(',', $subgroupArray) . ')';
		}

		if (count($subgroupWhereClauseArray)) {
			$subgroupWhereClause .= implode(' ' . ($bAllow ? 'OR' : 'AND') . ' ', $subgroupWhereClauseArray);
			$whereClausePart2Array[] = '( ' . $subgroupWhereClause . ' )';
		}

		if (count($deniedUserGroupArray)) {
			$uidArray = $GLOBALS['TYPO3_DB']->fullQuoteArray($deniedUserGroupArray, $theTable);
			$whereClausePart2Array[] = 'uid ' . ($bAllow ? 'NOT IN' : 'IN') . ' (' . implode(',', $uidArray) . ')';
		}

		if (count($whereClausePart2Array)) {
			$whereClausePart2 = implode(' ' . ($bAllow ? 'AND' : 'OR') . ' ', $whereClausePart2Array);
			$whereClause .= ' AND (' . $whereClausePart2 . ')';
		}

		return $whereClause;
	}

	public function parseOutgoingData(
		$theTable,
		$fieldname,
		$foreignTable,
		$cmdKey,
		$pid,
		$conf,
		$dataArray,
		$origArray,
		&$parsedArray
	) {
		$valuesArray = array();
		if (
			isset($origArray) &&
			is_array($origArray) &&
			isset($origArray[$fieldname]) &&
			is_array($origArray[$fieldname])
		) {
			$valuesArray = $origArray[$fieldname];

			if ($conf[$cmdKey . '.']['keepUnselectableUserGroups']) {
				$whereClause = $this->getAllowedWhereClause($foreignTable, $pid, $conf, $cmdKey, false);
				$rowArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', $foreignTable, $whereClause, '', '', '', 'uid');
				if (!empty($rowArray) && is_array($rowArray)) {
					$keepValues = array_keys($rowArray);
				}
			} else {
				$keepValues = $this->getReservedValues();
			}
			$valuesArray = array_intersect($valuesArray, $keepValues);
		}

		if (
			isset($dataArray) &&
			is_array($dataArray) &&
			isset($dataArray[$fieldname]) &&
			is_array($dataArray[$fieldname])
		) {
			$dataArray[$fieldname] = array_unique(array_merge($dataArray[$fieldname], $valuesArray));
			$parsedArray[$fieldname] = $dataArray[$fieldname];
		}
	}
}