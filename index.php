<?php
	session_start();
	if (!isset($_SESSION['login']) || !isset($_SESSION['password']))
	{
		header('Location: login.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Accueil - BMM - Brownie money management</title>
	<link rel="stylesheet" type="text/css" href="css/styleIndex.css">
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body>
	<center><h1>Brownie money management</h1>
	<h2>Accueil</h2>
	</center>
	<a href="login.php" id="deco"><input type="button" value="Déconnexion" id="deconnexion"></a>
	
	<?php
		try
		{
			$db = mysql_connect('localhost', 'root', '');
			mysql_select_db('browniemoneymanagement', $db);

			//Utiliser les caractères spéciaux de la base
			mysql_query("SET NAMES 'utf8'");

			$query = mysql_query('SELECT id, sens, montant, libelle, coloc, date_format(date, "%d/%m/%Y") as calendar FROM counts');

			$countVincent = mysql_query('SELECT count(coloc) as nbEnregV FROM counts WHERE coloc="Vincent"');
			$dataCountV = mysql_fetch_assoc($countVincent);
			$nbVinc = $dataCountV['nbEnregV'];

			$countHugo = mysql_query('SELECT count(coloc) as nbEnregH FROM counts WHERE coloc="Hugo"');
			$dataCountH = mysql_fetch_assoc($countHugo);
			$nbHugo = $dataCountH['nbEnregH'];

			$tabHID = array();
			$tabHSens = array();
			$tabHMontant = array();
			$tabHDate = array();
			$tabHLibelle = array();
			$tabVID = array();
			$tabVSens = array();
			$tabVMontant = array();
			$tabVDate = array();
			$tabVLibelle = array();

			$totalVincent = 0;
			$totalHugo = 0;
			$du = 0;
			$textDu;

			$count = 0;

			//tableaux avec toutes les infos !
			echo '<div id="tableContainer">';
			echo '<table width="60%")>';
				echo '<tr>';
					echo '<th width="50%">Vincent</th>';
					echo '<th width="50%">Hugo</th>';
				echo'</tr>';

				while ($dataCounts = mysql_fetch_assoc($query))
				{
					if ($dataCounts['coloc'] == 'Hugo')
					{
						array_push($tabHID, $dataCounts['id']);
						array_push($tabHSens, $dataCounts['sens']);
						array_push($tabHMontant, $dataCounts['montant']);
						array_push($tabHDate, $dataCounts['calendar']);
						array_push($tabHLibelle, $dataCounts['libelle']);
					}
					elseif ($dataCounts['coloc'] == 'Vincent')
					{
						array_push($tabVID, $dataCounts['id']);
						array_push($tabVSens, $dataCounts['sens']);
						array_push($tabVMontant, $dataCounts['montant']);
						array_push($tabVDate, $dataCounts['calendar']);
						array_push($tabVLibelle, $dataCounts['libelle']);
					}

					$count++;
				}


				//Savoir quel est le coloc qui a le plus long tableau
				//pour parcourir tous les éléments des tableaux
				$cTabH = count($tabHSens);
				$cTabV = count($tabVSens);
				if (count($tabHSens) > count($tabVSens))
				{
					$plusLongTab = count($tabHSens);
					$plusCourtTab = count($tabVSens);
				}
				else
				{
					$plusLongTab = count($tabVSens);
					$plusCourtTab = count($tabHSens);
				}

				for ($i=0; $i < $plusCourtTab; $i++)
				{ 
					echo '<tr>';
						if (isset($tabVSens[$i]) && $tabVSens[$i] == 'Debit')
						{
							$totalVincent -= $tabVMontant[$i];
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'#ecf2f5\'"><a id="supprRight" href="supprimer.php?id=' . $tabVID[$i] . '" onclick="return(confirm(\'Veux-tu vraiment supprimer cette ligne ?\'));"><img id="imgSuppr" src="img/suppr_mod.png"></a><div id=montantD>-' . $tabVMontant[$i] . '&#8364; pour ' . $tabVLibelle[$i] . ' le ' . $tabVDate[$i] . '</div></td>';
						}						
						elseif (isset($tabHSens[$i]) && $tabVSens[$i] == 'Credit')
						{
							$totalVincent += $tabVMontant[$i];
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'#ecf2f5\'"><a id="supprLeft" href="supprimer.php?id=' . $tabVID[$i] . '" onclick="return(confirm(\'Veux-tu vraiment supprimer cette ligne ?\'));"><img id="imgSuppr" src="img/suppr_mod.png"></a><div id=montantC>+' . $tabVMontant[$i] . '&#8364; pour ' . $tabVLibelle[$i] . ' le ' . $tabVDate[$i] . '</div></td>';
						}

						if (isset($tabHSens[$i]) && $tabHSens[$i] == 'Debit')
						{
							$totalHugo -= $tabHMontant[$i];
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'#ecf2f5\'"><a id="supprRight" href="supprimer.php?id=' . $tabHID[$i] . '" onclick="return(confirm(\'Veux-tu vraiment supprimer cette ligne ?\'));"><img id="imgSuppr" src="img/suppr_mod.png"></a><div id=montantD><div id=montantD>-' . $tabHMontant[$i] . '&#8364; pour ' . $tabHLibelle[$i] . ' le ' . $tabHDate[$i] . '</div></td>';
						}
						elseif (isset($tabHSens[$i]) && $tabHSens[$i] == 'Credit')
						{
							$totalHugo += $tabHMontant[$i];
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'#ecf2f5\'"><a id="supprLeft" href="supprimer.php?id=' . $tabHID[$i] . '" onclick="return(confirm(\'Veux-tu vraiment supprimer cette ligne ?\'));"><img id="imgSuppr" src="img/suppr_mod.png"></a><div id=montantC>+' . $tabHMontant[$i] . '&#8364; pour ' . $tabHLibelle[$i] . ' le ' . $tabHDate[$i] . '</div></td>';
						}
						
						//&#8364; correspond au signe €
					echo '</tr>';
				}

				for ($i=$plusCourtTab; $i < $plusLongTab; $i++)
				{ 
					if ($plusLongTab == count($tabHSens) && $tabHSens[$i] == 'Debit')
					{
						$totalHugo -= $tabHMontant[$i];
						echo '<tr>';
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'#ecf2f5\'">' . ' ' . '</td>';
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'#ecf2f5\'"><a id="supprRight" href="supprimer.php?id=' . $tabHID[$i] . '" onclick="return(confirm(\'Veux-tu vraiment supprimer cette ligne ?\'));"><img id="imgSuppr" src="img/suppr_mod.png"></a><div id=montantD><div id=montantD>-' . $tabHMontant[$i] . '&#8364; pour ' . $tabHLibelle[$i] . ' le ' . $tabHDate[$i] . '</div></td>';
						echo '</tr>';
					}
					elseif ($plusLongTab == count($tabHSens) && $tabHSens[$i] == 'Credit')
					{
						$totalHugo += $tabHMontant[$i];
						echo '<tr>';
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'white\'">' . ' ' . '</td>';
							echo '<td onmouseover="this.style.backgroundColor=\'lightgrey\'" onmouseout="this.style.backgroundColor=\'white\'"><a id="supprLeft" href="supprimer.php?id=' . $tabHID[$i] . '" onclick="return(confirm(\'Veux-tu vraiment supprimer cette ligne ?\'));"><img id="imgSuppr" src="img/suppr_mod.png"></a><div id=montantC>+' . $tabHMontant[$i] . '&#8364; pour ' . $tabHLibelle[$i] . ' le ' . $tabHDate[$i] . '</div></td>';
						echo '</tr>';
					}
				}

					echo '<tr>';
						echo '<td id="total">' . $totalVincent . '&#8364;</td>';
						echo '<td id="total">' . $totalHugo . '&#8364;</td>';
					echo '</tr>';

					if ($totalHugo == $totalVincent)
					{
						$textDu = 'Les comptes sont bons, personne ne doit rien à personne';
					}
					elseif ($totalHugo < $totalVincent)
					{
						$textDu = 'Hugo a payé ' . abs(($totalVincent-$totalHugo)) . '&#8364; de plus';
					}
					elseif ($totalHugo > $totalVincent)
					{
						$textDu = 'Vincent a payé ' . abs(($totalVincent-$totalHugo)) . '&#8364; de plus';
					}

					echo '<tr>';
						echo '<td colspan=2 id="total">' . $textDu . '</td>';
					echo '</tr>';

			echo '</table>';
			echo '</div>';

			session_destroy();


		}
		catch (Exception $e)
		{
			echo $e;
		}
	?>

	<br>
	<div id="container">
        <form action="index.php" method="post">
            <select id="colocs" required="required" name="coloc" id="coloc">
				<option value="" disabled="disabled" selected="selected">Choisir un coloc
				<option value="Vincent">Vincent
				<option value="Hugo">Hugo
			</select>
			<select id="way" required="required" name="sens" id="sens">
				<option value="" disabled="disabled" selected="selected">Choisir un sens
				<option value="Debit">Debit
				<option value="Credit">Credit
			</select>
            <input type="text" autocomplete="off" placeholder="Libellé" name="libelle" id="libelle">
                        
            <input type="text" autocomplete="off" placeholder="Montant en €" name="montant" id="montant">&nbsp;
             
            <div id="lower">
                <input type="button" value="Ajouter" id="ajouter">
                <p id="error"></p>
            </div>
        </form>
    </div>


	<script src="js/jquery-1.10.2.js"></script>
	<script type="text/javascript">
    	$('#ajouter').click(function()
    	{
    		var coloc = "";
    		var sens = "";
    		if ($('#libelle').val() != "" && $('#montant').val() != "")
    		{
    			$("#colocs option:selected").each(function ()
    			{
    				coloc = $(this).text();
              	});
              	$("#way option:selected").each(function ()
              	{
    				sens = $(this).text();
              	});

              	window.location.href = "ajout.php?c=" + coloc + "&s=" + sens + "&l=" + $('#libelle').val() + "&m=" + $('#montant').val();
    		}
    		else
    		{
    			document.getElementById("error").innerHTML = 'Remplir tous les champs pour ajouter une ligne de comptes';
    		};
    	});

    	
	</script>
</body>
</html>