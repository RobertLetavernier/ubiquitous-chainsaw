<?php
//Script de download d'image/de fichier
//génére un fichier suivant le ContentType du fichier uploadé

include("include/global.php");

$db = new MySql_Sql();

$hasRight = false;

if (isset($_GET["idx"]) && isset($_GET["num"]))
{
    $eltIdx = $_GET["idx"];
    $imgNum = $_GET["num"];
    $db->query("SELECT ficNom, ficEnsIdx, ficCateIdx FROM {$db->dbPrefixe}fichier WHERE eltIdx={$eltIdx} AND ficNum={$imgNum}");
    if ($data = $db->next_record())
    {
        if ($data->ficCateIdx != $g_iCateIdxTarification)
        {
            if (trim($data->ficNom) != "")
            {
                $fic = $UploadPhysique . $asEnseigne[$data->ficEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($data->ficCateIdx) . "/";
                if ((isset($_GET["type"])) && ($_GET["type"] != ""))
                {
                    $fic .= "vignette/";
                }
                $fic .= $data->ficNom;
            }
        }
        else
        {
            $sImagePath = $asEnseigne[$g_iFrontEnsIdx] . "/" . CategorieHelper::getInstance()->getDossierByCateIdx($data->ficCateIdx);
            $sFileName = $data->ficNom;
            if ($sFileName != "" && file_exists($UploadPhysique . $sImagePath . "/" . $sFileName))
            {
                $fic = $UploadPhysique . $sImagePath . '/' . getReducedImage($sFileName, $UploadPhysique .  $sImagePath, 89, 89);
            }
        }
    }
}
if (file_exists($fic))
{
    if (isFileType($fic, "img"))
    {
        $contentType = "image/" . strtolower(substr(strrchr($data->ficNom, "."), 1));
        $hasRight = true;
    }
}

if (!$hasRight)
{
    $fic = "layout/images/pix.gif";
    $contentType = "image/gif";
}

header("Content-Type: " . $contentType);
header("Content-Length: " . filesize($fic));
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
readfile($fic);
?>