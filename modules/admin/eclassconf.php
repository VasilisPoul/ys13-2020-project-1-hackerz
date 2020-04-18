<?php
/*========================================================================
*   Open eClass 2.3
*   E-learning and Course Management System
* ========================================================================
*  Copyright(c) 2003-2010  Greek Universities Network - GUnet
*  A full copyright notice can be read in "/info/copyright.txt".
*
*  Developers Group:	Costas Tsibanis <k.tsibanis@noc.uoa.gr>
*			Yannis Exidaridis <jexi@noc.uoa.gr>
*			Alexandros Diamantidis <adia@noc.uoa.gr>
*			Tilemachos Raptis <traptis@noc.uoa.gr>
*
*  For a full list of contributors, see "credits.txt".
*
*  Open eClass is an open platform distributed in the hope that it will
*  be useful (without any warranty), under the terms of the GNU (General
*  Public License) as published by the Free Software Foundation.
*  The full license can be read in "/info/license/license_gpl.txt".
*
*  Contact address: 	GUnet Asynchronous eLearning Group,
*  			Network Operations Center, University of Athens,
*  			Panepistimiopolis Ilissia, 15784, Athens, Greece
*  			eMail: info@openeclass.org
* =========================================================================*/

/*===========================================================================
	eclassconf.php
	@last update: 31-05-2006 by Pitsiougas Vagelis
	@authors list: Karatzidis Stratos <kstratos@uom.gr>
		       Pitsiougas Vagelis <vagpits@uom.gr>
==============================================================================
        @Description: Change configuration file settings

 	This script allows the administrator to change all values in the config.php,
 	to make a backup of the orginal and restore values from backup config.php

 	The user can : - Change settings in config.php
 	               - Create a backup file of the original config.php
 	               - Restore values from backup config.php
                 - Return to course list

 	@Comments: The script is organised in three sections.

  1) Display values from config.php
  2) Restore values from backup config.php
  3) Save new config.php
  4) Create a backup file of config.php
  5) Display all on an HTML page

==============================================================================*/

/*****************************************************************************
 * DEAL WITH  BASETHEME, OTHER INCLUDES AND NAMETOOLS
 ******************************************************************************/
// Check if user is administrator and if yes continue
// Othewise exit with appropriate message
require_once '../../modules/htmlpurifier/HTMLPurifier.auto.php';
$require_admin = TRUE;
// Include baseTheme
include '../../modules/htmlpurifier/HTMLPurifier.auto.php';
include '../../include/baseTheme.php';
$purifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());
$nameTools = $langEclassConf;
$navigation[] = array("url" => "index.php", "name" => $langAdmin);
// Initialise $tool_content
$tool_content = "";

/*****************************************************************************
 * MAIN BODY
 ******************************************************************************/
