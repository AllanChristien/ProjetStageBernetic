<?php
// SCRIPT POUR INSERSION DANS LES PAGES DU SITE
if (isset($_POST['recherche'])) //si on a validé le formulaire
{
if ($_POST['recherche'] != NULL) //si on a validé le formulaire
{

$recherche = mysql_real_escape_string(htmlspecialchars($_POST['recherche'])); //sécurisation des variables
$mode = mysql_real_escape_string(htmlspecialchars($_POST['mode']));
if ($mode == "tous_les_mots")
{
$and_ou_or = 'AND'; //on utilisera AND dans la boucle
$mots = explode(" ", $recherche); //séparation des mots
$nombre_mots = count ($mots); //comptage du nombre de mots
$valeur_requete1 = ''; //Une requete pour chaque champ ou on veut faire une recherche
$valeur_requete2 = '';
$valeur_requete3 = '';
for($nombre_mots_boucle = 0; $nombre_mots_boucle < $nombre_mots; $nombre_mots_boucle++) //tant que le nombre de mots de la recherche est supérieur a celui de la boucle, on continue en incrémentant a chaque fois le nombre de mots
{
$valeur_requete1 .= '' . $and_ou_or . ' id LIKE \'%' . $mots[$nombre_mots_boucle] . '%\' ' ; //modification de la variable 
$valeur_requete2 .= '' . $and_ou_or . ' nomfichier LIKE \'%' . $mots[$nombre_mots_boucle] . '%\'' ; //modification de la variable 
$valeur_requete3 .= '' . $and_ou_or . ' titre LIKE \'%' . $mots[$nombre_mots_boucle] . '%\'' ; //modification de la variable 
}
$valeur_requete1 = ltrim($valeur_requete1,$and_ou_or); //suppression de AND ou de OR au début de la boucle
$valeur_requete2 = ltrim($valeur_requete2,$and_ou_or); //suppression de AND ou de OR au début de la boucle
$valeur_requete3 = ltrim($valeur_requete3,$and_ou_or); //suppression de AND ou de OR au début de la boucle

$_SESSION['MONSEARCH1']	= $recherche ;

	
$exportrecherche = "SELECT * FROM documents
WHERE ($valeur_requete1 OR $valeur_requete2 OR $valeur_requete3) ORDER BY id LIMIT 0,100 ";
$_SESSION['exportrecherche'] = $exportrecherche ;
$selection_recherche = mysql_query("SELECT * FROM documents
WHERE ($valeur_requete1 OR $valeur_requete2 OR $valeur_requete3) ORDER BY id LIMIT 0,100 ") or die(mysql_error()); //requete contenant le résultat de la boucle
}
$nombre_resultats = mysql_num_rows($selection_recherche); //comptage du nombre d'entrées sélectionnées par la recherche
if ($nombre_resultats == 0) //s'il n'y a pas de résultat
{ ?>
<br /><br />

<div align = "center" class ="soustitre">Votre recherche n'a donn&eacute; aucun r&eacute;sultat.<br />
<a href="<?php echo $_SERVER['PHP_self'] ;?>">Recommencer</a>
</div><br /><br />

<?php 
}
else //il y a au moins un résultat
{
 ?>
<table width="1000" class="cadres" border="0" cellpadding="0" cellspacing="0" bordercolor="#333333">
  <tr>
    <td align="center" valign="middle"><table width="60%" border="0" cellpadding="0" cellspacing="0">
      <tbody>
        <tr><br>
          <td height="30" align="center" valign="middle" bgcolor="#FFFFFF" class="TextPlanNoir1"> Vous avez recherch&eacute; : <strong><?php echo $recherche ; ?></strong> - 
        <?php echo '(Nombre de r&eacute;sultats :<strong>';?>
      <?php  echo $nombre_resultats."</strong>)" ; //nombre de résultats ?>
            <?php if ($nombre_resultats == 100) { echo "(Affichage limit&eacute; &agrave; 100 lignes) <br><br> "  ; }  ?>
            </td>
          </tr>
        <tr>
          <td height="30" align="center" valign="middle" bgcolor="#8D8D8D"><a href="<?php echo $_SERVER['PHP_self'] ;?>" class="blanc3">Effectuer une nouvelle recherche</a></td>
        </tr>
        </tbody>
    </table>     
      <br />
      <span class="TexteBlanc"><br />
      </span></td>
  </tr>
</table>
<br/>
<table width="1064" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td width="77" align="center" valign="middle" bgcolor="#BBB8B8"><span class="TextPlanNoir1">Classement du Doc</span></td>
    <td width="77" height="30" align="center" valign="middle" bgcolor="#BBB8B8"><span class="TextPlanNoir1">Num&eacute;ro du document</span><br /></td>
    <td width="176" height="30"  align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Cr&eacute;&eacute; par</td>
    <td width="506" align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Titre du document</td>
    <td width="90" height="30" align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Type</td>
    <td width="75" align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Service</td>
    <td width="39" height="30" align="center" valign="middle" bgcolor="#BBB8B8">&nbsp;</td>
  </tr>
</table>
<br />
<?php 
while($resultats = mysql_fetch_array($selection_recherche) ) //boucle affichant les résultats
{
$ID = $resultats["id"] ;     
$NomPosteur = $resultats['posteur'] ;
$DateJour = $resultats['datecreation'] ;
$DateValid = $resultats['dateperemption'] ;
$MaDateJour = date('Y-m-d', strtotime($DateJour)) ;
$MaDateValid = date('Y-m-d', strtotime($DateValid)) ;
if ($MaDateValid == "1970-01-01"){ $MaDateValid = "Non pr&eacute;cis&eacute;e" ; }    
$Type = $resultats['typedoc'] ;    
$Service = $resultats['servicedoc'] ;
$Titre = $resultats['titre'] ;
$Destinataires = $resultats['destinataires'] ; 
$Etat = $resultats['etatdoc'];
if ($Etat == "wait"){ $MonBackGround = "#FF6600"; $ClassDuDoc = "<img src='attente.png' alt='' width='40'/>" ;}
    elseif ($Etat == "depass") { $MonBackGround = "red"; $ClassDuDoc = "<img src='obso.png' alt='' width='40'/>" ;}
    elseif ($Etat == "on"){ $MonBackGround = "#286800"; $ClassDuDoc = "<img src='valid.png' alt='' width='40'/>" ;}
    else {  $MonBackGround = "#8C8C8C"; $ClassDuDoc = "" ;}
    ?>
<table width="1064" border="0" cellpadding="0" cellspacing="3" bordercolor="#FFFFFF">
					   <tr>
					     <td width="77" align="center" valign="middle" bgcolor="<?php echo $MonBackGround ; ?>" class="soustitrenoir"><?php echo $ClassDuDoc ;?></td>
                      <td width="77" align="center" valign="middle" bgcolor="#8C8C8C" class="soustitrenoir"><?php echo $ID ;?><br /></td>
                      <td width="175"  align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><?php echo $NomPosteur ; ?></td>
                      <td width="436" align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                            <tr>
                              <td class="soustitrenoir"><?php if (!empty($NomFichier)){ echo "<a href='../../../Tpfolio/$NomFichier'  target='_blank'>".$Titre."</a>" ;   } else { echo $Titre ; } ?></td>
                            </tr>
                            <tr>
                              <td align="center" valign="middle">Affichage du : <?php echo $MaDateJour ; ?> jusqu'au : <?php echo $MaDateValid; ?></td>
                            </tr>
                          </tbody>
                      </table></td>
                      <td width="70" align="center" valign="middle" class="TextPlanBlanc">
                     <input type="button" value="Modifier" onclick="window.location.href='<?php echo 'pfattente.php?modifT='.$ID.'' ; ?>';">  
					  </td>
                      <td width="89" align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><?php echo $Type;?></td>
                      <td width="76" align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><?php echo $Service;?></td>
                      <td width="37" align="center" valign="middle" bgcolor="#8C8C8C"><a href="<?php echo 'pfattente.php?supprimer=' . $ID . '&amp;Fichier='.$NomFichier.''; ?>" onclick="return confirm_del()"><img src="supproff.png" alt="supprimer" name="suprimer" width="25" height="25" border="0" id="suprimer" /></a></td>
                    </tr>
</table>
<?php } ?>
<?php } ?><br />
<br />
<?php
 }}
 else //si on n'a pas validé le formulaire, on l'affiche
{ ?>
<form method="post" action="<?php echo @$_SERVER['self'] ;?>">
<div id="recherche" align="center">
<span class="TextPlanBlanc">Rechercher : </span><input name="recherche" type="text" size="40" />&nbsp;&nbsp;
<input type="submit" value="Rechercher" id="rechercher" name="rechercher" />
<input name="search" type="hidden" id="search" value="search" />
<input name="affmini" type="hidden" id="affmini" value="<?php echo $affminiok ; ?>" />
<input name="mode" type="hidden" id="mode" value="tous_les_mots" />
</div>
</form>
 <?php } ?>