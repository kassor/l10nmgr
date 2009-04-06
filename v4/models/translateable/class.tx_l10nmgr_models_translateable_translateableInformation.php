<?php

/***************************************************************
 *  Copyright notice
 *
 *  Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * description
 *
 * class.tx_l10nmgr_models_translateable_translateableInformation.php
 *
 * @author	 Timo Schmidt <schmidt@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version $Id: class.class_name.php $
 * @date 03.04.2009 - 10:00:50
 * @package	TYPO3
 * @subpackage	l10nmgr
 * @access public
 */
class tx_l10nmgr_models_translateable_translateableInformation {

	/**
	 * Holds the internal pageGroups of the translateableInformation
	 *
	 * @var ArrayObject
	 */
	protected $pageGroups;

	/**
	 * The forced previewLanguage of the pageGroup
	 *
	 * @var tx_l10nmgr_models_language_Language
	 */
	protected $previewLanguage;

	/**
	 * The targetLanguage of the pagegroup
	 *
	 * @var tx_l10nmgr_models_language_Language
	 * 	 */
	protected $targetLanguage;
	
	/**
	 * @return ArrayObject
	 */
	public function getPageGroups() {
		return $this->pageGroups;
	}
	/**
	 * @return tx_l10nmgr_models_language_Language
	 */
	public function getPreviewLanguage() {
		return $this->previewLanguage;
	}
	
	/**
	 * @return tx_l10nmgr_models_language_Language
	 */
	public function getTargetLanguage() {
		return $this->targetLanguage;
	}
	
	/**
	 * @param tx_l10nmgr_models_language_Language $previewLanguage
	 */
	public function setPreviewLanguage($previewLanguage) {
		$this->previewLanguage = $previewLanguage;
	}
	
	/**
	 * @param tx_l10nmgr_models_language_Language $targetLanguage
	 */
	public function setTargetLanguage($targetLanguage) {
		$this->targetLanguage = $targetLanguage;
	}
	/**
	 * Constructor of the object.
	 *
	 */
	public function __construct(){
		$this->pageGroups = new ArrayObject();
	}
	
	/**
	 * Method to add a pageGroup to the translateableInformation
	 * 
	 * @param tx_l10nmgr_models_translateable_PageGroup $pageGroup
	 */
	public function addPageGroup(tx_l10nmgr_models_translateable_PageGroup $pageGroup){
		$this->pageGroups->append($pageGroup);
	}
	
	
}

?>