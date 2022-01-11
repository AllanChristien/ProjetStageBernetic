<?php
$serveur='192.168.0.161';	//Nom du serveur (en local : locahost)
$user='bernard';	//  bernard Nom de l'utilisateur (en local : root)
$mdp='berclem0511';	// bernard Mot de passe (en local : aucun)
$base='IntranetBE';	//Nom de la base de données
// On se connecte d'abord a MySQL : 
$con = mysqli_connect('localhost', 'root', '') or die(mysqli_error($con)); 
mysqli_select_db($con, 'intranetBE');
ini_set( 'default_charset', 'ISO-8859-1' );	

/////////////////////////////////////////////////////////////////
//CONTROLE AVANT CHARGEMENT DES PAGES 
//LE MEME CODE EST INSERE DANS LA PARTIE PUBLIQUE ET LA PARTIE ADMIN
//LA PREMIERE PAGE EXECUTEE FAIT LA MISE A JOUR QUOTIDIENNE POUR TOUT LE MONDE
/////////////////////////////////////////////////////////////////
	
//////////////////////////////////////////////////////////////////
// CONTROLE DES DATES DE FIN DES DOCUMENTS	
// A chaque chargement de la page,
// on vérifie la date de fin de CHAQUE Document
// et on modifie automatiquement son état si la date est dépassée.
///////////////////////////////////////////////////////////	
$requete = mysqli_query($con, "SELECT id FROM documents");
while($data = mysqli_fetch_assoc($requete))
foreach($data as $NouvID)
{
$requete2 = mysqli_query($con, "SELECT * FROM documents WHERE id = $NouvID");
while($data2 = mysqli_fetch_assoc($requete2))
{
$now = date("Y-n-d");
$DateValid = $data2['dateperemption'] ;
$Date = $data2['dateproduction'] ;
$ID = $data2['id'] ;	
$NomFichier = $data2['nomfichier']; 
}

if (strtotime($DateValid) < (strtotime($now)) AND (!empty(strtotime($DateValid)))){
mysqli_query("UPDATE documents SET etatdoc ='depass' WHERE id ='$ID'")or die(mysqli_error());
}
if ((!empty($NomFichier)) AND (strtotime($Date)) <= (strtotime($now)) AND (strtotime($DateValid)) > (strtotime($now)) OR (empty(strtotime($DateValid)))){
mysqli_query($con, "UPDATE documents SET etatdoc ='on' WHERE id ='$ID'")or die(mysqli_error()); 
} 
if ( (empty($NomFichier)) OR (strtotime($Date)) > (strtotime($now)) ){
mysqli_query($con, "UPDATE documents SET etatdoc ='wait' WHERE id ='$ID'"); 
} 
}
////////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////////	
// FIN DE LA ZONE DE CONTROLE AVANT CHARGEMENT DES PAGES
////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////// ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Administration PortFolio</title>
<link href="stylesheet.css" rel="stylesheet" type="text/css" />	
<link href="../../../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css" />
<link href="../../../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css" />
<link href="../../../jQueryAssets/jquery.ui.datepicker.min.css" rel="stylesheet" type="text/css" />
<script src="../../../jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="../../../jQueryAssets/jquery.ui-1.10.4.datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
function confirm_del() {
var agree=confirm("Cette operation est irreversible. Etes-vous certain de vouloir supprimer ces informations ?");
        if (agree) {
                return true ;
        } else {
                return false ;
        }
}
	</script>	


</head>
<?php
	
$FichierASupp = $_GET['Fichier'] ;
$ID = $_GET['supprimer']; 	
	
	// On supprime une ligne
if (isset($_GET['supprimer'])) // Si on demande de supprimer un message 
{ 
//Pour effacer limage
mysqli_query('DELETE FROM documents WHERE id=' . $_GET['supprimer'])or die(mysqli_error());

$dossier_traite="../../../Tpfolio/";
$fichier_traite="../../../Tpfolio/".$FichierASupp."";    

$repertoire = opendir($dossier_traite); //on définit le répertoire dans lequel on souhaite travailler

if (file_exists($fichier_traite)){
unlink($fichier_traite);    
//rmdir($fichier_traite);
closedir($repertoire); //Ne pas oublier de fermer le dossier !EN DEHORS de la boucle ! Ce qui évitera a PHP bcp de calculs et des pbs liés a l'ouverture du dossier
}
}
	
