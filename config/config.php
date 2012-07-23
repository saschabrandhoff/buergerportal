<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  pixelSpread.de - 2009
 * @author     Sascha Brandhoff (brandhoff@pixelspread.de)
 * @package    Acquisto Webshop
 * @license    LGPL
 * @filesource
 */


/**
 * Backend module
 */

$GLOBALS['BE_MOD']['buergerportal'] = array(
    'ideen' => array
    (
        'tables'     => array('tl_ideen'),
        'icon'       => 'system/modules/acquistoShop/html/icons/basket.png',
        'stylesheet' => 'system/modules/acquistoShop/html/style.css'
    )
);

/**
 * Frontend module
 */
array_insert($GLOBALS['FE_MOD']['ideen'], 0, array
(
    'bp_idee_einreichen'  => 'bp_idee_einreichen',
    'bp_idee_details'     => 'bp_idee_details',
    'bp_ideen_liste'      => 'bp_ideen_liste',
    'bp_ideen_start'      => 'bp_ideen_start',
    'bp_ideen_katlist'    => 'bp_ideen_katlist'
));

?>