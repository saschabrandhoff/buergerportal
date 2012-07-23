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

class bp_idee_details extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'bp_idee_details';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### IDEEN LISTE ###';
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

            if($this->Input->Post('FORM_SUBMIT') == "tl_bp_ideen_votes") {
                $objCount = $this->Database->prepare("SELECT COUNT(*) as total FROM tl_bp_ideen_votes WHERE pid = ? && member_id = ?")->execute($this->Input->Get('item'), $this->User->id);
                if(!$objCount->total) {
                    $this->Database->prepare("INSERT INTO tl_bp_ideen_votes (pid, tstamp, member_id) VALUES('" . $this->Input->Get('item') . "', '" . time() . "', '" . $objMember->id . "');")->execute();

                    $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags('{{page::id}}'));
                    $this->redirect(sprintf($this->generateFrontendUrl($objPage->fetchAssoc(), '/item/%s'), $this->Input->Get('item')));
                }
            }
        }

        if($this->Input->Post('FORM_SUBMIT_DEFAULT') == "tl_comments") {
#	    $objEmail          = new Email();
#	    $objEmail->from    = 'info@buergerportal-wa-fkb.de';
#	    $objEmail->subject = 'Neuer Vorschlag: ' . $this->Input->Post('title');
#	    $objEmail->text    = $this->Input->Post('description');
#	    $objEmail->sendTo('brandhoff@piraten-wa-fkb.de');
	}
	
        $objIdeas = $this->Database->prepare("SELECT * FROM tl_bp_ideen WHERE id = ?")->execute($this->Input->Get('item'));
        $objComments = $this->Database->prepare("SELECT COUNT(*) AS comments FROM tl_comments WHERE source = ? && parent = ? && published = 1")->execute('tl_bp_ideen', $objIdeas->id);
        $objVotes    = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_bp_ideen_votes WHERE pid = ?")->execute($objIdeas->id);

        $objIdee = (object) $objIdeas->row();
	$objIdee->description = $this->replace_uri($objIdee->description);
        $objIdee->comments    = $objComments->comments;
        $objIdee->liked       = $objVotes->total;
        $this->Template->Idee = $objIdee;

        $this->changeTitle($objIdee->title);

        $this->import('Comments');
        $arrNotifies = array();

        // Notify system administrator
        $arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];



        $objConfig = new stdClass();

        $objConfig->perPage = 0;
        $objConfig->order = 'ascending';
        $objConfig->template = $this->com_template;
        $objConfig->requireLogin = '';
        $objConfig->disableCaptcha = '';
        $objConfig->bbcode = '';
        $objConfig->moderate = '';

        $this->Template->allowComments = true;
        $this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_bp_ideen', $objIdeas->id, $arrNotifies);
    }

    function changeTitle($strTitle) {
        global $objPage;
        $objPage->title = $strTitle;
    }
    
    function replace_uri($str) {
	$pattern = '#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>]+)([\s\n<>]|$)#sm';
	return preg_replace($pattern,"\\1<a href=\"\\2\\3\"><u>\\2\\3</u></a>\\4",$str);
    } 
}

?>