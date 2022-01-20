<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PortFolio</title>
<link href="stylesheet.css" rel="stylesheet" type="text/css" />	
</head>
<?php
// On se connecte d'abord a MySQL : 
$con = mysqli_connect('localhost', 'root', '') or die(mysqli_error($con)); 
mysqli_select_db($con, 'intranetBE');
include ("recherche.php") ;
ini_set( 'default_charset', "UTF-8" );
    ?>
	
	

<body><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#434343">
		    <tbody>
		      <tr>
		        <td width="70" align="left" valign="top"><a href="http://192.168.0.161/intranet/index.php"><img src="../logoBE.png" width="70" height="77" alt=""/></a></td>
		        <td width="90%" height="77" align="center" valign="middle" class="titreEtire">INTRANET  BERNETIC</td>
		        <td width="70" align="center" valign="top" class="titre">&nbsp;</td>
	          </tr>
			</tbody>
	</table>
<p>&nbsp;</p>
<p><span class="petittitre">Portfolio </span><br>
  <br>
  <br>
</p>
<table width="961" height="524" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
      <tr>
        <td height="524" align="center" valign="middle"><br>
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td width="24%" align="center" valign="middle"><span class="soustitre">Quel service :</span></td>
                <td width="43%" align="center" valign="middle" class="soustitre">Type de document :</td>
                <td width="33%" align="center" valign="middle" class="soustitre">Recherche par mot clé:</td>
              </tr>
              <tr>
                <td align="center" valign="middle"> <?php
//REQUETES POUR LES TRIS
if (empty($_POST['TriService'])) { $_SESSION['TriService'] = "tous" ; } else { $_SESSION['TriService'] = $_POST['TriService'];}
$TriService = $_SESSION['TriService'] ; 

if (empty($_POST['TriType'])) { $_SESSION['TriType'] = "tous" ; } else { $_SESSION['TriType'] = $_POST['TriType'];}
$TriType = $_SESSION['TriType'] ;                     
                   
if (empty($_POST['TriMots'])) { $_SESSION['TriMots'] = "aucun" ; } else { $_SESSION['TriMots'] = $_POST['TriMots'];}
$TriMots = $_SESSION['TriMots'] ;                     
                    ?>
                  <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ; ?>">
  <select name="TriService" id="TriService" title="TriService" onchange="submit()">
<option value ="tous">Tout les services</option>

<?php   
$reponseTriSer = mysqli_query($con, "SELECT servicedoc FROM documents WHERE etatdoc='on' AND servicedoc = '$TriService' GROUP BY servicedoc");
while ($donneesTriSer = mysqli_fetch_assoc($reponseTriSer) ) 
{ 
$MonServiceTri = $donneesTriSer['servicedoc'];
?>
<option value="<?php echo $MonServiceTri ?>" selected="selected"><?php echo $MonServiceTri ; ?></option>
<?php } 
$reponseTriSer2 = mysqli_query($con, "SELECT servicedoc FROM documents WHERE etatdoc='on' GROUP BY servicedoc");
while ($donneesTriSer2 = mysqli_fetch_assoc($reponseTriSer2) ) 
{ 
$MonServiceTri2 = $donneesTriSer2['servicedoc'];
?>   
<option value="<?php echo $MonServiceTri2; ?>"><?php echo $MonServiceTri2; ?></option>
<?php } ?>
    </select>
    <input id='TriType' name='TriType' type='hidden' value='<?php echo $TriType ; ?>'> 
</form>
                  
           
                </td>
<td align="center" valign="middle">
                  
                  
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ; ?>">
<select name="TriType" id="TriType" title="TriType" onchange="submit()">
<option value ="tous">Tous les types de documents</option>

<?php   
$reponseTriTyp = mysqli_query($con, "SELECT typedoc FROM documents WHERE etatdoc='on' AND typedoc = '$TriType' GROUP BY typedoc");
while ($donneesTriTyp = mysqli_fetch_assoc($reponseTriTyp)) 
{ 
$MonTypeTri = $donneesTriTyp['typedoc'];
?>
<option value="<?php echo $MonTypeTri ?>" selected="selected"><?php echo $MonTypeTri ; ?></option>
    <?php } 
