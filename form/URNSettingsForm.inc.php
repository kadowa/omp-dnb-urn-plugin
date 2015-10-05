<?php

/**
 * @file plugins/pubIds/urn_dnb/classes/form/URNSettingsForm.inc.php
 *
 * Copyright (c) 2015 Heidelberg University
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNSettingsForm
 * @ingroup plugins_pubIds_doi
 *
 * @brief Form for press managers to setup DOI plugin
 */


import('lib.pkp.classes.form.Form');

class URNSettingsForm extends Form {

	//
	// Private properties
	//
	/** @var integer */
	var $_pressId;

	/**
	 * Get the press ID.
	 * @return integer
	 */
	function _getPressId() {
		return $this->_pressId;
	}

	/** @var DOIPubIdPlugin */
	var $_plugin;

	/**
	 * Get the plugin.
	 * @return DOIPubIdPlugin
	 */
	function &_getPlugin() {
		return $this->_plugin;
	}


	//
	// Constructor
	//
	/**
	 * Constructor
	 * @param $plugin DOIPubIdPlugin
	 * @param $pressId integer
	 */
	function URNSettingsForm(&$plugin, $pressId) {
		$this->_pressId = $pressId;
		$this->_plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
		
		$this->setData('pluginName', $plugin->getName());
	}


	//
	// Implement template methods from Form
	//
	/**
	 * @see Form::initData()
	 */
	function initData() {
		$pressId = $this->_getPressId();
		$plugin =& $this->_getPlugin();
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$this->setData($fieldName, $plugin->getSetting($pressId, $fieldName));
		}
	}

	/**
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array_keys($this->_getFormFields()));
	}

	/**
	 * @see Form::execute()
	 */
	function execute() {
		$plugin =& $this->_getPlugin();
		$pressId = $this->_getPressId();
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$plugin->updateSetting($pressId, $fieldName, $this->getData($fieldName), $fieldType);
		}
	}


	//
	// Private helper methods
	//
	function _getFormFields() {
		return array(
			'urnPrefix' => 'string',
		);
	}
}

?>