// Save new config.php
if (isset($submit)) {
    // Make config directory writable
    @chmod("../../config", 777);
    @chmod("../../config", 0777);
    // Create backup file
    if ($backupfile == "on") {
        // If a backup already exists delete it
        if (file_exists("../../config/config_backup.php"))
            unlink("../../config/config_backup.php");
        // Create the backup
        copy("../../config/config.php", "../../config/config_backup.php");
    }
    $urlServer = $_POST['formurlServer'];
    $regex = '/^[^,;"\']+$/';
    $urlServerRegex = "/^(http|https|ftp):\/\/((([1-9][0-9_-]*)\.([0-9][0-9_-]*)\.([0-9][0-9_-]*)\.([0-9][0-9_-]*))|([a-zA-Z0-9]+(\.[a-zA-Z0-9]+)+))\/$/i";

    // Open config.php empty
    $fd = @fopen("../../config/config.php", "w");
    if (!$fd) {
        $tool_content .= $langFileError;
    } else {

        if ($_POST['formcloseuserregistration'] == 'false') {
            $user_reg = 'FALSE';
        } else {
            $user_reg = 'TRUE';
        }
        if (defined('UTF8')) {
            $utf8define = "define('UTF8', true);";
        }

        $formwebDir = str_replace("\\", "/", realpath($_POST['formwebDir']) . "/");

        // fields
        $urlServer = preg_match($urlServerRegex, $_POST['formurlServer']) === 1 ? $purifier->purify($_POST['formurlServer']) : '';
        $urlAppend = preg_match($regex, $_POST['formurlAppend']) === 1 ? $purifier->purify($_POST['formurlAppend']) : '';
        $webDir = preg_match($regex, $formwebDir) ? $purifier->purify($formwebDir) : '';
        $mysqlServer = preg_match($regex, $_POST['formmysqlServer']) === 1 ? $purifier->purify($_POST['formmysqlServer']) : '';
        $mysqlUser = preg_match($regex, $_POST['formmysqlUser']) === 1 ? $purifier->purify($_POST['formmysqlUser']) : '';
        $mysqlPassword = preg_match($regex, $_POST['formmysqlPassword']) === 1 ? $purifier->purify($_POST['formmysqlPassword']) : '';
        $mysqlMainDb = preg_match($regex, $_POST['formmysqlMainDb']) === 1 ? $purifier->purify($_POST['formmysqlMainDb']) : '';
        $phpMyAdminURL = preg_match($regex, $_POST['formphpMyAdminURL']) === 1 ? $purifier->purify($_POST['formphpMyAdminURL']) : '';
        $phpSysInfoURL = preg_match($regex, $_POST['formphpSysInfoURL']) === 1 ? $purifier->purify($_POST['formphpSysInfoURL']) : '';
        $emailAdministrator = preg_match($regex, $_POST['formemailAdministrator']) === 1 ? $purifier->purify($_POST['formemailAdministrator']) : '';
        $administratorName = preg_match($regex, $_POST['formadministratorName']) === 1 ? $purifier->purify($_POST['formadministratorName']) : '';
        $administratorSurname = preg_match($regex, $_POST['formadministratorSurname']) === 1 ? $purifier->purify($_POST['formadministratorSurname']) : '';
        $siteName = preg_match($regex, $_POST['formsiteName']) === 1 ? $purifier->purify($_POST['formsiteName']) : '';
        $telephone = preg_match($regex, $_POST['formtelephone']) === 1 ? $purifier->purify($_POST['formtelephone']) : '';
        $emailhelpdesk = preg_match($regex, $_POST['formemailhelpdesk']) === 1 ? $purifier->purify($_POST['formemailhelpdesk']) : '';
        $Institution = preg_match($regex, $_POST['formInstitution']) === 1 ? $purifier->purify($_POST['formInstitution']) : '';
        $InstitutionUrl = preg_match($regex, $_POST['formInstitutionUrl']) === 1 ? $purifier->purify($_POST['formInstitutionUrl']) : '';
        $language = preg_match($regex, $_POST['formlanguage']) === 1 ? $purifier->purify($_POST['formlanguage']) : '';
        $postaddress = preg_match($regex, $_POST['formpostaddress']) === 1 ? $purifier->purify($_POST['formpostaddress']) : '';
        $fax = preg_match($regex, $_POST['formfax']) === 1 ? $purifier->purify($_POST['formfax']) : '';
        $durationAccount = preg_match($regex, $_POST['formdurationAccount']) === 1 ? $purifier->purify($_POST['formdurationAccount']) : '';

        // Prepare config.php content
        $stringConfig = '<?php
/*===========================================================================
 *   Open eClass 2.3
 *   E-learning and Course Managemenconft System
 *===========================================================================

 config.php automatically generated on ' . date('c') . '

*/

' . $utf8define . '
$urlServer	=	"' . $urlServer . '";
$urlAppend	=	"' . $urlAppend . '";
$webDir  =	"' . $webDir . '" ;
$mysqlServer="' . $mysqlServer . '";
$mysqlUser="' . $mysqlUser . '";
$mysqlPassword="' . $mysqlPassword . '";
$mysqlMainDb="' . $mysqlMainDb . '";
$phpMyAdminURL="' . $phpMyAdminURL . '";
$phpSysInfoURL="' . $phpSysInfoURL . '";
$emailAdministrator="' . $emailAdministrator . '";
$administratorName="' . $administratorName . '";
$administratorSurname="' . $administratorSurname . '";
$siteName="' . $siteName . '";
$telephone="' . $telephone . '";
$emailhelpdesk="' . $emailhelpdesk . '";
$Institution="' . $Institution . '";
$InstitutionUrl="' . $InstitutionUrl . '";

// available: greek and english
$language = "' . $language . '";
$postaddress = "' . $postaddress . '";
$fax = "' . $fax . '";
$close_user_registration = "' . $user_reg . '";
$encryptedPasswd = "true";
$persoIsActive = TRUE;
$durationAccount = "' . $durationAccount . '";
// end of file
';
        // Save new config.php
        fwrite($fd, $stringConfig);

        // Display result message
        $tool_content .= "<p>" . $langFileUpdatedSuccess . "</p>";

    }
    // Display link to go back to index.php
    $tool_content .= "<center><p><a href=\"index.php\">" . $langBack . "</a></p></center>";

} // Display config.php edit form
else {
    $titleextra = "config.php";
    // Check if restore has been selected
    if (isset($restore) && $restore == "yes") {
        // Substitute variables with those from backup file
        $titleextra = " ($langRestoredValues)";
        @include("../../config/config_backup.php");
    }
    // Constract the form



    $tool_content .= "
    <form action=\"" .$purifier->purify($_SERVER['PHP_SELF']) . "\" method=\"post\">";
    $tool_content .= "

  <table class=\"FormData\" width=\"99%\" align=\"left\">
  <tbody>
  <tr>
    <th width=\"220\" class=\"left\">&nbsp;</th>
    <td>" . $langFileEdit . " <b>" . $titleextra . "</b></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$urlServer:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formurlServer\" size=\"40\" value=\"" . $urlServer . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$urlAppend:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formurlAppend\" size=\"40\" value=\"" . $urlAppend . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$webDir:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formwebDir\" size=\"40\" value=\"" . $webDir . "\"></td>
  </tr>
  <tr>
    <td colspan=\"2\">&nbsp;</td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$mysqlServer:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formmysqlServer\" size=\"40\" value=\"" . $mysqlServer . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$mysqlUser:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formmysqlUser\" size=\"40\" value=\"" . $mysqlUser . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$mysqlPassword:</b></th>
    <td><input class=\"FormData_InputText\" type=\"password\" name=\"formmysqlPassword\" size=\"40\" value=\"" . $mysqlPassword . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$mysqlMainDb:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formmysqlMainDb\" size=\"40\" value=\"" . $mysqlMainDb . "\"></td>
  </tr>";
    $tool_content .= "  <tr>
    <th class=\"left\"><b>\$phpMyAdminURL:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formphpMyAdminURL\" size=\"40\" value=\"" . $phpMyAdminURL . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$phpSysInfoURL:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formphpSysInfoURL\" size=\"40\" value=\"" . $phpSysInfoURL . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$emailAdministrator:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formemailAdministrator\" size=\"40\" value=\"" . $emailAdministrator . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$administratorName:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formadministratorName\" size=\"40\" value=\"" . $administratorName . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$administratorSurname:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formadministratorSurname\" size=\"40\" value=\"" . $administratorSurname . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$siteName:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formsiteName\" size=\"40\" value=\"" . $siteName . "\"></td>
  </tr>
  <tr>
    <td colspan=\"2\">&nbsp;</td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$postaddress:</b></th>
	<td><textarea rows='3' cols='40' name='formpostaddress'>$postaddress</textarea></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$telephone:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formtelephone\" size=\"40\" value=\"" . $telephone . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$fax:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formfax\" size=\"40\" value=\"" . $fax . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$emailhelpdesk:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formemailhelpdesk\" size=\"40\" value=\"" . $emailhelpdesk . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$Institution:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formInstitution\" size=\"40\" value=\"" . $Institution . "\"></td>
  </tr>
  <tr>
    <th class=\"left\"><b>\$InstitutionUrl:</b></th>
    <td><input class=\"FormData_InputText\" type=\"text\" name=\"formInstitutionUrl\" size=\"40\" value=\"" . $InstitutionUrl . "\"></td>
  </tr>
  <tr>
    <td colspan=\"2\">&nbsp;</td>
  </tr>";
    if ($language == "greek") {
        $grSel = "selected";
        $enSel = "";
    } else {
        $grSel = "";
        $enSel = "selected";
    }
    $tool_content .= "
  <tr>
    <th class=\"left\"><b>\$language:</b></th>
    <td><select name=\"formlanguage\">
      <option value=\"greek\" " . $grSel . ">greek</option>
      <option value=\"english\" " . $enSel . ">english</option>
    </select></td>
  </tr>";

    if ($close_user_registration == "true") {
        $close_user_registrationSelTrue = "selected";
        $close_user_registrationSelFalse = "";
    } else {
        $close_user_registrationSelTrue = "";
        $close_user_registrationSelFalse = "selected";
    }

    $tool_content .= "
  <tr>
    <th class=\"left\"><b>\$close_user_registration:</b></th>
    <td><select name=\"formcloseuserregistration\">
      <option value=\"true\" " . $close_user_registrationSelTrue . ">true</option>
      <option value=\"false\" " . $close_user_registrationSelFalse . ">false</option>
    </select></td>
</tr>";

    $tool_content .= "
  <tr>
    <th class=\"left\"><b>\$durationAccount:</b></th>
    <td><input type=\"text\" name=\"formdurationAccount\" size=\"40\" value=\"" . $durationAccount . "\"></td>
</tr>";
    $tool_content .= "
  <tr>
    <th class=\"left\"><b>\$encryptedPasswd:</b></th>
    <td><input type=\"checkbox\" checked disabled> " . $langencryptedPasswd . "</td>
  </tr>
  <tr>
    <td colspan=\"2\">&nbsp;</td>
  </tr>
  <tr>
    <th class=\"left\">" . $langReplaceBackupFile . "</th>
    <td><input type=\"checkbox\" name=\"backupfile\" checked></td>
  </tr>
  <tr>
    <th class=\"left\">&nbsp;</th>
    <td><input type='submit' name='submit' value='$langModify'></td>
  </tr>
  </tbody>
  </table>
  </form>\n";
    // Check if a backup file exists
    if (file_exists("../../config/config_backup.php")) {
        // Give option to restore values from backup file
        $tool_content .= "
  <table class=\"FormData\" width=\"99%\" align=\"left\">
  <tbody>
  <tr>
    <th width=\"220\" class=\"left\">" . $langOtherActions . "</th>
    <td><a href=\"eclassconf.php?restore=yes\">$langRestoredValues</a></td>
  </tr>
  </tbody>
  </table>";
    }
    // Display link to index.php
    $tool_content .= "
    <br>
    <p align=\"right\"><a href=\"index.php\">" . $langBack . "</a></p>";
    // After restored values have been inserted into form then bring back
    // values from original config.php, so the rest of the page can be played correctly
    if (isset($restore) && $restore == "yes") {
        @include("../../config/config.php");
    }
}

/*****************************************************************************
 * DISPLAY HTML
 ******************************************************************************/
// Call draw function to display the HTML
// $tool_content: the content to display
// 3: display administrator menu
// admin: use tool.css from admin folder
draw($tool_content, 3, 'admin');
?>