//////////////////////////////	
//SI ON MODIFIE UNE INFO AVEC AJOUT DE FICHIER:
////////////////////////////////
///////////////////////////////

	
if (isset($_POST['valider2'])) // Si les variables existent 
{
if (isset($_POST['TitreM'])) // Si les variables existent 
{ 
if ($_POST['TitreM'] != NULL) // Si on a quelque chose a enregistrer 
{
//$PostAvatar = $_POST['Avatar'];
$IDM = $_POST['IDM'];	
$NomPosteurM = $_POST['NomPosteurM'];
$DateM = $_POST['DateM'];
$DateValidM = $_POST['DateValidM'];		
$NomFichierM = basename($_FILES['avatar']['name']);
$MonAncienFichierM = $_POST['MonAncienFichierM']  ;     
$VersionM = $_POST['VersionM'];
$TypeM = $_POST['TypeM'];
$ServiceM = $_POST['ServiceM'];
$TitreM =  $_POST['TitreM'];	
$DestinatairesM = $_POST['DestinatairesM'];
$DateDuJour = date("Y-n-d");
    if ($DateValidM < $DateDuJour) { $etat = 'depass' ; }
    else if ($DateM <= $DateDuJour) { $etat = 'on' ; }
    else if ($DateM > $DateDuJour) { $etat = "wait" ; }
    else  { $etat = 'off' ; }

if (empty($NomFichierM) AND (empty($MonAncienFichierM))) { ?>
<br>
<table width="600" border="16" align="center" cellpadding="12" cellspacing="6" class="soustitrenoir">
      <tbody>
        <tr>
          <td align="center" valign="middle" bgcolor="#FFAE00">Attention !<br>
            Il faut imp&eacute;rativement 
          t&eacute;l&eacute;charger un fichier<br>
          pour finaliser l'op&eacute;ration.</td>
        </tr>
      </tbody>
</table>
<?php } else if (empty($NomFichierM) AND (!empty($MonAncienFichierM))) {

// SI IL Y A PAS DE FICHIER A POSTER MAIS QU'UN FICHIER EST DEJA PRESENT POUR CET ID...
mysqli_query("UPDATE documents SET posteur='$NomPosteurM', dateproduction='$DateM', dateperemption='$DateValidM', version='$VersionM', typedoc='$TypeM', servicedoc='$ServiceM', titre='$TitreM', destinataires='$DestinatairesM', etatdoc='$etat' WHERE id='$IDM'") or die(mysqli_error());
?><script type="text/JavaScript">
<!--
function redirection(page)
  {window.location=page;}
setTimeout('redirection("index.php")',0);
//--> 
</script>  
<?php          
} else if (!empty($NomFichierM)) {     
$dossier = '../../../Tpfolio/';
$fichier = basename($_FILES['avatar']['name']);
$taille_maxi = 10000000;
$taille = filesize($_FILES['avatar']['tmp_name']);
$extensions = array('', '.png', '.jpg', '.jpeg', '.JPG', '.doc', '.docx', '.xls', '.XLS', '.xlsx', '.XLSX', '.ods', '.odt', '.txt', '.pdf', '.PDF');
$extension = strrchr($_FILES['avatar']['name'], '.'); 
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = 
	 '<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="red"><tbody><tr>
      <td width="500" height="40" align="center" valign="middle" class="soustitre">
	  Vous ne pouvez pas t&eacute;l&eacute;charger ce type de fichier</td></tr></tbody></table>';
		 
	
}
if($taille>$taille_maxi)
{
     $erreur = 
	'<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="red"><tbody><tr>
      <td width="500" height="40" align="center" valign="middle" class="soustitre">
	  Votre fichier d&eacute;passe le poids autoris&eacute;s...</td></tr></tbody></table>';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
$Now = date("dmy");    
    $fichier = $IDM."_".$Now."_".$fichier ;
     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          echo '<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="green"><tbody><tr>
      <td width="500" height="40" align="center" valign="middle" class="soustitre">
	  Votre fichier a &eacute;t&eacute; t&eacutel&eacutecharg&eacute</td></tr></tbody></table>'; 
			  
			  'Upload effectué avec succès !';
	 
		 		// Alors on modifie l'actu correspondante 
mysqli_query("UPDATE documents SET posteur='$NomPosteurM', dateproduction='$DateM', dateperemption='$DateValidM', nomfichier='$fichier', version='$VersionM', typedoc='$TypeM', servicedoc='$ServiceM', titre='$TitreM', destinataires='$DestinatairesM', etatdoc='$etat' WHERE id='$IDM'") or die(mysqli_error());
 
?><script type="text/JavaScript">
<!--
function redirection(page)
  {window.location=page;}
setTimeout('redirection("index.php")',0);
//--> 
</script>  
<?php 
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo 'Echec de l\'upload !';
     }
}
else
{
echo $erreur;
}
} 
} 
}
}
				

    
    
    ////////////////////////////////////
    /////AJOUT D'UN NOUVEAU DOCUMENT////
    ////////////////////////////////////
    
    
    
	
	//On ajoute un nouvelle info