$reponseTriTyp2 = mysqli_query($con, "SELECT typedoc FROM documents WHERE etatdoc='on' GROUP BY typedoc");
while ($donneesTriTyp2 = mysqli_fetch_assoc($reponseTriTyp2)) 
{ 
$MonTypeTri2 = $donneesTriTyp2['typedoc'];
?>   
<option value="<?php echo $MonTypeTri2; ?>"><?php echo $MonTypeTri2; ?></option> <?php } ?>
</select>
<input id='TriService' name='TriService' type='hidden' value='<?php echo $TriService ; ?>'>   
</form> 
      
                    
                </td>
<td><form method="post" action="<?php echo $_SERVER['PHP_SELF'] ; ?>">

<input type="TriMots" name="TriMots" id="TriMots" value="<?php echo $TriMots ;?>" onchange="submit()">    

<input id='TriMots' name='TriMots' type='hidden' value='<?php echo $TriMots ; ?>'> 
</form> </td>
              </tr>
            </tbody>
          </table>
          <?php
        
/////////////////
///////////////// SI AUCUN TRI ON  AFFICHE TOUT LES SERVICES
            
if (($_SESSION['TriService'] == "tous") AND ($_SESSION['TriType'] == "tous"))  {         

$requete = mysqli_query($con, "SELECT servicedoc FROM documents WHERE etatdoc='on' GROUP BY servicedoc ");
while($data = mysqli_fetch_assoc($requete))
foreach($data as $NouvID)
{
$MaRubrique = $data['servicedoc'];
    ?>
      <br>
 <table width="100%" border="0" bordercolor="#D97900" cellspacing="3" cellpadding="0">
 <tr>
 <td width="2%" height="44" align="left" valign="bottom" bgcolor="#008EF7"><img src="fleche.png" alt="fleche"/></td>
 <td width="98%" align="left" valign="middle" bgcolor="#008EF7"><span class="NoCmd">&nbsp;<?php echo $MaRubrique ; ?></span></td>
 </tr>
 </table>
  <table width="100%" border="0" cellspacing="3" cellpadding="0">
    <tr>
      <td width="70" height="30" align="center" valign="middle" bgcolor="#00BDD4"><span class="TextPlanNoir1">Doc N&deg;</span><br /></td>
      <td width="429" align="center" valign="middle" bgcolor="#00BDD4" class="TextPlanNoir1">Titre du document</td>
      <td width="281" height="30" align="center" valign="middle" bgcolor="#00BDD4" class="TextPlanNoir1">Type</td>
      <td width="205" align="center" valign="middle" bgcolor="#00BDD4" class="TextPlanNoir1"><p>Destinataire</p></td>
      </tr>
  </table>
<?php 
$requete2 = mysqli_query($con, "SELECT * FROM documents WHERE servicedoc = '$MaRubrique' AND etatdoc='on'");
while($data2 = mysqli_fetch_array($requete2))
{
$now = date("Y-n-d");
$DateValid = $data2['dateperemption'] ;
$Date = $data2['dateproduction'] ;
$ID = $data2['id'] ;	
$NomFichier = $data2['nomfichier']; 
    

$NomPosteur = $data2['posteur'];
$Date = $data2['dateproduction'];
$DateValid = $data2['dateperemption'];	
$Type = $data2['typedoc'];	
$Service = $data2['servicedoc'];
$Titre = $data2['titre'];	
$Destinataires = $data2['destinataires'];
if ($Destinataires == '1') { $Destinataires = "<img src='Dest1.png' width='25' height='25' title='Bernetic seulement'/>" ; }
if ($Destinataires == '2') { $Destinataires = "<img src='Dest2.png' width='25' height='25' title='Bernetic + Commerciaux'/>" ; } 
if ($Destinataires == '3') { $Destinataires = "<img src='Dest3.png' width='25' height='25' title='Bernetic + Commerciaux + Clients'/>" ; }     
?>      
  <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#FFFFFF">
    <tr>
        <td width="69" height="30" align="center" valign="middle" bgcolor="#545454" class="TextPlanBlanc"><?php echo $ID ;?></td>
        <td width="409" align="center" valign="middle" bgcolor="#545454" class="TextPlanNoir1"><?php if (!empty($NomFichier)){ echo "<a href='../../Tpfolio/$NomFichier'  target='_blank' class='blanc3'>".$Titre."</a>" ;   } else { echo $Titre ; } ?>
          
        </td>
        <td width="268" align="center" valign="middle" bgcolor="#545454" class="TextPlanBlanc"><?php echo $Type;?></td>
        <td width="200" align="center" valign="middle" bgcolor="#545454" class="TextPlanBlanc"><?php echo $Destinataires;?></td>
        </tr>
  </table>    
             
            
<?php }}} else {
//ON TRIE LES AFFICHAGES     
$MonServiceTri = $_SESSION['TriService'];
$MonTypeTri = $_SESSION['TriType']; 

if ($MonServiceTri == 'tous') {
    $AffichTri = "Toutes les Rubriques" ;
if ($MonTypeTri != 'tous') { $where = "WHERE typedoc = '$MonTypeTri' AND etatdoc='on'"; }}
    
if ($MonServiceTri != 'tous') {
    $AffichTri = $MonServiceTri ;
if ($MonTypeTri == 'tous') { $where = "WHERE servicedoc = '$MonServiceTri' AND etatdoc='on'"; }
if ($MonTypeTri != 'tous') { $where = "WHERE servicedoc = '$MonServiceTri' AND typedoc = '$MonTypeTri' AND etatdoc='on'"; }}    
    
    
            ?>    <br>

 <table width="100%" border="0" bordercolor="#D97900" cellspacing="3" cellpadding="0">
 <tr>
 <td width="2%" height="30" align="left" valign="bottom" bgcolor="#008EF7"><img src="fleche.png" alt="fleche"/></td>
 <td width="98%" align="left" valign="middle" bgcolor="#008EF7"><span class="NoCmd">&nbsp;<?php echo $AffichTri ; ?></span></td>
 </tr>
 </table>
  <table width="100%" border="0" cellspacing="3" cellpadding="0">
    <tr>
      <td width="74" height="30" align="center" valign="middle" bgcolor="#00BDD4"><span class="TextPlanNoir1">Doc N&deg;</span><br /></td>
      <td width="426" align="center" valign="middle" bgcolor="#00BDD4" class="TextPlanNoir1">Titre du document</td>
      <td width="284" height="30" align="center" valign="middle" bgcolor="#00BDD4" class="TextPlanNoir1">Type</td>
      <td width="201" align="center" valign="middle" bgcolor="#00BDD4" class="TextPlanNoir1">Destinataires</td>
      </tr>
  </table>
<?php             
                
           
$requete2 = mysqli_query($con, "SELECT * FROM documents $where ");
while($data2 = mysqli_fetch_array($requete2))
{
$now = date("Y-n-d");
$DateValid = $data2['dateperemption'] ;
$Date = $data2['dateproduction'] ;
$ID = $data2['id'] ;	
$NomFichier = $data2['nomfichier']; 
    

$NomPosteur = $data2['posteur'];
$Date = $data2['dateproduction'];
$DateValid = $data2['dateperemption'];	
$Type = $data2['typedoc'];	
$Service = $data2['servicedoc'];
$Titre = $data2['titre'];	
$Destinataires = $data2['destinataires'];
if ($Destinataires == '1') { $Destinataires = "<img src='Dest1.png' width='25' height='25' title='Bernetic seulement'/>" ; }
if ($Destinataires == '2') { $Destinataires = "<img src='Dest2.png' width='25' height='25' title='Bernetic + Commerciaux'/>" ; } 
if ($Destinataires == '3') { $Destinataires = "<img src='Dest3.png' width='25' height='25' title='Bernetic + Commerciaux + Clients'/>" ; }     
?>      
  <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#FFFFFF">
    <tr>
        <td width="72" height="30" align="center" valign="middle" bgcolor="#545454" class="TextPlanBlanc"><?php echo $ID ;?></td>
        <td width="427" align="center" valign="middle" bgcolor="#545454" class="TextPlanNoir1"><?php if (!empty($NomFichier)){ echo "<a href='../../Tpfolio/$NomFichier'  target='_blank' class='blanc3'>".$Titre."</a>" ;   } else { echo $Titre ; } ?>
          
        </td>
        <td width="287" align="center" valign="middle" bgcolor="#545454" class="TextPlanBlanc"><?php echo $Type;?></td>
        <td width="199" align="center" valign="middle" bgcolor="#545454" class="TextPlanBlanc"><?php echo $Destinataires;?></td>
        </tr>
  </table> <?php }} ?>
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>