<?php
/*
Author: Flox
File Name: dashboard.php
Call: index_auth.php
Description: Display list of tasks
Version: 2.5
Creation date: 17/07/2009
last update: 25/12/2012
*/

// initialize variables 
if(!isset($asc)) $asc = ''; 
if(!isset($img)) $img= ''; 
if(!isset($date)) $date= '';  
if(!isset($from)) $from=''; 
if(!isset($filter)) $filter=''; 
if(!isset($col)) $col=''; 
if(!isset($view)) $view=''; 
if(!isset($keywords)) $keywords=''; 
if(!isset($nkeyword)) $nkeyword=''; 
if(!isset($rowlastname)) $rowlastname=''; 
if(!isset($resultcriticality['color'])) $resultcriticality['color']= ''; 
if(!isset($_GET['category'])) $_GET['category']= ''; 
if(!isset($_GET['subcat'])) $_GET['subcat']= ''; 
if(!isset($_GET['order'])) $_GET['order'] = ''; 
if(!isset($_GET['cursor'])) $_GET['cursor']= ''; 
if(!isset($_GET['way'])) $_GET['way'] = ''; 
if(!isset($_GET['searchengine'])) $_GET['searchengine'] = ''; 
if(!isset($_GET['date_create'])) $_GET['date_create'] = ''; 
if(!isset($_POST['criticality'])) $_POST['criticality'] = ''; 
if(!isset($_POST['priority'])) $_POST['priority']= '';
if(!isset($_POST['date'])) $_POST['date']= '';
if(!isset($_POST['fstate'])) $_POST['fstate']= '';
if(!isset($_POST['title'])) $_POST['title']= '';
if(!isset($_POST['ticket'])) $_POST['ticket']= '';
if(!isset($_POST['technician'])) $_POST['technician']= '';
if(!isset($_POST['userid'])) $_POST['userid']= '';
if(!isset($_POST['category'])) $_POST['category']= '';
if(!isset($_POST['subcat'])) $_POST['subcat']= '';

//compatibility between personal view and filter on category
if($_GET['category']!='') $_POST['category']= $_GET['category'];
if($_GET['subcat']!='') $_POST['subcat']= $_GET['subcat'];

// default values
if ($techread=='') $techread='%';
if ($state=='')$state='%';
if($_GET['category']=='') $_GET['category']= '%'; 
if($_GET['subcat']=='') $_GET['subcat']= '%';
if($_GET['cursor']=='') $_GET['cursor']='0'; 
if($_GET['techread']=='') $_GET['techread']='%'; 
if($_POST['criticality']=='') $_POST['criticality']= '%'; 
if($_POST['priority']=='') $_POST['priority']='%';
if($_POST['fstate']=='') $_POST['fstate']= '%'; 
if($_POST['date']=='') 
{
	if ($_GET['date_create']=='current') 
	{
		$_POST['date']=date("Y-m-d") ;
	} else {
		$_POST['date']= '%'; 
	}
}  
if($_POST['title']=='') $_POST['title']= '%'; 
if($_POST['ticket']=='') $_POST['ticket']= '%'; 
if($_POST['technician']=='') $_POST['technician']= '%'; 
if($_POST['userid']=='') $_POST['userid']= '%'; 
if($_POST['category']=='') $_POST['category']= '%'; 
if($_POST['subcat']=='') $_POST['subcat']= '%'; 

// asc or desc
if ($_GET['way']==''){$_GET['way']='ASC';} else if ($_GET['way']=='ASC') {$_GET['way']='DESC';} else if ($_GET['way']=='DESC') {$_GET['way']='ASC';}
if ($_GET['techid']=='')$_GET['techid']='$_SESSION[user_id]';

// select auto order 
if ($filter=='on' || ($_GET['state']=='%' && $_GET['order']=='')){$_GET['order']='state';}
elseif ($_GET['order']=='') $_GET['order']='priority';

