<?php

/** ***************************************************************************
 * APPLICATION PATHS
 */
//Upload physical path
$UploadPhysique                     = '/opt/nfs/rbp_files/';

// Application virtual paths
$g_sUrlRoot                         = '';
$g_sAdminDir                        = 'admin';

/** ***************************************************************************
 *  BDD
 */
$g_sDbHostname                      = 'dbHost';
$g_sDbUsername                      = 'dbRootUser';
$g_sDbPassword                      = 'dbRootPass';
$g_sDbBasename                      = 'media';
$g_sDbType                          = 'mysql';
$g_sDbPrefix                        = 'rbp_';
$g_bDbHistory                       = true;

/** ***************************************************************************
 * E-MAILS
 */
$g_sSmtpHostname                    = 'smtp.web';
$g_bSmtpAuthent                     = false;
$g_sSmtpUsername                    = 'smtpUser';
$g_sSmtpPassword                    = 'smtpPass';

$g_sSenderName                      = 'Roche Bobois';
$g_sSenderAddress                   = 'sender@rochebobois.com';
$g_bSendModeSmtp                    = true;
$g_sResponsableName                 = 'ResponsableName';
$g_sResponsableAdress               = 'responsable@rochebobois.com';

/** ***************************************************************************
 * DATAS
 */
// configuration des domaines / ensIdx
$g_asEnseigneDomain                             = array();
$g_asEnseigneDomain['media.roche-bobois.biz']   = 1;
$g_asEnseigneDomain['media.myrochebobois.com']  = 1;
$g_asEnseigneDomain['media.cuir-center.biz']    = 2;
$g_asEnseigneDomain['media.mycuircenter.com']   = 2;
$g_asEnseigneDomain['media.mynatuzzi.com']      = 4;
$g_asEnseigneDomain['media.natuzzi-store.biz']  = 4;

/** ***************************************************************************
 * THIRD PARTY SOFTWARES
 */
// FCK
$g_sFCKeditorUserFilesUrl           = $g_sUrlRoot . '/media';
$g_sFCKeditorUserFilesPath          = dirname(__FILE__) . '/../media';

// Felix
$g_sFelixWebServiceUrl              = 'https://www.deca.eu/apps/xfurn/demo/WebService/';
$g_sFelixWebServiceClient           = 'demo';
$g_sFelixWebServiceLogin            = 'admin';
$g_sFelixWebServiceSSOKey           = '2Ifgo4aIiBAKKN58jwMoqkZZ97qWDEr4';
$g_sFelixWebServiceSecure           = 'gLkbL3oSgDrVhJX8AZBEo93tO1wsQcWQ';

/** ***************************************************************************
 * RUN PARAMETERS
 */
$g_bLogAction                       = true;
$g_bAdminAuthent                    = true;
$g_defaultEncoding                  = 'UTF-8';
$g_iCateIdxTarification             = 23;
$g_sImportmediaCSVFolder            = '/opt/nfs/rbp_files/import_par_lot/';
$g_sImportmediaCSVFilename          = 'import_media.csv';
