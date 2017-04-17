"use strict"

$(document).ready(function(){
  console.log("All elements loaded");
  //Admin Page JQuery Functions
  hideAll();
  disableButtons();
  $("#results_btn").click(showResults);
  $("#gallery_btn").click(showGallery);
  $("#parties_btn").click(showParties);
  $("#constituencies_btn").click(showConstituencies);
  $("#login_button").click(adminLogin);

});

function disableButtons(){
  $("#results_btn").prop('disabled', true);
  $("#gallery_btn").prop('disabled', true);
  $("#parties_btn").prop('disabled', true);
  $("#constituencies_btn").prop('disabled', true);
}

function enableButtons(){
  $("#results_btn").prop('disabled', false);
  $("#gallery_btn").prop('disabled', false);
  $("#parties_btn").prop('disabled', false);
  $("#constituencies_btn").prop('disabled', false);
}

function hideAll() {
  $("#constituencies").hide(500);
  $("#gallery").hide(500);
  $("#results").hide(500);
  $("#parties").hide(500);
}

function showResults() {
  populateResultsTbl();
  hideAll();
  $("#AdminLogin").hide(500);
  $("#results").show(500);
}

function showParties() {
  populatePartiesTbl();
  $("#AdminLogin").hide(500);
  hideAll();
  $("#parties").show(500);
}

function showGallery() {
  $("#AdminLogin").hide(500);
  hideAll();
  $("#gallery").show(500);
}

function showConstituencies() {
  populateConstituenciesTbl();
  $("#AdminLogin").hide(500);
  hideAll();
  $("#constituencies").show(500);
}

function adminLogin(){
	
	var LoginRegistration = $("#user").val();
    var password = $("#pass").val();
	
	var data = {
		
		'admin': LoginRegistration,
		'pass': password
	
	};
	//console.log(data);
	$.post("login.php", data, function (success) {
		
		//console.log(success);
		if(success == 1){
		
			swal("E.V.S Administrator", "Successfully Logged In.", "success");
			enableButtons();
			showResults();	
			
		}
		
		else{
			
			swal("E.V.S Administrator", "Wrong ID or Password was Entered.", "error");
			$("#user").val("");
			$("#pass").val("");
		
		}
		
  });


	return false;
	
}


function releaseResults() {
  var data = {
    'ReleaseResults' : 1
  };
  $.post("/ElectronicVotingSystem/admin/index.php", data, function (response) {
    swal("Success", "The election results has been released", "success");
  });
  return false;
}

function toggleConstModal(constituency) {
  $("#constEditModal").modal('show');
  $("#oldConst").text(constituency);
}

function togglePollModal(constituency) {
  $('#myModal').modal('show');
  $("#pollSubTitle").text(constituency);
  $("#const").val(constituency);
}

function togglePartiesModal(acronym) {
  $('#partyEditModal').modal('show');
  $("#oldAcronym").text(acronym);
}

function updatePoll() {
  var constituency = $("#const").val();
  var NNP = $("#nnp").val();
  var NDC = $("#ndc").val();
  var data = {
      'Constituency' : constituency,
      'NNP' : NNP,
      'NDC' : NDC
  };
  $.post("/ElectronicVotingSystem/admin/index.php", data, function (response) {
    swal("Success", "Poll updated successfully", "success");
    populateResultsTbl();
  });
  $("#nnp").val(0);
  $("#ndc").val(0);
  $('#myModal').modal('hide');
  return false;
}

function updateParty() {
  var oldAcronym = $("#oldAcronym").text();
  var newAcronym = $("#newAcronym").val();
  var party = $("#party").val();
  var color = $("#color").val();
  var data = {
      'OldAcronym' : oldAcronym,
      'NewAcronym' : newAcronym,
      'Color' : color,
      'Party' : party
  };
  $.post("/ElectronicVotingSystem/admin/index.php", data, function (response) {
    populateConstituenciesTbl();
    swal("Success", "Party updated successfully", "success");
  });
  $("#party").val("");
  $("#color").val("");
  $("#newAcronym").val("");
  $("#oldAcronym").text("");
  $('#partyEditModal').modal('hide');
  return false;
}

function updateConstituency() {
  var oldConstituency = $("#oldConst").text();
  var newConstituency = $("#newConst").val();
  var data = {
      'OldConstituency' : oldConstituency,
      'NewConstituency' : newConstituency
  };
  $.post("/ElectronicVotingSystem/admin/index.php", data, function (response) {
    populateConstituenciesTbl();
    swal("Success", "Constituency updated successfully", "success");
  });
  $("#oldConst").val("");
  $('#constEditModal').modal('hide');
  return false;
}

function deleteConstituency(constituency) {
  var data = {
    'Constituency' : constituency,
    'DeleteConstituency' : 1
  }
  swal({
    title: "Are you sure?",
    text: "You will not be able to recover "+constituency+" and all its data!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, delete it! ",
    closeOnConfirm: false
  },
  function() {
    $.post("/ElectronicVotingSystem/admin/index.php", data, function (response) {
      populateConstituenciesTbl();
      swal("Deleted!", constituency+" has been deleted successfully", "success");
    });
  });
}

