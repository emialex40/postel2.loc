<?php
/**
 * List the order payments
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2016 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvivirtuemartorderpayment.php 2396 2013-03-24 11:55:23Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with order payments
 */
class JFormFieldCsviVirtuemartOrderPayment extends JFormFieldCsviForm {

	protected $type = 'CsviVirtuemartOrderPayment';

	/**
	 * Specify the options to load based on default site language
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		array	an array of options
	 * @since 		4.0
	 */
	protected function getOptions() {
		// Get the default administrator language
		$params = JComponentHelper::getParams('com_languages');
		$lang = strtolower(str_replace('-', '_', $params->get('administrator')));
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('virtuemart_paymentmethod_id AS value, payment_name AS text');
		$query->from('#__virtuemart_paymentmethods_'.$lang);
		$db->setQuery($query);
		$methods = $db->loadObjectList();
		if (empty($methods)) $methods = array();
		return array_merge(parent::getOptions(), $methods);
	}
}