if (isset($_POST['NomPosteur']) AND isset($_POST['Titre'])) // Si les variables existent 
{ 
    if ($_POST['NomPosteur'] != NULL  AND $_POST['Titre'] != NULL) // Si on a quelque chose à enregistrer 
    {

$NomPosteur = $_POST['NomPosteur'];
$Date = $_POST['Date'];
$DateValid = $_POST['DateValid'];		
$NomFichier = $_POST['NomFichier'];
$Version = $_POST['Version'];
$Type = $_POST['Type'];
$Service = $_POST['Service'];
$Titre =  $_POST['Titre'];	
$Destinataires = $_POST['Destinataires'];
	
		
mysqli_query("INSERT INTO portfolio VALUES('', '$NomPosteur', '$Date', '$DateValid', '$NomFichier', '$Version', '$Type', '$Service', '$Titre', '$Destinataires', 'wait')") or die(mysqli_error());   
}}
?>
<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="85%" align="left" valign="middle"><span class="titre"><a href="../../index.php"><img src="../../PointRetour.png" width="40" height="40" alt=""/></a> Administration &gt;</span><span class="petittitre2"> PortFolio <a href="index.php" class="blanc4">&gt; accueil</a></span></td>
       </tr>
      <tr>
        <td height="60" colspan="2" align="center" valign="middle" >
			<br>
     

            
           
<?php
/////////////////////////////////////////////////////////////////////////////////
///////////////////////////AFFICHAGE DE LA FENETRE DE MODIFICATION D'UN DOCUMENT 
/////////////////////////////////////////////////////////////////////////////////            
            
            // On affiche la fen&ecirc;tre modifier 
