<?php
include (dirname(__FILE__) . "/../include/global.php");

$sPathToCsv = $g_sImportmediaCSVFolder . $g_sImportmediaCSVFilename;
//config
$bDebug = retrieveVariable('debug', 'boolean', false);
$iLinesToSkip = 2;
$iFileColumn = 18;
$iFileColumnCount = 5;
$cSeparator = ';';
$cGroupSeparator = '|';
$cTextEnclosure = '"';

$sFileSuffix = date('YmdHis');
$sFileName = 'import_media.csv.' . $sFileSuffix . '.report';
$sReport = '';

$sText = 'import of file [' . $sPathToCsv . '] start at ' . date('Y-m-d H:i:s');
echo $sText . '<br />';
$sReport .= $sText . PHP_EOL;

//load csv file
if (($rFile = fopen($sPathToCsv, "r")) !== FALSE)
{
    $sText = 'file [' . $sPathToCsv . '] found ... processing';
    echo $sText . '<br />';
    $sReport .= $sText . PHP_EOL;
	//skipping first lines
	for($i = 0; $i <$iLinesToSkip; $i++)
	{
		fgetcsv($rFile);
	}
}
else
{
    $sText = '! ERROR : file [' . $sPathToCsv . '] not found';
    echo $sText . '<br />';
    $sReport .= $sText . PHP_EOL;
    file_put_contents($g_sImportmediaCSVFolder . $sFileName, $sReport);
    exit(0);
}

