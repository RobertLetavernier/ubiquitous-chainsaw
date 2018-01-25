<?php
/*******************************************************************
module/element/lookup.php
Page de s&eacute;lection des elements
 ********************************************************************
Version : 1.0
Auteur : AOU - 2002-12-10
Mise e jour : 20xx-xx-xx - xxxxxxxx
 *******************************************************************/

///Include
require_once("../include/global.php");
$g_bNoAuthent = true;
$sPageTitle = '';
require_once(dirname(__FILE__) . "/../admin/include/header_light.php");

//Objet de bdd
$oDb = new Mysql_Sql();

$aiEltCateIdx = array(1, 2, $g_iCateIdxTarification);
$sTextNom = 'Nom';
$sErrorMessage = '';

//infos relatives aux visuels d&eacute;tour&eacute;s
$titreElement = " photos d&eacute;tour&eacute;e";

//R&eacute;cup&eacute;ration des param&eacute;tres
if (!isset($_SESSION["sess_rows"])) $_SESSION["sess_rows"] = 25;
if (isset($_POST["rows"])) $_SESSION["sess_rows"] = (int) $_POST["rows"];
if (!isset($_SESSION["sess_page"])) $_SESSION["sess_page"] = 1;
if (isset($_POST["page"])) $_SESSION["sess_page"] = (int) $_POST["page"];
if (!isset($_SESSION["sess_sortf"])) $_SESSION["sess_sortf"] = "Nom";
if (isset($_POST["sortf"])) $_SESSION["sess_sortf"] = $_POST["sortf"];
if (!isset($_SESSION["sess_sortt"])) $_SESSION["sess_sortt"] = "ASC";
if (isset($_POST["sortt"])) $_SESSION["sess_sortt"] = $_POST["sortt"];
if (isset($_POST["selCollecIdx"])) $_SESSION["sess_collecIdx"] = (int)$_POST["selCollecIdx"];
if (isset($_POST["selFoncIdx"])) $_SESSION["sess_foncIdx"] = (int)$_POST["selFoncIdx"];

$iEnsIdx = retrieveVariable('brand', 'int', 0);
if ($iEnsIdx == 0)
{
    $sErrorMessage .= '<li>parameter [brand] is mandatory</li>';
}
$sCodeXfurn = retrieveVariable('code', 'string', '');
if (strlen(trim($sCodeXfurn)) == 0)
{
    $sErrorMessage .= '<li>parameter [code] is mandatory</li>';
}

$sEltNom = retrieveVariable('selEltNom', 'string', $sTextNom);
if ($sEltNom == '')
{
    $sEltNom = $sTextNom;
}
$iCollectIdx = getSessionVariable('sess_collecIdx', 0);
$iFoncIdx = getSessionVariable('sess_foncIdx', 0);

