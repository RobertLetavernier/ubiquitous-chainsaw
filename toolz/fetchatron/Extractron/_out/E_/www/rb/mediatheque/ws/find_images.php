<?php
/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 *
 * ATTENTION : ne pas modifier la structure de sortie du JSON
 * il est parsé par un outil manuellement
 * (qui ne sait pas gérer le JSON - parsing syntaxique)
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */

include (dirname(__FILE__) . "/../include/global.php");

setSessionVariable(FrontAuthentificationManager::S_KEY_LANGUAGE, 'fr');

$bError = false;
$sErrorMessage = '';
$amJson = array();
$amImages = array();
$asMainImage = array();

$mCodeXfurn = retrieveVariable('code', 'string', '');
if (strlen(trim($mCodeXfurn)) == 0)
{
    $sErrorMessage = 'code parameter is mandatory';
    $bError = true;
}

if (!$bError)
{
    $asCodeXfurn = explode(',', $mCodeXfurn);
    foreach ($asCodeXfurn as $sCodeXfurn)
    {
        $oElementPrincipal = ElementDAO::getInstance()->findPrincipalByElxfCodeXfurn($sCodeXfurn);
    
        $aiEltIdx = array();
        $aoElement = ElementDAO::getInstance()->findListByElxfCodeXfurn($sCodeXfurn);
        foreach ($aoElement as $oElementModel)
        {
            $aiEltIdx[$oElementModel->getEltIdx()] = $oElementModel->getEltIdx();
        }
    
        $aoFichier = FichierDAO::getInstance()->findListByEltIdxListAndByFicNumIndexedOrTarification($aiEltIdx, 1, $g_iCateIdxTarification);
        foreach ($aoElement as $oElementModel)
        {
            if ($oElementModel != null)
            {
                if (isset($aoFichier[$oElementModel->getEltIdx()]))
                {
                    foreach ($aoFichier[$oElementModel->getEltIdx()] as $oFichier)
                    {
                        $sFileName = $oFichier->getFicNom();
                        $sImagePath = $UploadPhysique . $asEnseigne[$g_iFrontEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($oElementModel->getEltCateIdx());
                        if (file_exists($sImagePath . "/" . $sFileName) && ($sFileName != ""))
                        {
                            $sThumbFileName = getReducedImage($sFileName, $sImagePath, 89, 89);
                            $sFullFileName = getReducedImage($sFileName, $sImagePath, 800, 600);
                            if ($oElementPrincipal != null && $oElementPrincipal->getEltIdx() == $oElementModel->getEltIdx())
                            {
                                $asMainImage['thumb'] = $PathVirtuel . 'image2.php?fic=' . urlencode($asEnseigne[$g_iFrontEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($oElementModel->getEltCateIdx()) . '/' . $sThumbFileName);
                                $asMainImage['full'] = $PathVirtuel . 'image2.php?fic=' . urlencode($asEnseigne[$g_iFrontEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($oElementModel->getEltCateIdx()) . '/' . $sFullFileName);
                            }
                            else
                            {
                                $asImage = array();
                                $asImage['thumb'] = $PathVirtuel . 'image2.php?fic=' . urlencode($asEnseigne[$g_iFrontEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($oElementModel->getEltCateIdx()) . '/' . $sThumbFileName);
                                $asImage['full'] = $PathVirtuel . 'image2.php?fic=' . urlencode($asEnseigne[$g_iFrontEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($oElementModel->getEltCateIdx()) . '/' . $sFullFileName);
                                $amImages[] = $asImage;
                            }
                        }
                    }
                }
            }
        }
    }
}

$amJson["error"] = $bError;
$amJson["error_message"] = $sErrorMessage;
$amJson["data"]["url_list"] = $amImages;
$amJson["data"]["main_image"] = $asMainImage;
$amJson["data"]["count"] = count($amImages);
echo json_encode($amJson);