$iCurLine = 0;
while (($curLine = fgetcsv($rFile, 10000, $cSeparator, $cTextEnclosure)) !== FALSE)
{
    //$curLine = explode($cSeparator, $sCurLine);
	$iCurLine++;
	
	$oElement = new ElementModel();
	$oElementContent = new ElementContentModel();
	
	$oElement->setEltDeplace(0);
	$oElement->setEltOnline(1);
	$oElement->setEltMediaIdx(null);
	$oElement->setEltAnnee(null);
	
	$oElement->setEltEnsIdx($curLine[1]);
	$oElement->setEltCateIdx($curLine[2]);
	
	if(strtolower($curLine[3]) != 'null')
	{
	    $oElement->setEltMediaIdx($curLine[3]);
	}
	else
	{
	    $oElement->setEltMediaIdx(null);
	}
	$oElement->setEltModele($curLine[4]);
	
	if(strtolower($curLine[5]) != 'null')
	{
	    $oElement->setEltFoncIdx($curLine[5]);
	}
	else
	{
	    $oElement->setEltFoncIdx(null);
	}
	
	if(strtolower($curLine[6]) != 'null')
	{
	    $oElement->setEltPaysIdx($curLine[6]);
	}
	else
	{
	    $oElement->setEltPaysIdx(null);
	}

	if(strlen($curLine[7])>0)
	{
	    $oElement->setEltAnnee($curLine[7]);
	}
	else
	{
	    $oElement->setEltAnnee(null);
	}
	$oElement->setEltSemestre($curLine[8]);
	
	if(strtolower($curLine[9]) != 'null')
	{
	    $oElement->setEltCollecIdx($curLine[9]);
	}
	else
	{
	    $oElement->setEltCollecIdx(null);
	}

	if(strtolower($curLine[10]) != 'null')
	{
	    $oElement->setEltDate($curLine[10]);
	}
	else
	{
	    $oElement->setEltDate(null);
	}
	$oElement->setEltIdx(ElementDAO::getInstance()->insert($oElement));
	if (!is_numeric($oElement->getEltIdx()))
	{
        $sText = '! Warning on line [' . $iCurLine . '] Unable to insert element. please check datas : [' . $sCurLine . ']';
        echo $sText . '<br />';
        $sReport .= $sText . PHP_EOL;
	    continue;
	}
	
	$oElementContent->setEltIdx($oElement->getEltIdx());
	$oElementContent->setEltCateIdx($oElement->getEltCateIdx());
	$oElementContent->setEltLg('fr');
	
	$oElementContent->setEltNom($curLine[11]);
	$oElementContent->setEltSousTitre($curLine[12]);
	$oElementContent->setEltTexte($curLine[13]);
	
	ElementContentDAO::getInstance()->insert($oElementContent);
	
	
	$oElementContent->setEltLg('en');
	$oElementContent->setEltNom($curLine[14]);
	$oElementContent->setEltSousTitre($curLine[15]);
	$oElementContent->setEltTexte($curLine[16]);
	ElementContentDAO::getInstance()->insert($oElementContent);
	
	
	$sXFurnQuery = 'INSERT INTO ' . DBManager::getInstance()->prefix('elementxfurn') . ' (elxfEltIdx, elxfCodeXfurn, elxfIsPrincipal) VALUES';
	$sXFurnQuery .= '(' . $oElement->getEltIdx() . ', ';
	$sXFurnQuery .= DBManager::getInstance()->quote($curLine[17]) . ', ';
	$sXFurnQuery .= '1)';
    if($bDebug)
    {
        echo 'xfurn query : [' . $sXFurnQuery . ']' . PHP_EOL;
    }

	DBManager::getInstance()->insert($sXFurnQuery);

	$iCurFileColumn = $iFileColumn;
	//gets category as we will need its name to move the media in the correct folder
	//the main purpose is to insert the elements, if the eltcateidx is not valid, it's not our problem.
	//but if we can't get the category name, we can't copy the file, so the test is done there
    $oCategorie = CategorieDAO::getInstance()->findByCateIdx($oElement->getEltCateIdx());
    if($oCategorie != null)
    {
    	while(isset($curLine[$iCurFileColumn]) && strlen($curLine[$iCurFileColumn]) > 0)
    	{
    	    $bCurFileExists = false;
    	    if(isset($curLine[$iCurFileColumn + 1 ]) && strlen($curLine[$iCurFileColumn + 1 ]) > 0)
    	    {
    	        if(file_exists($g_sImportmediaCSVFolder . $curLine[$iCurFileColumn + 1]))
    	        {
    	            $bCurFileExists = true;
    	        }
    	    }
    	    if($bCurFileExists)
    	    {
    	        $sFileName = $curLine[$iCurFileColumn + 1 ];
    	        //get destination folder
    	        $sPathUpload = $UploadPhysique . $asEnseigne[$oElement->getEltEnsIdx()] . '/' . $oCategorie->getCateDossierNom() . '/';
    	        //gets uniquename
    	        $sFileName = uniqueNameForMoveInDir($sPathUpload, $sFileName);
    		    $oFile = new FichierModel();
    		    $oFile->setEltIdx($oElement->getEltIdx());
    		    $oFile->setFicCateIdx($oElement->getEltCateIdx());
    		    $oFile->setFicEnsIdx($oElement->getEltEnsIdx());
    		    $oFile->setFicMime('any');
    		    $oFile->setFicLinked(0);
    		    $oFile->setFicFoncIdx($oElement->getEltFoncIdx());
    		    
    		    $oFile->setFicNum($curLine[$iCurFileColumn]);
    		    $oFile->setFicNom($sFileName);
        		
        		if(strlen($curLine[$iCurFileColumn + 2])>0)
        		{
        		    $oFile->setFicMime($curLine[$iCurFileColumn + 2]);
        		}
    		    $oFile->setFicInterne($curLine[$iCurFileColumn + 3]);
    		    $oFile->setFicNomModele($curLine[$iCurFileColumn + 4]);
    		    
    		    if($bDebug)
    		    {
    		        echo 'Trying to move file from [' . $g_sImportmediaCSVFolder . $curLine[$iCurFileColumn + 1] . '] to [' . $sPathUpload . $sFileName . ']' . PHP_EOL;
    		    }
    		    //try to move file before insert in db
    		    if (rename($g_sImportmediaCSVFolder . $curLine[$iCurFileColumn + 1], $sPathUpload . $sFileName))
    		    {
    		        FichierDAO::getInstance()->insert($oFile);
    		    }
    		    else
    		    {
                    $sText = '! Warning : Unable to move file from [' . $g_sImportmediaCSVFolder . $curLine[$iCurFileColumn + 1] . '] to [' . $sPathUpload . $sFileName . ']';
                    echo $sText . '<br />';
                    $sReport .= $sText . PHP_EOL;
                    $sText = 'File not inserted in database';
                    echo $sText . '<br />';
                    $sReport .= $sText . PHP_EOL;
    		    }
    	    }
    	    else
    	    {
                $sText = '! Warning : File #[' . $curLine[$iCurFileColumn] . '] : ' . $curLine[$iCurFileColumn + 1] . ' does not exist in folder [' . $g_sImportmediaCSVFolder . '] on line : [' . $iCurLine . ']';
                echo $sText . '<br />';
                $sReport .= $sText . PHP_EOL;
    	    }
    	    $iCurFileColumn += $iFileColumnCount;
    	}
    }
    else
    {
        $sText = '! Warning : line [' . $iCurLine . '], category [' . $oElement->getEltCateIdx() . '] not found, associated files not inserted';
        echo $sText . '<br />';
        $sReport .= $sText . PHP_EOL;
    }
	$aiGroups = explode($cGroupSeparator, $curLine[0]);
	if(is_array($aiGroups))
	{
	    
		foreach ($aiGroups as $iCurGroup)
		{
		    $sQuery = 'INSERT INTO ' . DBManager::getInstance()->prefix('elementgroupe') . ' (eltIdx, grpIdx) VALUES (' . $oElement->getEltIdx() . ', ' . $iCurGroup . ')';
		    if($bDebug)
		    {
		        echo 'group query : [' . $sQuery . ']' . PHP_EOL;
		    }
		    DBManager::getInstance()->insert($sQuery);
		}
	}
	else
	{
	    $sReport .= '! Warning : line [' . $iCurLine . '] no group defined, no one will be able to access it';
	}
}

fclose($rFile);
//renames csv file, so we can't process it many_times
rename($sPathToCsv, $sPathToCsv . '.' . $sFileSuffix );

$sText = 'END : ' . $iCurLine . ' lines processed';
echo $sText . '<br />';
$sReport .= $sText . PHP_EOL;

file_put_contents($g_sImportmediaCSVFolder . $sFileName, $sReport);