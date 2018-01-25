<?php

include_once (dirname(__FILE__) . '/conf/_config.default.inc.php');
include_once (dirname(__FILE__) . '/conf/_config.inc.php');

define('LOG4PHP_CONFIGURATION', dirname(__FILE__) . '/conf/log_conf.xml');

include_once (dirname(__FILE__) . '/_autoload.php');

include_once (dirname(__FILE__) . '/lib/internal/_lib_sql.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_variable.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_url.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_image.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_xml.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_format.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_affichage.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_mail.inc.php');
include_once (dirname(__FILE__) . '/lib/internal/_lib_log_lite.inc.php');
include_once (dirname(__FILE__) . '/include/readfile_chunked.php');

//include_once (dirname(__FILE__) . '/lib/external/adodb/adodb.inc.php');

if (!defined("NO_SESSION"))
{
    session_start();
}
$g_oLogger = LoggerManager::getLogger('Global');

//Definition de l'objet de connexion a la base de donnees en variable globale
$g_oDbConnexion = DBManager::getInstance();

//Definition d'une variable globale contenant le titre du module en cours
$g_sCurrentTitle = "Home";


//Definition d'une variable globale pour l'id de l'action log en cours
//(pour modification sur action d'insert pour conserver l'id d'insertion)
$g_iCurrentActionLogIdx = 0;
$g_iCurrentInsertIdxToLog = 0;

$sServerName = strtolower($_SERVER['HTTP_HOST']);
if (!isset($g_asEnseigneDomain[$sServerName]))
{
    die('Nom de domaine non reconnu / unknown domain name');
}
else
{
    $g_iFrontEnsIdx = $g_asEnseigneDomain[$sServerName];
    switch ($g_iFrontEnsIdx)
    {
        case 2 :
            $g_sFrontTitle = 'Cuir Center';
            break;
        case 4 :
            $g_sFrontTitle = 'Natuzzi';
            break;
        case 1 :
        default:
            $g_sFrontTitle = 'Roche Bobois';
    }
    $PathVirtuel = 'http://' . $sServerName . '/' . $g_sUrlRoot;
}