if (isset($_GET['modifT'])) // Si on demande de modifier 
{
$modifT = $_GET['modifT'];  
$don1 = "SELECT * FROM documen ts WHERE id='$modifT'"; 
$rep1 = mysqli_query($don1)or die(mysqli_error());
while ($donall = mysqli_fetch_array($rep1)){ 

$ID = $donall['ID'] ;	
$modif_NomPosteur = $donall['NomPosteur'];
$modif_Date = $donall['Date'];
$modif_DateValid = $donall['DateValid'];
$modif_NomFichier = $donall['NomFichier'];
$modif_Version = $donall['Version'];	
$modif_Type = $donall['Type'];
$modif_Service = $donall['Service'];

$sansBRTitre = str_replace("<br />", "\n", $donall['Titre']);
$modif_Titre = stripslashes($sansBRTitre);
	
$modif_Destinataires = $donall['Destinataires'];
if ($modif_Destinataires == 1){ $modif_NomDestinataires = "Bernetic seulement"; }	
else if ($modif_Destinataires == 2){ $modif_NomDestinataires = "Bernetic + Commerciaux"; }
else if ($modif_Destinataires == 3){ $modif_NomDestinataires = "Bernetic + Commerciaux + Clients"; }
	
$modif_Etat = $donall['etatdoc'];	
}
    ?>
            
            <form action="../admin/pfobso.php" method="post" enctype="multipart/form-data" name="formulaire" id="formulaire2">
              <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" bgcolor="#434343" class="fondtransp1">
                <tr>
                  <td height="40" colspan="4" align="center" bgcolor="#D97900" style="color: #FFFFFF"><span class="soustitre">Je modifie le document  &#8470;<?php echo $modifT ; ?></span></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" align="center" bgcolor="#000000" style="color: #FFFFFF"><span class="TextPlanBlanc">Je suis : 
                      <select name="NomPosteurM" id="NomPosteurM">
                        <option selected="selected" value="<?php echo $modif_NomPosteur ; ?>"><?php echo $modif_NomPosteur ; ?></option>
                        <option value="Myriam CARRE">Myriam CARRE</option>
                        <option value="Bernard CLEMENCE">Bernard CLEMENCE</option>
                        <option value="Sebastien JAHIER">Sebastien JAHIER</option>
                        <option value="Pascal LE BIAVANT">Pascal LE BIAVANT</option>
                        <option value="Lydie LE GRAS">Lydie LE GRAS</option>
                      </select>
                  </span></td>
                </tr>
                <tr>
                  
                  <?php if (empty($modif_NomFichier)) { ?>
                    <td height="40" colspan="4" align="center" bgcolor="red" class="TextPlanBlanc" style="color: #FFFFFF"> <?php echo "Aucun fichier t&eacute;l&eacutecharg&eacute pour le moment" ;?></td>
                    <?php } else { ?>
                  <td height="40" colspan="4" align="center" bgcolor="#2B2B2B" class="TextPlanBlanc" style="color: #FFFFFF"> <?php echo "Fichier existant : ". $modif_NomFichier ; } ?></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" align="center" bgcolor="#000000" class="TextPlanBlanc" style="color: #FFFFFF"><span class="TextPlanBlanc"><input type="file" name="avatar" id="avatar" />
                  </span></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" bgcolor="#2B2B2B" style="color: #FFFFFF"><div align="center">
                    <span class="TextPlanBlanc">Titre de mon document : 
                    <input name="TitreM" type="text" id="Titre2" value="<?php echo $modif_Titre ; ?>" size="60" maxlength="90" />
                  </span></div></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" bgcolor="#000000" style="color: #FFFFFF"><div align="center">
                    <span class="TextPlanBlanc">Type de document : 
                    <select name="TypeM" id="TypeM">
                      <option selected="selected"><?php echo $modif_Type ; ?></option>
                      <option value="Procedure">Procedure</option>
                      <option value="Charte">Charte</option>
                      <option value="Note">Note</option>
                    </select>
                  </span></div></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" bgcolor="#2B2B2B" style="color: #FFFFFF"><div align="center">
                    <span class="TextPlanBlanc">Document destiné à : 
                    <select name="ServiceM" id="ServiceM">
                      <option selected="selected"><?php echo $modif_Service ; ?></option>
                      <option value="ADV">ADV</option>
                      <option value="Marketing">Marketing</option>
						<option value="Production">Production</option>
                    </select>
                  </span></div></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" bgcolor="#000000" style="color: #FFFFFF"><div align="center"><span class="TextPlanBlanc">Fichier accessible par :
                        <select name="DestinatairesM" id="DestinatairesM">
                          <option selected="selected" value="<?php echo $modif_Destinataires ; ?>"><?php echo $modif_NomDestinataires ; ?></option>
                          <option value="1">Bernetic seulement</option>
                          <option value="2">Bernetic + Commerciaux</option>
                          <option value="3">Bernetic + Commerciaux + Clients</option>
                        </select>
                  </span></div></td>
                </tr>
                <tr>
                  <td width="27%" height="40" align="right" valign="middle" bgcolor="#2B2B2B" style="color: #FFFFFF"><span class="TextPlanBlanc"> Disponible à partir du :</span></td>
                  <td width="23%" height="40" bgcolor="#2B2B2B" style="color: #FFFFFF"><input name="DateM" required type="text" id="Datepicker1"  value="<?php echo $modif_Date ; ?>"/></td>
                  <td width="18%" height="40" align="right" valign="middle" bgcolor="#2B2B2B" style="color: #FFFFFF"><span class="TextPlanBlanc">Fin de validité :</span></td>
                  <td width="32%" height="40" bgcolor="#2B2B2B" style="color: #FFFFFF"><input name="DateValidM" type="text" id="Datepicker2"  value="<?php echo $modif_DateValid ; ?>"/></td>
                </tr>
                <tr>
                  <td height="40" colspan="4" bgcolor="#000000" style="color: #FFFFFF"><div align="center">
                    <input name="valider2" type="submit" id="valider2" value="Confirmer" />
                    <input type="hidden" name="IDM" id="IDM" value="<?php echo $modifT; ?>" />
                    <input type="hidden" name="MonAncienFichierM" value="<?php echo $modif_NomFichier ; ?>" />
                    <input type="hidden" name="posted" value="1">
                  </div></td>
                </tr>
              </table>
              <?php } ?>
            </form><br>
<br>
            
