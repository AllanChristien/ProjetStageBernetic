<?php
$serveur='192.168.0.161';	//Nom du serveur (en local : locahost)
$user='bernard';	//  bernard Nom de l'utilisateur (en local : root)
$mdp='berclem0511';	// bernard Mot de passe (en local : aucun)
$base='IntranetBE';	//Nom de la base de données
// On se connecte d'abord a MySQL : 
mysqli_connect($serveur, $user, $mdp) or die("Problème de connexion au serveur de Bernétic. <br/>Nous mettons tout en oeuvre pour rétablir la connexion dans les meilleurs délais."); 
mysql_select_db($base);

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
$requete = mysql_query("SELECT ID FROM portfolio");
while($data = mysql_fetch_assoc($requete))
foreach($data as $NouvID)
{
$requete2 = mysql_query("SELECT * FROM portfolio WHERE ID = $NouvID");
while($data2 = mysql_fetch_assoc($requete2))
{
$now = date("Y-n-d");
$DateValid = $data2['DateValid'] ;
$Date = $data2['Date'] ;
$ID = $data2['ID'] ;	
$NomFichier = $data2['NomFichier']; 
}

if (strtotime($DateValid) < (strtotime($now)) AND (!empty(strtotime($DateValid)))){
mysql_query("UPDATE portfolio SET Etat ='depass' WHERE ID ='$ID'")or die(mysql_error());
}
if ((!empty($NomFichier)) AND (strtotime($Date)) <= (strtotime($now)) AND (strtotime($DateValid)) > (strtotime($now)) OR (empty(strtotime($DateValid)))){
mysql_query("UPDATE portfolio SET Etat ='on' WHERE ID ='$ID'")or die(mysql_error()); 
} 
if ( (empty($NomFichier)) OR (strtotime($Date)) > (strtotime($now)) ){
mysql_query("UPDATE portfolio SET Etat ='wait' WHERE ID ='$ID'")or die(mysql_error()); 
} 
}
////////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////////	
// FIN DE LA ZONE DE CONTROLE AVANT CHARGEMENT DES PAGES
////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////// ?>
<?php 	//On ajoute un nouvelle info
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
	
		
mysql_query("INSERT INTO portfolio VALUES('', '$NomPosteur', '$Date', '$DateValid', '$NomFichier', '$Version', '$Type', '$Service', '$Titre', '$Destinataires', 'wait')") or die(mysql_error());   
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
            <td height="60" align="center" valign="middle" bgcolor="#0E0D0D"><a href="index.php?ajouter"class="soustitre"><img src="plus.png" alt="" width="45" height="44"/><br>Ajouter un document</a><br>
 <?php // On affiche la fen&ecirc;tre ajouter actualite
if (isset($_GET['ajouter'])) // Si on demande d ajouter une news 
{ ?>
                    <br><form action="../admin/index.php" method="post" enctype="multipart/form-data" name="formulaire" id="formulaire">
                      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="fondtransp1">
                        <tr>
                          <td height="25" colspan="4" align="center" bgcolor="#8C8C8C"><select name="NomPosteur" id="NomPosteur">
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
                              <input name="Titre" type="text" id="Titre" value="Titre" size="60" maxlength="90" />
                          </div></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center">
                            <select name="Type" id="Type">
                            <option selected="selected">Type de document</option>
                            <option value="Procedure">Procedure</option>
                            <option value="Charte">Charte</option>
                            <option value="Note">Note</option>
                          </select>
						  </div></td>
                        </tr>
                        <tr>
                          <td height="25" colspan="4" bgcolor="#8C8C8C"><div align="center">
                            <select name="Service" id="Service">
                            <option selected="selected">Rubrique</option>
                            <option value="ADV">ADV</option>
                            <option value="Devis">Devis</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Expedition">Expedition</option>
                            <option value="Stock">Stock</option>
                            <option value="Production">Production</option>
                            <option value="Service Personnel">Service Personnel</option>
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
                          <td width="23%" height="25" bgcolor="#8C8C8C"><input name="Date" required type="text" id="Datepicker1" /></td>
							<td width="18%" height="25" align="right" valign="middle" bgcolor="#8C8C8C"><span class="TextPlanBlanc">Fin de validité :</span> </td>
                          <td width="32%" height="25" bgcolor="#8C8C8C"><input name="DateValid" type="text" id="Datepicker2" /></td>
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
$req = "SELECT COUNT(*) AS Total FROM portfolio WHERE Etat='wait'"; 
$reponse0 = mysql_query($req)or die(mysql_error());
while ($donnees0 = mysql_fetch_assoc($reponse0))
{
$Etat = $donnees0['Total'];	
if ($Etat == 0){ $MessAttente = "Aucun document en attente" ;} else if ($Etat == 1){ $MessAttente = $Etat. " document en attente" ; } else { $MessAttente = $Etat. " documents en attente" ; }} ?>
    
  <?php 
$req = "SELECT COUNT(*) AS Total FROM portfolio WHERE Etat='depass'"; 
$reponse0 = mysql_query($req)or die(mysql_error());
while ($donnees0 = mysql_fetch_assoc($reponse0))
{
$Etat = $donnees0['Total'];	
if ($Etat == 0){ $MessDepass = "Aucun document en fin de validité" ;} else if ($Etat == 1){ $MessDepass = $Etat. " document en fin de validité" ; } else { $MessDepass = $Etat. " documents en fin de validité" ; }} ?>
    
  <?php 
$req = "SELECT COUNT(*) AS Total FROM portfolio WHERE Etat='on'"; 
$reponse0 = mysql_query($req)or die(mysql_error());
while ($donnees0 = mysql_fetch_assoc($reponse0))
{
$Etat = $donnees0['Total'];	
if ($Etat == 0){ $MessOk = "Aucun document disponible" ;} else if ($Etat == 1){ $MessOk = $Etat. " document disponible" ; } else { $MessOk = $Etat. " documents disponibles" ; }} ?>        
		
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="10%" align="left" valign="middle"><a href='pfattente.php'><img src="attente.png" alt="" width="80"/></a></td>
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