if ($sErrorMessage == '')
{
    $sQuery = '';
    $sQuery .= ' SELECT e.eltIdx, eltNom, CONCAT(IFNULL(eltModele, \'\'), \' \', eltNom) AS eltModeleNom, eltDate, eltOnline, foncNom, collecNom, eltSemestre, eltAnnee, elxfIsPrincipal';
    $sQuery .= '   FROM ' . $oDb->dbPrefixe . 'element AS e';
    $sQuery .= '  INNER JOIN ' . $oDb->dbPrefixe . 'elementcontent AS ec ON e.eltIdx = ec.eltIdx';
    $sQuery .= '   LEFT JOIN ' . $oDb->dbPrefixe . 'fonctioncontent ON eltFoncIdx = foncIdx AND foncLg=' . tosql($sDefaultLg);
    $sQuery .= '   LEFT JOIN ' . $oDb->dbPrefixe . 'collectioncontent ON eltCollecIdx = collecIdx AND collecLg=' . tosql($sDefaultLg);
    $sQuery .= '  INNER JOIN ' . $oDb->dbPrefixe . 'elementxfurn ';
    $sQuery .= '        ON elxfEltIdx = e.eltIdx AND elxfCodeXfurn=' . tosql($sCodeXfurn);
    $sQuery .= '  WHERE ';
    $sQuery .= '        e.eltCateIdx IN (' . implode(',', $aiEltCateIdx) . ')';
    $sQuery .= '        AND e.eltEnsIdx = ' . $iEnsIdx;
    $sQuery .= '        AND eltLg = ' . tosql($sDefaultLg);
    $sQuery .= '        AND (eltOnline = 1 OR e.eltCateIdx = ' . $g_iCateIdxTarification . ')';
    $mResEltSelected = $oDb->query($sQuery);
    $numRowsSelected = $oDb->num_rows($mResEltSelected);


    //Construction de la query et ex&eacute;cution
    $sQuery = '';
    $sQuery .= ' SELECT e.eltIdx, eltNom, CONCAT(IFNULL(eltModele, \'\'), \' \', eltNom) AS eltModeleNom, eltDate, eltOnline, foncNom, collecNom, eltSemestre, eltAnnee, 0 as elxfIsPrincipal';
    $sQuery .= '   FROM ' . $oDb->dbPrefixe . 'element AS e';
    $sQuery .= '  INNER JOIN ' . $oDb->dbPrefixe . 'elementcontent AS ec ON e.eltIdx = ec.eltIdx';
    $sQuery .= '   LEFT JOIN ' . $oDb->dbPrefixe . 'fonctioncontent ON eltFoncIdx = foncIdx AND foncLg=' . tosql($sDefaultLg);
    $sQuery .= '   LEFT JOIN ' . $oDb->dbPrefixe . 'collectioncontent ON eltCollecIdx = collecIdx AND collecLg=' . tosql($sDefaultLg);
    $sQuery .= '  WHERE ';
    $sQuery .= '        e.eltCateIdx IN (' . implode(',', $aiEltCateIdx) . ')';
    $sQuery .= '        AND e.eltEnsIdx=' . $iEnsIdx;
    $sQuery .= '        AND eltLg=' . tosql($sDefaultLg);
    $sQuery .= '        AND (eltOnline = 1 OR e.eltCateIdx = ' . $g_iCateIdxTarification . ')';
    $sQuery .= '        AND e.eltIdx NOT IN (SELECT elxfEltIdx FROM ' . $oDb->dbPrefixe . 'elementxfurn WHERE elxfCodeXfurn=' . tosql($sCodeXfurn) . ')';
    if ($iCollectIdx != 0)
    {
        $sQuery .= '        AND eltCollecIdx=' . tosql($iCollectIdx, 'Number');
    }
    if ($iFoncIdx != 0)
    {
        $sQuery .= '        AND eltFoncIdx=' . tosql($iFoncIdx, 'Number');
    }
    if ($sEltNom != $sTextNom && $sEltNom != '')
    {
        $sQuery .= '        AND (eltNom LIKE ' . tosql('%' . $sEltNom . '%') . ' OR eltModele LIKE ' . tosql('%' . $sEltNom . '%') . ')';
    }
    if ($_SESSION["sess_sortf"] == "Date") {
        $sQuery .= '        ORDER BY eltAnnee ' . $_SESSION["sess_sortt"] . ', eltSemestre ' . $_SESSION["sess_sortt"];
    }
    else {
        $sQuery .= '        ORDER BY elt' . $_SESSION["sess_sortf"] . ' ' . $_SESSION["sess_sortt"];
    }
    
    $mResElt = $oDb->query($sQuery);

    //Calcul des pages
    $numRows = $oDb->num_rows($mResElt);

    //V&eacute;rif. que les param&eacute;tres ne sont pas truqu&eacute;s (pas de v&eacute;rif des champs - &eacute; faire...)
    if ($_SESSION["sess_page"]<1) { $_SESSION["sess_page"]=1; }
    if ($_SESSION["sess_rows"] != 5 and $_SESSION["sess_rows"] != 10 and $_SESSION["sess_rows"] != 25 and $_SESSION["sess_rows"] != 50 and $_SESSION["sess_rows"] != 100) {$_SESSION["sess_rows"] = 10; }
    $nbPage = ceil( ( $numRows / $_SESSION["sess_rows"] ) ); //Calcul du nombre de page
    if ($_SESSION["sess_page"]>=$nbPage) { $_SESSION["sess_page"]=$nbPage; }
    ?>
<script language="javascript">
    function changeOrder(champ, ordre) {
        document.location.href="tag_images.php";
        document.frm.sortf.value = champ;
        document.frm.sortt.value = ordre;
        document.frm.submit();
    }
</script>
        <td valign="top" width="100%" align="center">
            <form name="frm" method="post" action="tag_images.php" style="margin:0;padding:0">
                <input type="hidden" name="code" value="<?php echo $sCodeXfurn; ?>">
                <input type="hidden" name="brand" value="<?php echo $iEnsIdx; ?>">
                <input type="hidden" name="sortf" value="<?php echo $_SESSION["sess_sortf"]; ?>">
                <input type="hidden" name="sortt" value="<?php echo $_SESSION["sess_sortt"]; ?>">
                <input type="hidden" name="rows" id="hid_rows" value="<?php echo $_SESSION["sess_rows"]; ?>">
                <input type="hidden" name="page" id="hid_page" value="<?php echo $_SESSION["sess_page"]; ?>">
                <table border="1" cellpadding="5" cellspacing="0" bordercolor="#000000">
                    <tr>
                        <td class="menu2" align="center">
                            <input type="text" name="selEltNom" id="selEltNom" value="<?php echo $sEltNom ?>" onfocus="if(this.value=='<?php echo $sTextNom;?>') { this.value=''; }" onblur="if (this.value=='') { this.value='<?php echo $sTextNom; ?>'};" />
                            <select name="selCollecIdx">
                                <option value="0">Toutes les collections</option>
                                <?php
                                $oDb->query("
        SELECT c.collecIdx AS idx, collecNom AS lib
        FROM {$oDb->dbPrefixe}collection AS c
        INNER JOIN {$oDb->dbPrefixe}collectioncontent AS cc ON c.collecIdx = cc.collecIdx
        WHERE collecLg='{$sDefaultLg}' AND collecEnsIdx=" . $iEnsIdx . "
        ORDER BY collecNom ASC
    ");
                                $oDb->queryToOption($iCollectIdx);
                                ?>
                            </select>
                            <select name="selFoncIdx">
                                <option value="0">Toutes les fonctions</option>
                                <?php
                                $oDb->query("
        SELECT f.foncIdx AS idx, foncNom AS lib
        FROM {$oDb->dbPrefixe}fonction AS f
        INNER JOIN {$oDb->dbPrefixe}fonctioncontent AS fc ON f.foncIdx = fc.foncIdx
        WHERE
            foncLg='{$sDefaultLg}'
            AND foncEnsIdx=" . $iEnsIdx . "
            AND foncTypeIdx=1
        ORDER BY foncNom ASC
    ");
                                $oDb->queryToOption($iFoncIdx);
                                ?>
                            </select>
                            <input type="submit" name="rechercher" value="Rechercher" />
                        </td>
                    </tr>
                </table>
    </form>
    <form name="frmsave" method="post" action="tag_images_save.php" style="margin:0;padding:0">
    <?php
    if ($numRowsSelected > 0)
    {
        ?>
        <br />
        <h1>Images actuellement s&eacute;lectionn&eacute;es</h1>
        <table border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
            <tr>
                <td class="titre">Image</td>
                <td class="titre">Nom<a href="#" onClick="changeOrder('Nom','ASC');"><img src="<?php echo $AdminVirtuel?>image/asc<?php echo ($_SESSION["sess_sortf"]=="Nom" && $_SESSION["sess_sortt"] =="ASC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a><a href="#" onClick="changeOrder('Nom','DESC');"><img src="<?php echo $AdminVirtuel?>image/desc<?php echo ($_SESSION["sess_sortf"]=="Nom" && $_SESSION["sess_sortt"] =="DESC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a></td>
                <td class="titre">Date<a href="#" onClick="changeOrder('Date','ASC');"><img src="<?php echo $AdminVirtuel?>image/asc<?php echo ($_SESSION["sess_sortf"]=="Date" && $_SESSION["sess_sortt"] =="ASC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a><a href="#" onClick="changeOrder('Date','DESC');"><img src="<?php echo $AdminVirtuel?>image/desc<?php echo ($_SESSION["sess_sortf"]=="Date" && $_SESSION["sess_sortt"] =="DESC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a></td>
                <td class="titre">Collection</td>
                <td class="titre">Fonction</td>
                <td class="titre">S&eacute;lectionner</td>
                <td class="titre">Principale</td>
            </tr>
            <?php
            //Affichage de la liste des visuels
            $couleur="a";    //Gestion des couleurs
            while ($data = $oDb->next_record($mResEltSelected))
            {
                $couleur = ($couleur=="a")? "b" : "a";
                ?>
                <tr>
                    <td class="ligne<?php echo $couleur?>"><img src="<?php echo $PathVirtuel ?>image.php?idx=<?php echo $data->eltIdx;?>&num=1&type=vignette" width="54" height="54"></td>
                    <td class="ligne<?php echo $couleur?>"><?php echo $data->eltModeleNom;?></td>
                    <td class="ligne<?php echo $couleur?>"><?php echo $data->eltSemestre ." / " . $data->eltAnnee;?></td>
                    <td class="ligne<?php echo $couleur?>"><?php echo tohtml($data->collecNom);?>&nbsp;</td>
                    <td class="ligne<?php echo $couleur?>"><?php echo tohtml($data->foncNom);?>&nbsp;</td>
                    <td class="ligne<?php echo $couleur?>" align="center">
                        <?php
                        $sChecked = ' checked="checked"';
                        $sSelected = '';
                        if ($data->elxfIsPrincipal)
                        {
                            $sSelected = ' checked="checked"';
                        }
                        ?>
                        <input type="checkbox" name="element[<?php echo $data->eltIdx; ?>]" value="<?php echo $data->eltIdx; ?>" <?php echo $sChecked; ?> />
                    </td>
                    <td class="ligne<?php echo $couleur?>" align="center">
                        <input type="radio" name="elxfIsPrincipal" value="<?php echo $data->eltIdx; ?>" <?php echo $sSelected; ?> />
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>
    <br />
    <h1>S&eacute;lectionner les images &agrave; associer au code XFurn "<?php echo $sCodeXfurn; ?>" :</h1>
    <table border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
        <tr>
            <td valign="baseline" class="titre">Page
                <?php
                if ($nbPage <= 1)
                {
                    echo ": $nbPage";
                }
                else
                {
                    echo '<select name="page" onChange="document.getElementById(\'hid_page\').value=this.value;document.frm.submit()">';
                    for ($i= 1; $i<=$nbPage; $i++)
                    {
                        $sSelected = '';
                        if ($i==$_SESSION["sess_page"])
                        {
                            $sSelected = ' selected="selected"';
                        }
                        echo '<option value="' . $i . '"' . $sSelected . '>'. $i . '</option>';
                    }
                    echo '</select>';
                }
                ?>
            </td>
            <td valign="baseline" class="titre">Nombre de <?php echo $titreElement;?>s : <?php echo  $numRows?></td>
            <td valign="baseline" class="titre">Lignes par page
                <select name="rows" onChange="document.getElementById('hid_rows').value=this.value;document.frm.submit()">
                    <option value="5" <?php echo ($_SESSION["sess_rows"]==5)?" selected":"";?>>5</option>
                    <option value="10" <?php echo ($_SESSION["sess_rows"]==10)?" selected":"";?>>10</option>
                    <option value="25" <?php echo ($_SESSION["sess_rows"]==25)?" selected":"";?>>25</option>
                    <option value="50" <?php echo ($_SESSION["sess_rows"]==50)?" selected":"";?>>50</option>
                    <option value="100" <?php echo ($_SESSION["sess_rows"]==100)?" selected":"";?>>100</option>
                </select>
            </td>
        </tr>
    </table>
    <br />
    <input type="hidden" name="sortf" value="<?php echo $_SESSION["sess_sortf"]?>">
    <input type="hidden" name="sortt" value="<?php echo $_SESSION["sess_sortt"]?>">
    <input type="hidden" name="code" value="<?php echo $sCodeXfurn; ?>">
    <input type="hidden" name="brand" value="<?php echo $iEnsIdx; ?>">
    <?php
    if ($numRows > 0)
    {
        ?>
        <table border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
            <tr>
                <td class="titre">Image</td>
                <td class="titre">Nom<a href="#" onClick="changeOrder('Nom','ASC');"><img src="<?php echo $AdminVirtuel?>image/asc<?php echo ($_SESSION["sess_sortf"]=="Nom" && $_SESSION["sess_sortt"] =="ASC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a><a href="#" onClick="changeOrder('Nom','DESC');"><img src="<?php echo $AdminVirtuel?>image/desc<?php echo ($_SESSION["sess_sortf"]=="Nom" && $_SESSION["sess_sortt"] =="DESC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a></td>
                <td class="titre">Date<a href="#" onClick="changeOrder('Date','ASC');"><img src="<?php echo $AdminVirtuel?>image/asc<?php echo ($_SESSION["sess_sortf"]=="Date" && $_SESSION["sess_sortt"] =="ASC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a><a href="#" onClick="changeOrder('Date','DESC');"><img src="<?php echo $AdminVirtuel?>image/desc<?php echo ($_SESSION["sess_sortf"]=="Date" && $_SESSION["sess_sortt"] =="DESC")?"_on":""?>.gif" width="16" height="16" border="0" align="absmiddle"></a></td>
                <td class="titre">Collection</td>
                <td class="titre">Fonction</td>
                <td class="titre">S&eacute;lectionner</td>
                <td class="titre">Principale</td>
            </tr>
            <?php
            //Affichage de la liste des visuels
            $couleur="a";    //Gestion des couleurs
            $rowCount=($_SESSION["sess_rows"] * ($_SESSION["sess_page"]-1) + 1);    //Ligne de d&eacute;but
            $oDb->seek($rowCount, $mResElt);    //D&eacute;placement sur le bon enreg
            while (($data = $oDb->next_record($mResElt)) && ($rowCount <= ($_SESSION["sess_page"] * $_SESSION["sess_rows"])))
            {
                $rowCount++;
                $couleur = ($couleur=="a")? "b" : "a";
                ?>
                <tr>
                    <td class="ligne<?php echo $couleur?>"><img src="<?php echo $PathVirtuel ?>image.php?idx=<?php echo $data->eltIdx;?>&num=1&type=vignette" width="54" height="54"></td>
                    <td class="ligne<?php echo $couleur?>"><?php echo $data->eltModeleNom;?></td>
                    <td class="ligne<?php echo $couleur?>"><?php echo $data->eltSemestre ." / " . $data->eltAnnee;?></td>
                    <td class="ligne<?php echo $couleur?>"><?php echo tohtml($data->collecNom);?>&nbsp;</td>
                    <td class="ligne<?php echo $couleur?>"><?php echo tohtml($data->foncNom);?>&nbsp;</td>
                    <td class="ligne<?php echo $couleur?>" align="center">
                        <?php
                        $sChecked = '';
                        ?>
                        <input type="checkbox" name="element[<?php echo $data->eltIdx; ?>]" value="<?php echo $data->eltIdx; ?>" <?php echo $sChecked; ?> />
                    </td>
                    <td class="ligne<?php echo $couleur?>" align="center">
                        <input type="radio" name="elxfIsPrincipal" value="<?php echo $data->eltIdx; ?>" />
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <p>
            <input type="submit" value="Valider les modifications" />
        </p>
			</form>
<?php
    } //$if ($rowCount >=0 ...
}
else
{
    echo '<td valign="top" width=100%>';
    echo '<strong>ERROR</strong><ul>';
    echo $sErrorMessage;
    echo '</ul></td>';
}
?>
     </td>
<?php
require_once(dirname(__FILE__) . "/../admin/include/footer.php");

?>