///// SQL QUERY
		//conversion username to userid for filter line
		if ($_POST['userid']!='%')
		{
			$query=mysql_query("SELECT id FROM tusers WHERE firstname LIKE '%$_POST[userid]%' or lastname LIKE '%$_POST[userid]%' LIMIT 1"); 
			$row=mysql_fetch_array($query);
			$_POST['userid']=$row[0];
		}
		//Date conversion for filter line
		if ($_POST['date']!='%')
		{
			$date=$_POST['date'];
			$find='/';
			$find= strpos($date, $find);
			if ($find!=false)
			{			
				$date=explode("/",$date);
				$_POST['date']="$date[2]-$date[1]-$date[0]";
			}
		}
		/*
		// FOR NEXT DEV BUG ORDER BY subcat PB when empty case
		INNER JOIN tcategory ON  tincidents.category=tcategory.id  
		INNER JOIN tsubcat ON  tincidents.subcat=tsubcat.id  
		INNER JOIN tusers ON  tincidents.user=tusers.id  
		*/
		//
		if ($_GET['searchengine']=='1')
		{
			include "./searchengine.php";
		} else {
			$userIds = array();
			if(isset($_POST['demandeure']) && $_POST['demandeure'] != ''){
				$userIds[] = $_POST['demandeure'];
				$from .=" AND tincidents.user = ".$_POST['demandeure'];
			}
			if(isset($_POST['res']) && $_POST['res'] != ''){
				$userIds[] = $_POST['res'];
				$from .=" AND tincidents.user = ".$_POST['res'];
			}
			if(isset($_POST['groupe']) && $_POST['groupe'] != ''){
				$userIds[] = $_POST['groupe'];
				
			}
		
			$from ="
			FROM tincidents
			WHERE 
			tincidents.$profile LIKE '$_GET[techid]'
			AND	tincidents.technician LIKE '$_POST[technician]'
			AND	tincidents.state LIKE '$_GET[state]'
			AND	tincidents.techread LIKE '$_GET[techread]'
			AND	tincidents.disable='0'
			AND	(tincidents.category LIKE '$_POST[category]')
			AND	tincidents.subcat LIKE '$_POST[subcat]'
			AND	tincidents.id LIKE '$_POST[ticket]'
			AND	tincidents.user LIKE '$_POST[userid]'
			AND	tincidents.date_create LIKE '$_POST[date]'
			AND	tincidents.state LIKE '$_POST[fstate]'
			AND	tincidents.priority LIKE '$_POST[priority]'
			AND	tincidents.criticality LIKE '$_POST[criticality]'
			AND	tincidents.title LIKE '%$_POST[title]%'
			";
			if(count($userIds) > 0){
				$from .=" AND tincidents.user in (". implode(",", $userIds) .")";
			}
			if( isset($_POST['date_1']) && isset($_POST['date_2']) && $_POST['date_1'] != '' && $_POST['date_2'] != ''){
				$from .=" AND tincidents.date_create BETWEEN '".$_POST['date_1']."' AND '".$_POST['date_2']."'";
			}elseif( isset($_POST['date_1']) && isset($_POST['date_2']) && $_POST['date_1'] != '' && $_POST['date_2'] == ''){
				$from .=" AND tincidents.date_create >= '".$_POST['date_1']."'";
			}elseif( isset($_POST['date_1']) && isset($_POST['date_2']) && $_POST['date_1'] == '' && $_POST['date_2'] != ''){
				$from .=" AND tincidents.date_create <= '".$_POST['date_2']."'";
			}
			
			
		}
		$mastercount = mysql_query("SELECT COUNT(*) $from"); 
		$resultcount=mysql_fetch_array($mastercount);
		
		$masterquery = mysql_query("
		SELECT tincidents.* 
		$from
		ORDER BY 
		$_GET[order] $_GET[way],
		date_create DESC LIMIT $_GET[cursor],
		$rparameters[maxline]
		"); 
?>
<div id="filtre">
<form name="thisform" enctype="multipart/form-data" method="post" action="" id="filtreTask">
<fieldset> <legend>Filtre</legend>
				
				<?php if(isset($_POST['filterBtn'])){ ?>
					<a href="javascript:void(0)" class="toggleFilter">[-] Masquer le filtre</a>
				<?php }else{ ?>
					<a href="javascript:void(0)" class="toggleFilter">[-] Afficher le filtre</a>
				<?php } ?>
				<ul class="filterFields" <?php if(isset($_POST['filterBtn'])){ ?>style="display:block;"<?php }else{ ?> style="display:none;"<?php } ?>>
					<li>
						<?php
							$sql = "SELECT * FROM `tusers` where id in (Select responsible From tcompany) Order by firstname";
							$data = mysql_query($sql);
		
						?>
						<label>Responsables des groupes</label>
						<select name="res">
							<option value=""> ... </option>
							<?php while($row = mysql_fetch_array($data)){ ?>
							<option <?php if(isset($_POST['res']) && $_POST['res'] == $row['id']){ ?>selected="selected"<?php } ?> value="<?php echo $row['id']; ?>"><?php echo $row['firstname'].' '.$row['lastname']; ?></option>
							<?php } ?>
						</select>
					</li>
					<li>
						<?php
							$sql = "Select * From tcompany Order by nom_groupe";
							$data = mysql_query($sql);
						?>
						<label>Groupes</label>
						<select name="groupe">	
							<option value=""> ... </option>
							<?php while($row = mysql_fetch_array($data)){ ?>
								<option <?php if(isset($_POST['groupe']) && $_POST['groupe'] == $row['responsible']){ ?>selected="selected"<?php } ?> value="<?php echo $row['responsible']; ?>"><?php echo $row['nom_groupe']; ?></option>
							<?php } ?>
						</select>
					</li>
					<li>
						<?php
							$sql = "SELECT * FROM `tusers` where id in (Select user From tincidents) Order by firstname";
							$data = mysql_query($sql);
						?>
						<label>Les Demandeures</label>
						<select name="demandeure">
							<option value=""> ... </option>
							<?php while($row = mysql_fetch_array($data)){ ?>
							<option <?php if(isset($_POST['demandeure']) && $_POST['demandeure'] == $row['id']){ ?>selected="selected"<?php } ?> value="<?php echo $row['id']; ?>"><?php echo $row['firstname'].' '.$row['lastname']; ?></option>
							<?php } ?>
						</select>
					</li>
					<li>
						<label>Date 1 : </label>
						<input style="display: inline;" class="textfield" type='text' name='date_1'  value="<?php if(isset($_POST['date_1'])){ echo $_POST['date_1']; } ?>" />
						<img src="./images/calendar.png" value='Calendrier' onClick="window.open('components/mycalendar/mycalendar.php?form=thisform&amp;elem=date_1','Calendrier','width=400,height=400')" />
						
						<label>Date 2 : </label>
						<input style="display: inline;" class="textfield" type='text' name='date_2'  value="<?php if(isset($_POST['date_2'])){ echo $_POST['date_2']; } ?>" />
						<img src="./images/calendar.png" value='Calendrier' onClick="window.open('components/mycalendar/mycalendar.php?form=thisform&amp;elem=date_2','Calendrier','width=400,height=400')" />
					</li>
					<li style="text-align:right">
						<input type="submit" name="filterBtn" class="filterBtn" value="Appliquer" />
					</li>
				</ul>
	
</fieldset> 
</form>
</div>
<div class="post">
<h2>
<?php if ($_GET['searchengine']=='1') echo "Recherche: $keywords[0] "; else  echo "Liste des demandes";?> 
<span class="description">Nombre: <?php echo $resultcount[0] ?></span>
</h2>
	<table cellspacing="0" cellpadding="0" >
		<?php //*********************** FIRST LIGN *********************** ?>
		<tr class="list">
			<th scope="col" <?php if ($_GET['order']=='id') echo 'class="active"'; ?> >
				<a title="Numéro du ticket" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=id&amp;way=<?php echo $_GET['way']; ?>">
				Ticket
				<?php
				//Display way arrows
				if ($_GET['order']=='id'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<?php
			// do not diplay TECH column if technician is connected
			if ($_SESSION['profile_id']!=0 || $_SESSION['profile_id']!=4 || $_GET['techid']=='%')
			{
			echo "
			<th scope=\"col\" ";  if ($_GET['order']=='technician') echo 'class="active"'; echo ">
				<a title=\"Technicien en charge du ticket\" class=\"th\" href=\"./index.php?page=dashboard&amp;techid=$_GET[techid]&amp;state=$_GET[state]&amp;order=technician&amp;way=$_GET[way]\">
				TECH";
				//Display arrows
				if ($_GET['order']=='technician'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				echo"
				</a>
			</th>
			";
			} 
			if ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==3 || $_SESSION['profile_id']==4) {
			echo "
			<th scope=\"col\""; if ($_GET['order']=='user') echo 'class="active"'; echo ">
				<a title=\"Demandeur\" class=\"th\" href=\"./index.php?page=dashboard&amp;techid=$_GET[techid]&amp;state=$_GET[state]&amp;order=user&amp;way=$_GET[way]\">
				Demandeur";
				//Display arrows
				if ($_GET['order']=='user'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				echo"
				</a>
			</th>
			";
			}
			?>

			<th scope="col" <?php if ($_GET['order']=='category') echo 'class="active"'; ?> >
				<a title="Catégorie" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=category&amp;way=<?php echo $_GET['way']; ?>">
				Catégorie
				<?php
				//Display arrows
				if ($_GET['order']=='category'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<th scope="col" <?php if ($_GET['order']=='subcat') echo 'class="active"'; ?> >
				<a title="Sous-Catégorie" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=subcat&amp;way=<?php echo $_GET['way']; ?>">
				S-Catégorie
				<?php
				//Display arrows
				if ($_GET['order']=='subcat'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<th scope="col" <?php if ($_GET['order']=='description') echo 'class="active"'; ?> >
				<a title="Titre de la demande" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=description&amp;way=<?php echo $_GET['way']; ?>">
				Titre
				<?php
				//Display arrows
				if ($_GET['order']=='description'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<th scope="col" <?php if ($_GET['order']=='date_create') echo 'class="active"'; ?> >
				<a title="Date de création la demande" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=date_create&amp;way=<?php echo $_GET['way']; ?>">
				Date				
				<?php
				//Display arrows
				if ($_GET['order']=='date_create'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<th scope="col" <?php if ($_GET['order']=='state') echo 'class="active"'; ?> >
				<a title="état" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=state&amp;way=<?php echo $_GET['way']; ?>">
				E
				<?php
				//Display arrows
				if ($_GET['order']=='state'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<th scope="col" <?php if ($_GET['order']=='priority') echo 'class="active"'; ?> >
				<a title="Priorité 0=Urgent et 5=Trés basse" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=priority&amp;way=<?php echo $_GET['way']; ?>">
				P
				<?php
				//Display arrows
				if ($_GET['order']=='priority'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
			<th scope="col" <?php if ($_GET['order']=='criticality') echo 'class="active"'; ?> >
				<a title="Criticité" class="th" href="./index.php?page=dashboard&amp;techid=<?php echo $_GET['techid']; ?>&amp;state=<?php echo $_GET['state']; ?>&amp;order=criticality&amp;way=<?php echo $_GET['way']; ?>">
				C
				<?php
				//Display arrows
				if ($_GET['order']=='criticality'){
					if ($_GET['way']=='ASC') {echo '<img style="border-style: none" alt="img" src="./images/up.png" />';}
					if ($_GET['way']=='DESC') {echo '<img style="border-style: none" alt="img" src="./images/down.png" />';}
				}
				?>
				</a>
			</th>
		</tr>
		<?php // *********************************** FILTER LIGN ************************************** ?>
		<form name="filter" method="post">
			<tr class="green">
				<td>
					<input name="ticket"  onchange="submit();" type="text" size="6" value="<?php if ($_POST['ticket']!='%')echo $_POST['ticket']; ?>" />
				</td>			
				<?php
					//Display tech column if all demands view is selected
					if ($_SESSION['profile_id']!=0 || $_SESSION['profile_id']!=4 || $_GET['techid']=='%')
					{
						echo '
						<td>
							<select name="technician" onchange="submit()" style="width:69px">
								<option value="%"> </option>';
								$query = mysql_query("SELECT * FROM tusers WHERE profile='0' and disable='0'");
								while ($row=mysql_fetch_array($query)) 
								{
									echo "<option value=\"$row[id]\">$row[firstname]</option>";
									if ($_POST['technician']==$row['id']) echo "<option selected value=\"$row[id]\">$row[firstname]</option>";
								} 
							echo "
							</select>
						</td>";
					} 
					if ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==3 || $_SESSION['profile_id']==4) {
					
						echo "
						<td>";
							//find username 
							if ($filter=="on") {
							$query=mysql_query("SELECT * FROM tusers WHERE id LIKE '$_POST[userid]'"); 
							$row=mysql_fetch_array($query);
							} echo '
					<input name="userid"  onchange="submit();" type="text" size="11\" value="'; if ($filter=="on" && $_POST['userid']!='%') {echo $row['lastname'];} echo '" />
				</td>';
					}
				?>
				<td>
					<select name="category" onchange="submit()" style="width:83px">
						<option value="%"></option>
						<?php
						$query = mysql_query("SELECT * FROM tcategory ORDER BY name");
						while ($row=mysql_fetch_array($query)) 
						{
							echo "<option value=\"$row[id]\">$row[name]</option>";
							if ($_POST['category']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
						} 
						?>
					</select>	
				</td>
				
				<td>
					<select name="subcat" onchange="submit()" style="width:80px">
						<option value="%"></option>
						<?php
						if($_POST['category']!='%')
						{$query = mysql_query("SELECT * FROM tsubcat WHERE cat LIKE $_POST[category] ORDER BY name");}
						else
						{$query = mysql_query("SELECT * FROM tsubcat ORDER BY name");}
						while ($row=mysql_fetch_array($query))
						{
							echo "<option value=\"$row[id]\">$row[name]</option>";
							if ($_POST['subcat']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
						} 
						?>
					</select>
				</td>
				<td>
					<input name="title" onchange="submit();" type="text"  value="<?php if ($_POST['title']!='%')echo $_POST['title']; ?>" />
				</td>
				<td>
					<input name="date" onchange="submit();" type="text"  size="8" value="<?php if ($_POST['date']!='%')echo $_POST['date']; ?>" />
				</td>
				<td>
					<select name="fstate" onchange="submit()" style="width:22px" >	
						<option value=""></option>
						<?php
						$query = mysql_query("SELECT * FROM tstates ORDER BY name");
						while ($row=mysql_fetch_array($query))  {echo "<option value=\"$row[id]\">$row[name]</option>";} 
						?>
					</select>
				</td>
				<td>
					<select id="priority" name="priority" onchange="submit()" style="width:23px">
						<option value=""></option>
						<?php
						$query = mysql_query("SELECT * FROM tpriority ORDER BY number");
						while ($row=mysql_fetch_array($query)){echo "<option value=\"$row[number]\">$row[name]</option>";} 
						?>
					</select>
				</td>
				<td>
					<select id="criticality" name="criticality" onchange="submit()" style="width:23px" >
						<option value=""></option>
						<?php
						$query = mysql_query("SELECT * FROM tcriticality ORDER BY number");
						while ($row=mysql_fetch_array($query))
						{
						echo "<option value=\"$row[id]\">$row[name]</option>";
						} 
						?>
					</select>
				</td>
			</tr>
			<input name="state" type="hidden" value="<?php echo $_GET['state']; ?>" />
			<input name="filter" type="hidden" value="on" />
		</form>
		<?php
		while ($row=mysql_fetch_array($masterquery))
		{ 
			//Select name of states
			$querystate=mysql_query("SELECT * FROM tstates WHERE id LIKE $row[state]"); 
			$resultstate=mysql_fetch_array($querystate);
			//Select name of priority
			$querypriority=mysql_query("SELECT * FROM tpriority WHERE number LIKE $row[priority]"); 
			$resultpriority=mysql_fetch_array($querypriority);
			//Select name of criticality
			$querycriticality=mysql_query("SELECT * FROM tcriticality WHERE id LIKE $row[criticality]"); 
			$resultcriticality=mysql_fetch_array($querycriticality);
			//Select name of user
			$queryuser=mysql_query("SELECT * FROM tusers WHERE id LIKE '$row[user]'"); 
			$resultuser=mysql_fetch_array($queryuser);
			//Select name of technician
			$querytech=mysql_query("SELECT * FROM tusers WHERE id LIKE '$row[technician]'"); 
			$resulttech=mysql_fetch_array($querytech);
			//Select name of category
			$querycat=mysql_query("SELECT * FROM tcategory WHERE id LIKE '$row[category]'"); 
			$resultcat=mysql_fetch_array($querycat);
			//Select name of subcategory
			$queryscat=mysql_query("SELECT * FROM tsubcat WHERE id LIKE '$row[subcat]'"); 
			$resultscat=mysql_fetch_array($queryscat);
			
			//cut first letter of firstame
			$Fname=substr($resultuser['firstname'], 0, 1);
			$Ftname=substr($resulttech['firstname'], 0, 1);
			
			$rowdate= date_cnv($row['date_create']);
			
			//date hope
			$img='';
			if(!isset($row['date_hope'])) $row['date_hope']= ''; 
			$date_hope=$row['date_hope'];
			$querydiff=mysql_query("SELECT DATEDIFF(NOW(), '$date_hope') "); 
			$resultdiff=mysql_fetch_array($querydiff);
			if ($resultdiff[0]>0 && ($row['state']=='1'|| $row['state']=='2')) $img = "<img align=\"left\" border=\"0\" title=\"$resultdiff[0] jours de retard\" src=\"./images/clock.png\" />";
			
			// Display line color
				$bgcolor="";
				//query 30 days
				$query15=mysql_query("SELECT count(*) FROM `tincidents` WHERE TO_DAYS(NOW()) - TO_DAYS(date_create) >= $rparameters[lign_yellow] and TO_DAYS(NOW()) - TO_DAYS(date_create) <= 45 and (state LIKE '2' or state LIKE '1') and date_create LIKE '$row[date_create]'"); 
				$result15=mysql_fetch_array($query15);
				if ($result15[0]!=0 && ($row['state'] == '1' || $row['state'] == '2')) $bgcolor="yellow";
							
				//query 45 days and more
				$query15=mysql_query("SELECT count(*) FROM `tincidents` WHERE TO_DAYS(NOW()) - TO_DAYS(date_create) > $rparameters[lign_orange] and (state LIKE '2' or state LIKE '1') and date_create LIKE '$row[date_create]'"); 
				$result15=mysql_fetch_array($query15);
				if ($result15[0]!=0 && ($row['state'] == '1' || $row['state'] == '2')) $bgcolor="orange";
				
				//query date is today display green
				if (date('Y-m-d')==$row['date_create']) $bgcolor="green";
				
				//if techncian unread
				if ($row['techread']==0) $bgcolor="red";
				
				// default bg color
				if ($bgcolor=="") $bgcolor="blue";
				
			//if text is too long cut
			$title=$row['title'];
			$uname=$resultuser['lastname'];
				
			//attach file
			$attach='';
			if(!isset($row['img1'])) $row['img1']= ''; 
				
			if($row['img1']!='') $attach= "<img border=\"0\" title=\"$row[img1]\" src=\"./images/attach_min.png\"/>";
				
			if ($profile=='technician') {$pageticket='ticket';} else {$pageticket='ticket_u';}
			echo "
			<tr class=\"$bgcolor\" onclick=\"document.location='./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]'\">
			  <td style=\"width:30px;\"><center><a class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]\">$img $row[id]</a></center></td>
			 "; 
			 if ($_SESSION['profile_id']!=0 || $_SESSION['profile_id']!=4 || $_GET['techid']=='%') 
			 {
				echo "<td ><center><a class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\">$Ftname. $resulttech[lastname] </a></center></td>";
			 } 
			 if ($_SESSION['profile_id']==0 ||  $_SESSION['profile_id']==4 ||$_SESSION['profile_id']==3 ) 
			 {
				echo "<td><a class=\"td\" title=\"Tel: $resultuser[phone] \" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\">$Fname. $uname </a></td>";
			 }
			 echo "
			  <td ><a class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\">$resultcat[name]</a></td>
			  <td ><a class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\">$resultscat[name]</a></td>
			  <td><a class=\"td\" title=\"$row[title] \" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\">$title $attach</a></td>
			  <td ><a class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\">$rowdate</a></td>
			  <td ><a class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\"> <center><img style=\"border-style: none\" alt=\"state\" title=\"$resultstate[name]\" src=\"./images/$resultstate[id].png\" /></center> </a></td>
			  <td><center><a title=\"$resultpriority[name]\" class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\" >$row[priority]</a></center></td>
			  <td><a title=\"$resultcriticality[name]\" class=\"td\" href=\"./index.php?page=$pageticket&amp;id=$row[id]&amp;state=$_GET[state]&amp;techid=$_GET[techid]\" > <center><img style=\"border-style: none\" alt=\"$resultcriticality[name]\" src=\"./images/critical_$resultcriticality[color].png\" /></a></center></td>
			</tr>
			";
		}
		?>
	</table>	
	<?php
	//Multi-pages link
	if  ($resultcount[0]>$rparameters['maxline'])
	{
		//count number of page
		$pagenum=ceil($resultcount[0]/$rparameters['maxline']);
		// asc or desc
		if ($_GET['way']==''){$_GET['way']='ASC';} else if ($_GET['way']=='ASC') {$_GET['way']='DESC';} else if ($_GET['way']=='DESC') {$_GET['way']='ASC';}
		echo "<center>";
		for ($i = 1; $i <= $pagenum; $i++) {
			if ($i==1) $_GET['cursor']=0;
			if($_GET['searchengine']==1)
			{echo "<span class=\"pagination\"> <a href=\"index.php?page=dashboard&amp;techid=$_GET[techid]&amp;state=$_GET[state]&amp;order=$_GET[order]&amp;way=$_GET[way]&amp;cursor=$_GET[cursor]&amp;searchengine=1&amp;keywords=$keywords[0]\">&nbsp;$i&nbsp;</a></span> ";}
			else
			{echo "<span class=\"pagination\"><a href=\"index.php?page=dashboard&amp;techid=$_GET[techid]&amp;state=$_GET[state]&amp;order=$_GET[order]&amp;way=$_GET[way]&amp;cursor=$_GET[cursor]\">&nbsp;$i&nbsp;</a></span> ";}
			$_GET['cursor']=$i*$rparameters['maxline'];
		}
		echo "</center>";
	}
	?>
</div>
<?php
// Date conversion
function date_cnv ($date) 
{return substr($date,8,2) . "/" . substr($date,5,2) . "/" . substr($date,0,4);}

// Display events popup
if(!isset($displayevent)) $displayevent= ''; 
$q = mysql_query("SELECT * FROM `tevents` WHERE technician LIKE '$uid' and disable='0' and type='1'"); 
while ($event=mysql_fetch_array($q))
{
	$devent=explode(" ",$event['date_start']);
	//day check
	if ($devent[0]<=$daydate) 
	{
		//hour check
		$currenthour=date("H:i:s");
		$eventhour=explode(" ",$event['date_start']);
		if ($currenthour>$eventhour[1])
		{
			$displayevent= "<a title=\"Planifié aujourd'hui é $devent[1]\" href=\"./index.php?page=ticket&id=$event[incident]\"><img  src=\"./images/event.png\" /> <font color=\"FFFFFF\">Rappel ticket: $event[incident]</font></a>";
			$eventincident=$event['incident'];
			$eventtime=$devent[1];
			$eventid=$event['id'];
			echo "
			<script type=\"text/javascript\"> 
				$().ready(function() { 
					$('#dialog').jqm(); 

						$('#dialog').jqmShow(); 
						return false; 
				}); 
			</script> 
			<div class=\"jqmWindow\" id=\"dialog\"> 
			";
			include "./event.php"; 
			echo"
			</div>
			";
		}
	}
}
$q=mysql_query("SELECT * FROM `tusers` WHERE id LIKE '$uid'");
$r=mysql_fetch_array($q);

// Popup to display message to change user password.
if ($r['chgpwd']=='1')
{
		echo "
		<script type=\"text/javascript\"> 
			$().ready(function() { 
				$('#dialog').jqm(); 

					$('#dialog').jqmShow(); 
					return false; 
			}); 
		</script> 
		<div class=\"jqmWindow\" id=\"dialog\"> 
		";
		include "./modify_pwd.php"; 
		echo"
		</div>
		";
}
?>
<script type="text/javascript">
        // jQuery en action
        jQuery.noConflict();
		jQuery(document).ready(function(){
		// ------------------------------ //
			jQuery('.toggleFilter').toggle(
				<?php if(isset($_POST['filterBtn'])){ ?>
					function(){
						jQuery('.filterFields').hide();
						jQuery(this).text('[+] Afficher le filtre.');
					},function(){
						jQuery('.filterFields').show();
						jQuery(this).text('[-] Masquer le filtre.');
					}
				<?php }else{ ?>
					function(){
						jQuery('.filterFields').show();
						jQuery(this).text('[-] Masquer le filtre.');
					},function(){
						jQuery('.filterFields').hide();
						jQuery(this).text('[+] Afficher le filtre.');
					}
				<?php } ?>
			);
		// ------------------------------ //
		});
</script>

