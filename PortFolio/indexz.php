<?php
$serveur='192.168.0.161';	//Nom du serveur (en local : locahost)
$user='bernard';	//  bernard Nom de l'utilisateur (en local : root)
$mdp='berclem0511';	// bernard Mot de passe (en local : aucun)
$base='IntranetBE';	//Nom de la base de données
// On se connecte d'abord a MySQL : 
$con = mysqli_connect('localhost', 'root', '') or die(mysqli_error($con)); 
mysqli_select_db($con, 'intranetBE');

ini_set( 'default_charset', "UTF-8" );

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
mysqli_query($con, "UPDATE documents SET etatdoc ='depass' WHERE id ='$ID'");
}
if ((!empty($NomFichier)) AND (strtotime($Date)) <= (strtotime($now)) AND (strtotime($DateValid)) > (strtotime($now)) OR (empty(strtotime($DateValid)))){
mysqli_query($con, "UPDATE documents SET etatdoc ='on' WHERE id ='$ID'"); 
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
<?php 	//On ajoute un nouvelle info
if (isset($_POST['posteur']) AND isset($_POST['titre'])) // Si les variables existent 
{ 
    if ($_POST['posteur'] != NULL  AND $_POST['titre'] != NULL) // Si on a quelque chose à enregistrer 
    {

$NomPosteur = $_POST['posteur'];
$Date = $_POST['dateproduction'];
$DateValid = $_POST['dateperemption'];		
$NomFichier = $_POST['nomfichier'];
$Version = $_POST['version'];
$Type = $_POST['typedoc'];
$Service = $_POST['servicedoc'];
$Titre =  $_POST['titre'];	
$Destinataires = $_POST['destinataires'];
	
		
mysqli_query("INSERT INTO documents VALUES('', '$NomPosteur', '$Date', '$DateValid', '$NomFichier', '$Version', '$Type', '$Service', '$Titre', '$Destinataires', 'wait')") or die(mysqli_error());   
?>
        <script type="text/JavaScript">
<!--
function redirection(page)
  {window.location=page;}
setTimeout('redirection("pfattente.php")',0);
//--> 
</script>  
<?php     }} ?>
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

</head>
<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
		         <td width="85%" align="left" valign="middle"><span class="titre"><a href="../../index.php"><img src="../../PointRetour.png" width="40" height="40" alt=""/></a> Administration &gt;</span><span class="petittitre2"> PortFolio <a href="index.php" class="blanc4">&gt; accueil</a></span></td>
        </tr>
    </table>
      <br><br>
      <table width="65%" border="0" cellpadding="8" cellspacing="0">
        <tbody>
          <tr>
            <td height="60" align="center" valign="middle" bgcolor="#0E0D0D"><a href="indexz.php?ajouter"class="soustitre"><img src="plus.png" alt="" width="45" height="44"/><br>Ajout de document</a><br>
 <?php // On affiche la fen&ecirc;tre ajouter actualite
if (isset($_GET['ajouter'])) // Si on demande d ajouter une news 
{ ?>
                    <br><form action="../admin/index.php" method="post" enctype="multipart/form-data" name="formulaire" id="formulaire">
                      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="fondtransp1">
                        <tr>
                          <td height="25" colspan="4" align="center" bgcolor="#8C8C8C"><select name="posteur" id="posteur">
<option selected="selected">Je suis...</option>
<option value="Myriam CARRE">Myriam CARRE</option>
<option value="Bernard CLEMENCE">Bernard CLEMENCE</option>
<option value="Sebastien JAHIER">Sebastien JAHIER</option>
<option value="Pascal LE BIAVANT">Pascal LE BIAVANT</option>							  
<option value="Lydie LE GRAS">Lydie LE GRAS</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center">
                              <input name="titre" type="text" id="titre" value="titre" size="60" maxlength="90" />
                          </div></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center">
                            <select name="type" id="typedoc">
                            <option selected="selected">Type de document</option>
                            <option value="Procedure">Procedure</option>
                            <option value="Charte">Charte</option>
                            <option value="Note">Note</option>
                          </select>
						  </div></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center">
                            <select name="Service" id="service">
                            <option selected="selected">Service</option>
                            <option value="ADV">ADV</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Production">Production</option>
                            </select>
							  
						  </div></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center"><select name="Destinataires" id="Destinataires">
                            <option selected="selected">Destinataires</option>
                            <option value="1">Bernetic seulement</option>
                            <option value="2">Bernetic + Commerciaux</option>
                            <option value="3">Bernetic + Commerciaux + Clients</option>
                          </select></div></td>
                        </tr>
                        <tr>
							<td width="27%" height="25" align="right" valign="middle" bgcolor="#8C8C8C"><span class="TextPlanBlanc"> Disponible à partir du :</span></td>
                          <td width="23%" height="25" bgcolor="#8C8C8C"><input name="dateproduction" required type="text" id="Datepicker1" /></td>
							<td width="18%" height="25" align="right" valign="middle" bgcolor="#8C8C8C"><span class="TextPlanBlanc">Fin de validit&eacute; :</span> </td>
                          <td width="32%" height="25" bgcolor="#8C8C8C"><input name="dateperemption" type="text" id="Datepicker2" /></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center">
                              <input name="valider" type="submit" id="valider" value="Confirmer" />
                          </div></td>
                        </tr>
                      </table>
                      <?php } ?>
                    </form></td>
          </tr>
        </tbody>
      </table>
      <br><br><br>

        
        
        
   <?php 
$req = "SELECT COUNT(*) AS Total FROM documents WHERE etatdoc='wait'"; 
$reponse0 = mysqli_query($con, $req);
while ($donnees0 = mysqli_fetch_assoc($reponse0))
{
$Etat = $donnees0['Total'];	
if ($Etat == 0){ $MessAttente = "Aucun document en attente" ;} else if ($Etat == 1){ $MessAttente = $Etat. " document en attente" ; } else { $MessAttente = $Etat. " documents en attente" ; }} ?>
    
  <?php 
$req = "SELECT COUNT(*) AS Total FROM documents WHERE etatdoc='depass'"; 
$reponse0 = mysqli_query($con, $req);
while ($donnees0 = mysqli_fetch_assoc($reponse0))
{
$Etat = $donnees0['Total'];	
if ($Etat == 0){ $MessDepass = "Aucun document en fin de validité" ;} else if ($Etat == 1){ $MessDepass = $Etat. " document en fin de validité" ; } else { $MessDepass = $Etat. " documents en fin de validité" ; }} ?>
    
  <?php 
$req = "SELECT COUNT(*) AS Total FROM documents WHERE etatdoc='on'"; 
$reponse0 = mysqli_query($con, $req)or die(mysqli_error());
while ($donnees0 = mysqli_fetch_assoc($reponse0))
{
$Etat = $donnees0['Total'];	
if ($Etat == 0){ $MessOk = "Aucun document disponible" ;} else if ($Etat == 1){ $MessOk = $Etat. " document disponible" ; } else { $MessOk = $Etat. " documents disponibles" ; }} ?>        
		
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="10%" height="80" align="left" valign="middle"><a href='pfattente.php'><img src="attente.png" alt="" width="80"/></a></td>
      <td width="25%" class="TextPlanBlanc1"><a href='pfattente.php' class="blanc3"><?php echo $MessAttente ; ?></a></td>
      <td width="10%" align="left" valign="middle"><a href='pfobso.php'><img src="obso.png" alt="" width="80"/></a></td>
      <td width="25%" class="TextPlanBlanc1"><a href='pfobso.php' class="blanc3"><?php echo $MessDepass ; ?></a></td>
      <td width="10%" align="left" valign="middle"><a href='pfok.php'><img src="valid.png" alt="" width="80"/></a></td>
      <td width="25%" class="TextPlanBlanc1"><a href='pfok.php' class="blanc3"><?php echo $MessOk ; ?></a></td>
    </tr>
    </tbody>
</table>
<span class="TextPlanBlanc1"><br>
  Rechercher dans tous les documents :<br>
  </span>
  <br><?php include ("recherche.php") ; ?>
	 </td>
  </tr>

</body>
</html>