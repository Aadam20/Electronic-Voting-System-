<?php
	
if (isset($_POST['Constituency']) && isset($_POST['NNP']) && isset($_POST['NDC'])) {
	$constituency = $_POST['Constituency'];
	$NNP = $_POST['NNP'];
	$NDC = $_POST['NDC'];
	$res = updatePoll($constituency, $NNP, $NDC);
}

if (isset($_POST['Constituency']) && isset($_POST['DeleteConstituency'])) {
	$constituencyDel = $_POST['Constituency'];
	$res = deleteConstituency($constituencyDel);
} 

if (isset($_POST['Party']) && isset($_POST['DeleteParty'])) {
	$partyDel = $_POST['Party'];
	$res = deleteParty($partyDel);
} 

if (isset($_POST['Constituency']) && isset($_POST['AddConstituency'])) {
	$constituencyAdd = $_POST['Constituency'];
	$res = addConstituency($constituencyAdd);
}

if (isset($_POST['OldConstituency']) && isset($_POST['NewConstituency'])) {
	$oldConstituency = $_POST['OldConstituency'];
	$newConstituency = $_POST['NewConstituency'];
	$res = editConstituency($oldConstituency, $newConstituency);
}

if (isset($_POST['Name']) && isset($_POST['Acronym']) && isset($_POST['Color'])) {
	$name = $_POST['Name'];
	$acronym = $_POST['Acronym'];
	$color = $_POST['Color'];
	$res = addParty($name, $acronym, $color);
}

if (isset($_POST['OldAcronym']) && isset($_POST['NewAcronym']) && isset($_POST['Party']) && isset($_POST['Color'])) {
	$oldAcronym = $_POST['OldAcronym'];
	$newAcronym = $_POST['NewAcronym'];
	$color = $_POST['Color'];
	$party = $_POST['Party'];
	$res = editParty($party, $oldAcronym, $newAcronym, $color);
}

if (isset($_POST['ReleaseResults'])) {
	releaseResults();
}

function getDBConnection(){
	try{ // Uses try and catch to handle any unforeseen errors
		$db = new mysqli("localhost","root","","evsdatabase");
		if ($db == null && $db->connect_errno > 0) return null;
		return $db;
	}catch(Exception $e){ } // We currently do nothing in the catch, but later we can log
	return null;
}

function releaseResults() {
	$success = False;
	$db = getDBConnection();
	$sql = "UPDATE results SET released = 1 WHERE released = 0;";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	return $success;
}

function updatePoll($constituency, $NNP,  $NDC) {
	$success = False;
	$db = getDBConnection();
	$sql = "UPDATE votes_offline SET NNP = $NNP, NDC = $NDC WHERE Constituency = '$constituency';";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	return $success;
}

function deleteConstituency($constituency) {
	$success = False;
	$db = getDBConnection();
	$sql = "DELETE FROM `votes_online` WHERE `Constituency` = '$constituency';";
	$sql2 = "DELETE FROM `votes_offline` WHERE `Constituency` = '$constituency';";
	if ($db) {
		$success = $db->query($sql);
		$success = $db->query($sql2);
		$db->close();
	}
	return $success;
}

function deleteParty($party) {
	$success = False;
	$db = getDBConnection();
	$sql = "ALTER TABLE votes_online DROP COLUMN $party;";
	$sql2 = "ALTER TABLE votes_offline DROP COLUMN $party;";
	$sql3 = "DELETE FROM `parties` WHERE `Acronym` = '$party';";
	if ($db) {
		$success = $db->query($sql);
		$success = $db->query($sql2);
		$success = $db->query($sql3);
		$db->close();
	}
	return $success;
}

function addConstituency($constituency) { 
	$success = False;
	$db = getDBConnection();
	$sql = "INSERT INTO votes_offline (Constituency) VALUES ('$constituency');";
	$sql2 = "INSERT INTO votes_online (Constituency) VALUES ('$constituency');";
	if ($db) {
		$success = $db->query($sql);
		$success = $db->query($sql2);
		$db->close();
	}
	return $success;
}

