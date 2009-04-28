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
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

	// autoload the mvc
t3lib_extMgm::isLoaded('mvc', true);
tx_mvc_common_classloader::loadAll();

require_once t3lib_extMgm::extPath('l10nmgr') . 'models/translation/class.tx_l10nmgr_models_translation_factory.php';

/**
 * Verify that the TranslationFactory parse the XML
 * file correct and build the translationData collection as expected.
 *
 * {@inheritdoc}
 *
 * class.tx_l10nmgr_models_translation_factory_xmlData_testcase.php
 *
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version $Id$
 * @date $Date$
 * @since 24.04.2009 - 14:57:30
 * @see tx_phpunit_database_testcase
 * @category database testcase
 * @package TYPO3
 * @subpackage l10nmgr
 * @access public
 */
class tx_l10nmgr_models_translation_factory_xmlData_testcase extends tx_phpunit_testcase {

	/**
	 * @var tx_l10nmgr_models_translation_factory
	 */
	private $TranslationFactory = null;

	/**
	 * @var tx_l10nmgr_models_translation_data
	 */
	private $TranslationData = null;

	/**
	 * The setup method create the test database and
	 * loads the basic tables into the testdatabase
	 *
	 * @access public
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return void
	 */
	public function setUp() {
		$fileName                 = t3lib_extMgm::extPath('l10nmgr') . 'tests/translation/fixtures/files/validContent/catxml_export__to_en_GB_210409-175557.xml';
		$this->TranslationFactory = new tx_l10nmgr_models_translation_factory();
		$this->TranslationData    = $this->TranslationFactory->create($fileName);
	}

	/**
	 * This testcase should ensure, that the translationData returns a valid collection of pageIds
	 * from an import file.
	 *
	 * @param void
	 * @return void
	 * @author Timo Schmidt
	 */
	public function test_canDetermineCorrectPageIdsFromImportFile(){

		$this->assertEquals (
			11,
			$this->TranslationData->getPageIdCollection()->count(),
			'Unexpected Number of relevant pageids in importFile'
		);
	}

	/**
	 * Verify that the page collection with the UID 175 contains the right amount elements within the elements collection.
	 *
	 * @access public
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return void
	 */
	public function test_translationDataContainsRightAmountOfElements() {
		$fixtureElementsCount = (int)$this->TranslationData->getPagesCollection()->offsetGet(175)->getElementCollection()->count();

		$this->assertEquals (
			5,
			$fixtureElementsCount,
			'Wrong amount of elements returned. Expected amount of 5 and "' . $fixtureElementsCount . '" is returned.'
		);
	}

	/**
	 * Provides valid data records to test the stored table name
	 *
	 * <example>
	 * 	array (
	 * 		'pages', // expected tabel name
	 * 		1111, // Page UID
	 * 		1111, // Record UID
	 * 	)
	 * </exampl>
	 *
	 * @access public
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return array
	 */
	public function dataContainsRightTableNameForEntityDataProvider() {

		return array (
			array ('pages', 1111, 1111),
			array ('pages', 175, 175),
			array ('pages', 175, 175),
			array ('tt_content', 175, 423621),
			array ('tt_content', 175, 3897),
			array ('tt_content', 535, 1676),
		);
	}

	/**
	 * Verify that the right table name is stored
	 *
	 * @param string $expectedValue The expected result string
	 * @param integer $fixturePageId Page id where the elements (record) are located
	 * @param integer $fixtureElementId Record UID
	 * @access public
	 * @dataProvider dataContainsRightTableNameForEntityDataProvider
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return void
	 */
	public function test_elementContainsRightTableNameForEntity($expectedValue, $fixturePageId, $fixtureElementId) {
		$fixtureValue = $this->TranslationData->getPagesCollection()->offsetGet($fixturePageId)->getElementCollection()->offsetGet($fixtureElementId)->getTableName();

		$this->assertEquals (
			$expectedValue,
			$fixtureValue,
			'Wrong table name found. Expected table name "' . htmlspecialchars($expectedValue) . '" the following table name is given "' . htmlspecialchars($fixtureValue) . '"'
		);
	}

	/**
	 * Provides valid data records to test the "transformations" flag
	 *
	 * <example>
	 * 	array (
	 * 		true, // expected transformations status
	 * 		1111, // Page UID
	 * 		1111, // Record UID
	 * 		'title', // record field column name
	 * 	)
	 * </exampl>
	 *
	 * @access public
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return array
	 */
	public function validFieldTransoformationStatusDataProvider() {

		return array (
			array(false, 1111, 1111, 'title'),
			array(false, 535, 1674, 'header'),
			array(false, 535, 1693, 'header'),
			array(false, 25271, 25271, 'title'),
			array(true, 535, 1676, 'bodytext'),
		);
	}