function deleteParty(party) {
  var data = {
    'Party' : party,
    'DeleteParty' : 1
  }
  swal({
    title: "Are you sure?",
    text: "You will not be able to recover "+party+" and all its data!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, delete it! ",
    closeOnConfirm: false
  },
  function() {
    $.post("/ElectronicVotingSystem/admin/index.php", data, function (response) {
      populatePartiesTbl();
      swal("Deleted!", "Party has been deleted successfully", "success");
    });
  });
}

function addConstituency() {
  var constituency = $("#addConst").val();
  var data = {
      'Constituency' : constituency,
      'AddConstituency' : 1
  };
  $.post("/ElectronicVotingSystem/admin/index.php", data, function (data, status, xhr) {
    populateConstituenciesTbl();
    swal("Success", constituency+" has added successfully", "success");
  }).fail(function(){
    swal("Error Adding Constituency", "Please check your internet connection", "error");
  });
  $("#addConst").val("");
  return false;
}

function addParty() {
  var name = $("#name").val();
  var acronym = $("#acronym").val();
  var color = $("#color").val();
  var data = {
      'Name' : name,
      'Acronym' : acronym,
      'Color' : color
  };
  $.post("/ElectronicVotingSystem/admin/index.php", data, function (data, status, xhr) {
    populatePartiesTbl();
    swal("Success", "Party has added successfully", "success");
  }).fail(function(){
    swal("Error Adding Party", "Please check your internet connection", "error");
  });
  $("#name").val("");
  $("#acronym").val("");
  $("#color").val("");
  return false;
}

function getVotes(){
  var votes = null;
  $.ajax({
      type: "get",
      dataType: "json",
      url: '/ElectronicVotingSystem/communication/results.php',
      async: false,
      success: function(data){
        votes = data;
      }
  });
  return votes;
}

function getParties() {
  var parties = null;
  $.ajax({
    type: "get",
    dataType: "json",
    url: '/ElectronicVotingSystem/communication/parties.php',
    async: false,
    success: function(data){
      parties = data;
    }
  });
  return parties;
}

function populateConstituenciesTbl() {
  var votes = getVotes()[0];
  //console.log(votes);
  var html = "";
  votes.forEach(function(rec){
    html += "<tr><td>"+rec['Constituency']+"</td>";
    html += '<td><button class="btn btn-warning" onclick="toggleConstModal('+"'"+rec['Constituency']+"');"+'"><span class="glyphicon glyphicon-edit"></span></button></td>';
    html += '<td><button class="btn btn-danger" onclick="deleteConstituency('+"'"+rec['Constituency']+"');"+'"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
  });
  $("#constituencyTbl").html(html);
}

function populatePartiesTbl() {
  var parties = getParties();
  console.log(parties);
  var html = "";
  parties.forEach(function(rec){
    html += "<tr><td>"+rec['Party']+"</td>";
    html += "<td>"+rec['Acronym']+"</td>";
    html += '<td style="background-color:'+rec['Color_Code']+';"></td><td></td>';
    html += '<td><button class="btn btn-warning" onclick="togglePartiesModal('+"'"+rec['Acronym']+"');"+'"><span class="glyphicon glyphicon-edit"></span></button></td>';
    html += '<td><button class="btn btn-danger" onclick="deleteParty('+"'"+rec['Acronym']+"');"+'"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
  });
  $("#partiesTbl").html(html);
}

function populateResultsTbl() {
  var votes = getVotes();
  var votes_online = votes[0];
  var votes_offline = votes[1];
  console.log(votes);
  var html = "";
  for (var i = 0; i < votes_online.length; i++) {
    var NNPTotal = parseInt(votes_online[i]['NNP']) + parseInt(votes_offline[i]['NNP']);
    var NDCTotal = parseInt(votes_online[i]['NDC']) + parseInt(votes_offline[i]['NDC']);
    html += "<tr><td>"+votes_online[i]['Constituency']+"</td>";
    html += "<td><span class='badge'>"+votes_online[i]['NNP']+"</span></td>";
    html += "<td><span class='badge'>"+votes_online[i]['NDC']+"</span></td>";
    html += "<td><span class='badge'>"+votes_offline[i]['NNP']+"</span></td>";
    html += "<td><span class='badge'>"+votes_offline[i]['NDC']+"</span></td>";
    html += "<td><span class='badge'>"+NNPTotal+"</span></td>";
    html += "<td><span class='badge'>"+NDCTotal+"</span></td>";
    html += '<td><button class="btn btn-warning" onclick="togglePollModal('+"'"+votes_online[i]['Constituency']+"');"+'"><span class="glyphicon glyphicon-edit"></span></button></td></tr>';
  }
  
  $("#tblData").html(html);
}