function addParty($name, $acronym, $color) { 
	$success = False;
	$db = getDBConnection();
	$sql = "INSERT INTO parties (Party, Acronym, Color_Code) VALUES ('$name', '$acronym', '$color');";
	$sql2 = "ALTER TABLE votes_online ADD $acronym int(11) DEFAULT '0';";
	$sql3 = "ALTER TABLE votes_offline ADD $acronym int(11) DEFAULT '0';";
	if ($db) {
		$success = $db->query($sql);
		$success = $db->query($sql2);
		$success = $db->query($sql3);
		$db->close();
	}
	return $success;
}

function editConstituency($old, $new) {
	$success = False;
	$db = getDBConnection();
	$sql = "UPDATE `votes_offline` SET  `Constituency` =  '$new' WHERE  `Constituency` =  '$old';";
	$sql2 = "UPDATE `votes_online` SET  `Constituency` =  '$new' WHERE  `Constituency` =  '$old';";
	if ($db) {
		$success = $db->query($sql);
		$success = $db->query($sql2);
		$db->close();
	}
	return $success;
}

function editParty($party, $oldAcronym, $newAcronym, $color) {
	$success = False;
	$db = getDBConnection();
	$sql = "UPDATE `parties` SET  `Party` =  '$party', `Acronym` = '$newAcronym', `Color_Code` = $color WHERE  `Acronym` =  '$oldAcronym';";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	return $success;
}
?> 

<!DOCTYPE html>

<!--suppress HtmlUnknownTarget -->
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">
  <title>Electronic Voting System</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="../css/mycss.css">
  <link rel="stylesheet" href="css/main.css">
  <link href="../sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css">
  <link href="../font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

<div class="col-md-1"></div>

<div class= "col-md-10" id= "main">