	/**
	 * Validate the transformations flag on fields entity
	 *
	 * @param string $expectedValue The expected result string
	 * @param integer $fixturePageId Page id where the elements (record) are located
	 * @param integer $fixtureElementId Record UID
	 * @param string $fixtureFieldName Field name like "title" or "bodytext"
	 * @access public
	 * @dataProvider validFieldTransoformationStatusDataProvider
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return void
	 */
	public function test_fieldTransformationStatusIsSet($expectedValue, $fixturePageId, $fixtureElementId, $fixtureFieldName) {
		$fixtureValue = $this->TranslationData->getPagesCollection()->offsetGet($fixturePageId)->getElementCollection()->offsetGet($fixtureElementId)->getFieldCollection()->offsetGet($fixtureFieldName)->getTransformation();

		$this->assertEquals (
			$expectedValue,
			$fixtureValue,
			'Wrong content found in field of element. Expected content'
		);
	}

	/**
	 * Provides valid data records to test the stored content between CDATA tags
	 *
	 * <example>
	 * 	array (
	 * 		'WebEx Customers, // expected content
	 * 		1111, // Page UID
	 * 		1111, // Record UID
	 * 		'title', // record field column name
	 * 	)
	 * </exampl>
	 *
	 * @access public
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return array
	 */
	public function fieldContainsRightContentBetweenCdataDataProvider() {
		return array (
			array('WebEx Customers', 1111, 1111, 'title'),
			array('Contact Us', 175, 3887, 'header'),
			array('WebEx US offices', 536, 536, 'abstract'),
			array('WebEx International Offices', 535, 1674, 'header'),
		);
	}

	/**
	 * Verify that the right content is stored at the right place in the collection
	 *
	 * @param string $expectedValue The expected result string
	 * @param integer $fixturePageId Page id where the elements (record) are located
	 * @param integer $fixtureElementId Record UID
	 * @param string $fixtureFieldName Field name like "title" or "bodytext"
	 * @access public
	 * @dataProvider fieldContainsRightContentBetweenCdataDataProvider
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return void
	 */
	public function test_fieldContainsRightContentBetweenCDATA($expectedValue, $fixturePageId, $fixtureElementId, $fixtureFieldName) {
		$fixtureFieldContent = $this->TranslationData->getPagesCollection()->offsetGet($fixturePageId)->getElementCollection()->offsetGet($fixtureElementId)->getFieldCollection()->offsetGet($fixtureFieldName)->getContent();

		$this->assertEquals (
			$expectedValue,
			$fixtureFieldContent,
			'Wrong content found in field of element. Expected content "' . htmlspecialchars($expectedValue) . '" the following content is given "' . htmlspecialchars($fixtureFieldContent) . '"'
		);
	}

	/**
	 * Provides valid data records to test the stored content without CDATA tags
	 *
	 * <example>
	 * 	array (
	 * 		'WebEx Customers, // expected content
	 * 		1111, // Page UID
	 * 		1111, // Record UID
	 * 		'title', // record field column name
	 * 	)
	 * </exampl>
	 *
	 * @access public
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return array
	 */
	public function fieldContainsRightContentWithoutCdataDataProvider() {
		return array (
			array (
				'<p><a href="http://www.webex.co.uk/">WebEx Communications UK Ltd</a> <br/>20 Garrick Street <br/>London WC2E 9BT <br/>United Kingdom <br/>Tel: 0800 389 9772 <br/>Email: <a href="mailto:europe@webex.com">europe@webex.com</a> </p>',
				535,
				1693,
				'bodytext'
			),
			array (
				'<p>&nbsp;</p><h1>Your message has been sent</h1><p>&nbsp;</p><p>Thank you for your message.  We have forwarded your communication to the appropriate department.  If this is a technical support matter, please call our customer care line at<strong> 866-229-3239</strong> for immediate attention.  To speak with a sales representative, please call <strong>877-509-3239</strong>.</p>',
				19761,
				523511,
				'bodytext'
			),
		);
	}

	/**
	 * Verify that the right content is stored at the right place in the collection
	 *
	 * @param string $expectedValue The expected result string
	 * @param integer $fixturePageId Page id where the elements (record) are located
	 * @param integer $fixtureElementId Record UID
	 * @param string $fixtureFieldName Field name like "title" or "bodytext"
	 * @access public
	 * @dataProvider fieldContainsRightContentWithoutCdataDataProvider
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 * @return void
	 */
	public function test_fieldContainsRightContentWithoutCDATA($expectedValue, $fixturePageId, $fixtureElementId, $fixtureFieldName) {
		$fixtureFieldContent = $this->TranslationData->getPagesCollection()->offsetGet($fixturePageId)->getElementCollection()->offsetGet($fixtureElementId)->getFieldCollection()->offsetGet($fixtureFieldName)->getContent();

		$this->assertEquals (
			$expectedValue,
			$fixtureFieldContent,
			'Wrong content found in field of element. Expected content "' . htmlspecialchars($expectedValue) . '" the following content is given "' . htmlspecialchars($fixtureFieldContent) . '"'
		);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/l10nmgr/tests/translation/class.tx_l10nmgr_models_translation_factory_xmlData_testcase.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/l10nmgr/tests/translation/class.tx_l10nmgr_models_translation_factory_xmlData_testcase.php']);
}

?>