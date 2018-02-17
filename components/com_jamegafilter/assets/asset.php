<?php
/**
 * ------------------------------------------------------------------------
 * JA Megafilter Component
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2016 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */
//No direct to access this file.
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$currentLanguage = JFactory::getLanguage();
$isRTL = $currentLanguage->isRtl();

$doc->addStyleSheet('components/com_jamegafilter/assets/css/jquery-ui.min.css');

JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$doc->addScript('components/com_jamegafilter/assets/js/sticky-kit.min.js');
$doc->addScript('components/com_jamegafilter/assets/js/jquery-ui.range.min.js');

if ($isRTL) {
	$doc->addStyleSheet('components/com_jamegafilter/assets/css/jquery.ui.slider-rtl.css');
	$doc->addScript('components/com_jamegafilter/assets/js/jquery.ui.slider-rtl.min.js');
}

$doc->addScript('components/com_jamegafilter/assets/js/jquery.ui.datepicker.js');
$doc->addScript('components/com_jamegafilter/assets/js/jquery.ui.touch-punch.min.js');
$doc->addScript('components/com_jamegafilter/assets/js/jquery.cookie.js');
$doc->addScript('components/com_jamegafilter/assets/js/script.js');
$doc->addScript('components/com_jamegafilter/assets/js/libs.js');
$doc->addScript('components/com_jamegafilter/assets/js/main.js');

JText::script('COM_JAMEGAFILTER_TO');
JText::script('COM_JAMEGAFILTER_FROM');