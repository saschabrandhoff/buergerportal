<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_filterList
 *
 * Front end module "mod_acquistoShop_filterList".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class bp_idee_einreichen extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'bp_idee_einreichen';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### IDEE EINREICHEN ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if (FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');
            $objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->limit(1)->execute($this->User->id);
            $arrMember = $objMember->row();

            if($this->Input->Post('FORM_SUBMIT') == "tl_bp_ideen" && $this->Input->Post('title') && $this->Input->Post('description')) {
                $this->Database->prepare("INSERT INTO tl_bp_ideen (tstamp, member_id, title, description, location) VALUES('" . time() . "', '" . $objMember->id . "', '" . $this->Input->Post('title') . "', '" . $this->Input->Post('description') . "', '" . $this->Input->Post('location') . "');")->execute();

		$objEmail          = new Email();
		$objEmail->from    = 'info@buergerportal-wa-fkb.de';
		$objEmail->subject = 'Neuer Vorschlag: ' . $this->Input->Post('title');
		$objEmail->text    = $this->Input->Post('description');
		$objEmail->sendTo('brandhoff@piraten-wa-fkb.de');

                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags('{{page::id}}'));
                $this->redirect($this->generateFrontendUrl($objPage->fetchAssoc()));	
            }
        }
    }
}

?>