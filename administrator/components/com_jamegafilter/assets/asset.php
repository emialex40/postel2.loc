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
$doc->addScript(JUri::root(true).'/administrator/components/com_jamegafilter/assets/js/jquery-ui.min.js');
$doc->addStyleSheet(JUri::root(true).'/administrator/components/com_jamegafilter/assets/css/style.css');