<!-- Header -->
  <div class="row scheme top-buffer">
      <div class="jumbotron scheme">
        <div class="container text-center">
          <h1 class="header-1">Online Voting</h1>
                  <p><i>Quick and Convenient Voting at Your Fingertips.</i><br>
                 <i>Your Vote is Secured Using Advance Encryption Technologies.</i><br>
               <i>Please Be Aware of your Surroundings while interacting with the E.V.S.</i></p>

            <div class="row"> 

              <div class="col-md-3">
           			<button id="gallery_btn" class="btn btn-lg btn-block btn-info">
					<i class="fa fa-picture-o" aria-hidden="true"></i> Gallery</button>
              </div>

							<div class="col-md-3">
                <button id="results_btn" class="btn btn-lg btn-block btn-info">
                  <i class="fa fa-users fa-lg"></i> Results</button>
              </div>

							<div class="col-md-3">
                <button id="parties_btn" class="btn btn-lg btn-block btn-info">
                  <i class="fa fa-globe" aria-hidden="true"></i> Parties</button>
              </div>

              <div class="col-md-3">
                <button id="constituencies_btn" class="btn btn-lg btn-block btn-info">
                  <i class="fa fa-globe" aria-hidden="true"></i> Constituencies</button>
              </div>

            </div>
      </div>
  </div>
 </div>
  
		<div class="row text-center top-buffer scheme" id="AdminLogin">

          <form class="form-horizontal" id="Login-Form" onsubmit="return false;" method="POST">
            <fieldset>

              <!-- Form Name -->
              <legend>Administrator Login</legend>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="user">Registration Number</label>
                <div class="col-md-6">
                  <input id="user" name="user" type="number" placeholder="Enter ID Here" class="form-control input-md" required="">

                </div>
              </div>

              <!-- Password input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="pass">Password</label>
                <div class="col-md-6">
                  <input id="pass" name="pass" type="password" placeholder="Enter Password Here" class="form-control input-md" required="">

                </div>
              </div>

              <!-- Button -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="saveL"></label>
                <div class="col-md-4">
                  <button id="login_button" name="saveL" class="btn btn-info btn-block">Login</button>
                </div>
              </div>

            </fieldset>
          </form>

      </div>

  
  <!-- Constituencies -->
 <div class="row top-buffer scheme" id="constituencies">

    <!-- Modal that hadles Constituency Edit -->
  	<div class="modal fade" id="constEditModal" role="dialog">
		  <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Edit Constituency</h4>
		        </div>
		        <div class="modal-body">
							<form id="editConstForm" class="form-horizontal" onsubmit="return updateConstituency();">
								<fieldset>
								<label id="oldConst"></label>
								<!-- Text input-->
								<div class="form-group">
								  <!--label class="control-label" for="newConst">New Constituency Name</label-->
								  <div class="col-md-12">
								  <input id="newConst"  type="text" placeholder="Enter new constituency name here" class="form-control input-md">
								  </div>
								</div>
								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="editConstBtn"></label>
								  <div class="col-md-4">
								    <button id="editConstBtn" name="editConstBtn" class="btn btn-primary">Save</button>
								  </div>
								</div>
								</fieldset>
							</form>
		        </div>
		      </div>
		  </div>
	</div>

	<!-- Constituency Table -->
  	<div class="col-md-2"></div>
			  <div class="col-md-8 jumbotron" padding-top="5px" >
				<h2 align="center">Voting Constituencies</h2>

				<table style="width:100%" class="table table-striped">
				  <thead>
				  	<tr text-align="centre">
					  <th>Constituency</th>
					  <th>Edit</th>
					  <th>Delete</th>
					</tr>
				  </thead>
				  <tbody id="constituencyTbl">
				  </tbody>
				</table>
			   
			  <form class="form-horizontal" onsubmit="return addConstituency();">
			   <fieldset>
			    <label for="addConst" class="col-form-label">New Constituency</label>
				<div class="form-group row">
			      <div class="col-sm-8">
			        <input type="text" class="form-control" name="addConst" id="addConst" placeholder="Enter New Constituency">
			      </div>
			      <div class="col-sm-2"><button id="addConstBtn" class="btn btn-info btn-block">Add</button></div>
			    </div>	
			   </fieldset>
			  </form>
			</div>
  </div>

  <!-- Parties -->
  <div class="row top-buffer scheme" id="parties">

  	<!-- Modal that hadles Constituency Edit -->
  	<div class="modal fade" id="partyEditModal" role="dialog">
		  <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Edit Party</h4>
		        </div>
		        <div class="modal-body">
							<form id="editPartyForm" class="form-horizontal" onsubmit="return updateParty();">
								<fieldset>
								<label id="oldAcronym"></label>
								<!-- Text input-->
								<div class="form-group">
								  <!--label class="control-label" for="newConst">New Constituency Name</label-->
								  <div class="col-md-12">
								  <input id="party"  type="text" placeholder="Party Name" class="form-control input-md">
								  </div>
								</div>
								<div class="form-group">
								  	<div class="col-md-6">
								  	<input id="newAcronym"  type="text" placeholder="Acronym" class="form-control input-md">
								  	</div>
								  	<div class="col-md-6">
								  		<input id="color"  type="text" placeholder="Color" class="form-control input-md">
								  	</div>
								  </div>
								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="editPartyBtn"></label>
								  <div class="col-md-4">
								    <button id="editPartyBtn" name="editPartyBtn" class="btn btn-primary">Save</button>
								  </div>
								</div>
								</fieldset>
							</form>
		        </div>
		      </div>
		  </div>
	</div>

  	<!-- Parties Table -->
  	<div class="col-md-2"></div>
			  <div class="col-md-8 jumbotron" padding-top="5px" >
				<h2 align="center">Political Parties</h2>

				<table style="width:100%" class="table table-striped">
				  <thead>
				  	<tr text-align="centre">
					  <th>Party</th>
					  <th>Acronym</th>
					  <th style="width:5%;">Color</th>
					  <th></th>
					  <th>Edit</th>
					  <th>Delete</th>
					</tr>
				  </thead>
				  <tbody id="partiesTbl">
				  </tbody>
				</table>
			   
			  <form class="form-horizontal" onsubmit="return addParty();">
			   <fieldset>
			    <label for="addParty" class="col-form-label">New Political Party</label>
				<div class="form-group row">
			      <div class="col-sm-5">
			        <input type="text" class="form-control" name="name" id="name" placeholder="Party Name">
			      </div>
			      <div class="col-sm-2">
			        <input type="text" class="form-control" name="acronym" id="acronym" placeholder="Acronym">
			      </div>
			      <div class="col-sm-2">
			        <a class=info>
			        <input type="text" class="form-control" name="color" id="color" placeholder="Color">
			        <span>For custom color enter color rgb hex code</span></a>
			      </div>
			      <div class="col-sm-2"><button id="addPartyBtn" class="btn btn-info btn-block">Add</button></div>
			    </div>	
			   </fieldset>
			  </form>
			</div>
  </div>

  <!-- Gallery -->
  <div class="row top-buffer scheme" id="gallery">

  </div>


  <!-- Results -->
  <div class="row top-buffer scheme" id="results">

			<div class="modal fade" id="myModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Update Poll</h4>
		        </div>
		        <div class="modal-body">
							<form class="form-horizontal" onsubmit="return updatePoll();">
								<fieldset>
								<label id="pollSubTitle"></label>
								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-3 control-label" for="nnp">NNP</label>
								  <div class="col-md-6">
								  <input id="nnp"  type="number" placeholder="NNP" min="0" class="form-control input-md">
								  </div>
								</div>
								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-3 control-label" for="ndc">NDC</label>
								  <div class="col-md-6">
								  <input id="ndc"  type="number" placeholder="NDC" min="0" class="form-control input-md">
								  </div>
								</div>

								<input type="hidden" name="const" id="const" value="">

								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="updatebut"></label>
								  <div class="col-md-4">
								    <button id="updatebut" name="updatebut" class="btn btn-primary">Save</button>
								  </div>
								</div>
								</fieldset>
							</form>
		        </div>
		      </div>
		    </div>
		  </div>

			<div class="col-md-2"></div>
			  <div class="col-md-8 jumbotron" padding-top="5px" >
				<h2 align="center">Votes per Constituency</h2>

				<table style="width:100%" class="table table-striped">
				  <thead>
				  	<tr text-align="centre">
					  <th></th>
					  <th colspan="2">Electronic</th>
					  <th colspan="2">Traditional</th>
					  <th colspan="2">Total</th>
					  <th></th>
					</tr>
				  </thead>
				    <tr>
				      <th>Constituency</th>
				      <th>NNP</th>
				      <th>NDC</th>
				      <th>NNP</th>
				      <th>NDC</th>
				      <th>NNP</th>
				      <th>NDC</th>
				      <th>Update</th>
				    </tr>
				  <tbody id="tblData">
				  </tbody>
				</table>
			   <button class="btn btn-primary" onclick="return releaseResults();"><span class="glyphicon glyphicon-globe"></span> Release Results</button>	
			  </div>
  </div>

  <!-- Footer -->
  <div class="row text-center top-buffer scheme">
	<a href="" id="logo"><img class="img-responsive" src="../images/Flag_Grenada.gif"></a>
	<p id="logo_n">This System was brought to you by<span id="signature"> Konnect</span>, Online Voting System
	<br>Copyright © 2016 Grenada's National Election Board - All Rights Reserved</p>
  </div>

</div>

<div class="col-md-1"></div>

<script src="../bower_components/jquery/dist/jquery.js"></script>
<script src="../sweetalert-master/dist/sweetalert.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="js/admin.js"></script>

</body>

</html>