</div><?php 
$req = "SELECT COUNT(*) AS Total FROM documents WHERE etatdoc='depass'"; 
$reponse1 = mysqli_query($con, $req)or die(mysqli_error());
while ($donnees1 = mysqli_fetch_assoc($reponse1))
{
$Etat1 = $donnees1['Total'];	
if ($Etat1 == 0){} else {
			?>
          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="fondtransp1">
            <tr>
              <td><table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                  <td height="30" colspan="6" align="center" valign="middle" bgcolor="red" style="color: #FF8E00"><span class="soustitre">Documents en fin de validit&eacute;</span></td>
                </tr>
                <tr>
                  	             <td width="78" height="30" align="center" valign="middle" bgcolor="#BBB8B8"><span class="TextPlanNoir1">Num&eacute;ro du document</span><br /></td>
                    <td width="172" height="30"  align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Cr&eacute;&eacute; par</td>
                    <td width="501" align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Titre du document</td>
                    <td width="91" height="30" align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Type</td>
                    <td width="75" align="center" valign="middle" bgcolor="#BBB8B8" class="TextPlanNoir1">Rubrique</td>
                    <td width="38" height="30" align="center" valign="middle" bgcolor="#BBB8B8">&nbsp;</td>
                </tr>
              </table>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td height="4"></td>
      </tr>
    </tbody>
</table>
                <?php 
$req = "SELECT * FROM documents WHERE etatdoc='depass'"; 
$reponse = mysqli_query($req)or die(mysqli_error());
while ($donnees = mysqli_fetch_array($reponse))
{
$ID = $donnees['id'];
$NomPosteur = $donnees['posteur'];
$DateJour = $donnees['dateproduction'];
$DateValid = $donnees['dateperemption'];
$MaDateJour = date('Y-m-d', strtotime($DateJour)) ;
$MaDateValid = date('Y-m-d', strtotime($DateValid)) ;
if ($MaDateValid == "1970-01-01"){ $MaDateValid = "Non précisée" ; }    
$NomFichier = $donnees['nomfichier'];
$Version = " - v".$donnees['version'];
$Type = $donnees['typedoc'];
$Service = $donnees['servicedoc'];
$Titre =  $donnees['titre'];	
$Destinataires = $donnees['destinataires'];
$Etat = $donnees['etatdoc'];	
?>
                <table width="100%" border="0" bordercolor="#FFFFFF" cellspacing="3" cellpadding="0">
					   <tr>
                      <td width="77" align="center" valign="middle" bgcolor="#8C8C8C" class="soustitrenoir"><?php echo $ID ;?><br /></td>
                      <td width="175"  align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><?php echo $NomPosteur ; ?></td>
                      <td width="436" align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanNoir1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                            <tr>
                              <td class="soustitrenoir"><?php if (!empty($NomFichier)){ echo "<a href='../../../Tpfolio/$NomFichier'  target='_blank' class='blanc2'>".$Titre."</a>" ;   } else { echo $Titre ; } ?></td>
                            </tr>
                            <tr>
                              <td align="center" valign="middle">Affichage du : <?php echo $MaDateJour ; ?> jusqu'au : <?php echo $MaDateValid; ?></td>
                            </tr>
                          </tbody>
                      </table></td>
                      <td width="70" align="center" valign="middle" class="TextPlanBlanc">
                     <input type="button" value="Modifier" onclick="window.location.href='<?php echo 'pfobso.php?modifT='.$ID.'' ; ?>';">  
					  </td>
                      <td width="89" align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><?php echo $Type;?></td>
                      <td width="76" align="center" valign="middle" bgcolor="#8C8C8C" class="TextPlanBlanc"><?php echo $Service;?></td>
                      <td width="37" align="center" valign="middle" bgcolor="#8C8C8C"><a href="<?php echo 'pfobso.php?supprimer=' . $ID . '&amp;Fichier='.$NomFichier.''; ?>" onclick="return confirm_del()"><img src="supproff.png" alt="supprimer" name="suprimer" width="25" height="25" border="0" id="suprimer" /></a></td>
                    </tr>
                </table><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td height="4"></td>
      </tr>
    </tbody>
</table>
               <?php } ?></td>
            </tr>
        </table><?php }} ?>
          <br>
		 </td>
      </tr>
    </table></td>
  </tr>
</table>


<script type="text/javascript">
$(function() {
	$( "#Datepicker1" ).datepicker();
	
});
$(function() {
	$( "#Datepicker2" ).datepicker(); 
});
</script>
</body>
</html>