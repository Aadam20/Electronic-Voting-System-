"use strict"

$(document).ready(function(){
  console.log("All elements loaded");
  //Client Page JQuery Functions
  hideForms();

  $("#register_button").click(displayRegistration);
  $("#content_button").click(displayContent);
  $("#vote_button").click(displayVote);
  $("#scan_portrait").click(scanPortrait);
  $("#verify_portrait").click(verifyPortrait);
  $("#swap_camera").click(switchToCamera);
  $("#save_snapshot").click(completeRegistration); //Will be changed to KairosEnroll once database is established.


  $("#K_hack").click(KairosRemove); //Testing Feature To Remove Facial Portrait From KAIROS Database.


  //Voting Banners
  $("input[name=ndc_vote]").addClass("hide_radio");
  $("input[name=nnp_vote]").addClass("hide_radio");

  $("label[for='ndc']").click(function(){
      $(this).addClass("selectNDC");
      $("label[for='nnp']").removeClass("selectNNP");
  });

  $("label[for='nnp']").click(function(){
      $(this).addClass("selectNNP");
      $("label[for='ndc']").removeClass("selectNDC");
  });


  loadConstituencies();

});

/*CLIENT FUNCTIONS DEFINES*/

function hideForms(){

  $("#K_hack").hide();

  $("#graph").hide();
  $("#Login").hide();
  $("#image_capture").hide();
  $("#Registration").hide();
  $("#swap_camera").hide();
  $("#save_snapshot").hide();
  $("#scan_portrait").hide();
  $("#verify_portrait").hide();
  $("#snap_shot").hide();
}


function displayCapture(){
  $("#image_capture").show(500);
  $("#Login").hide(500);
  $("#Registration").hide(500);
}

function displayVote(){
  $("#Login").show(500);
  $("#Registration").hide(500);
  $("#Content").hide(500);
  $("#image_capture").hide(500);
  $("#scan_portrait").hide(100);
  $("#verify_portrait").hide(100);
}

function displayRegistration(){
  $("#Registration").show(500);
  $("#Content").hide(500);
  $("#Login").hide(500);
  $("#image_capture").hide(500);
  $("#scan_portrait").hide(100);
  $("#verify_portrait").hide(100);
}

function displayContent(){

  $.ajax({

	type: "POST",
	url: '/ElectronicVotingSystem/communication/CheckResults.php',
	async: false,


	success: function(released){


	if(released == 1){

	$("#register_button").prop("disabled",true);
	$("#vote_button").prop("disabled",true);

	$("#Content").hide(500);
	$("#Registration").hide(500);
	$("#Login").hide(500);
	$("#image_capture").hide(500);
	$("#scan_portrait").hide(100);
	$("#verify_portrait").hide(100);
	$("#graph").show(500);

	}

	if(released == 0){

	$("#Content").show(500);
	$("#Registration").hide(500);
	$("#Login").hide(500);
	$("#image_capture").hide(500);
	$("#scan_portrait").hide(100);
	$("#verify_portrait").hide(100);


	}

	}


  });

}

function switchToCamera(){

  $("#snap_shot").fadeOut();
  $("#camera").fadeIn(700);
  $("#swap_camera").hide(400);
  $("#save_snapshot").hide(400);

}

function scanPortrait(){

  takeSnapShot();
  $("#camera").slideUp(400);
  $("#snap_shot").fadeIn(500);
  KairosDetect();

}

function verifyPortrait(){

    takeSnapShot();
    $("#camera").slideUp(400);
    $("#snap_shot").fadeIn(500);
    VerifyByKairos();

}

function openMenu(){

  $("#Menu").css("width", "200px");
  $("#main").css("margin-left", "200px");

}

function closeMenu(){

  $("#Menu").css("width", "0");
  $("#main").css("margin-left", "0");

}

function vote(){
  var registrationNumber = $("#user").val();
  var partySelected = getPartySelection();

  if(partySelected == false){
    swal("E.V.S Warning", "Please Ensure That a Party was Selected", "warning");
    return;
  }

  var votingData = {

    'RegistrationNumber': registrationNumber,
    'Party': partySelected

  };

  swal({
    title: "E.V.S Confirmation",
    text: "Please Ensure that Correct Party was chosen\n Before Proceeding",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3076da",
    confirmButtonText: "Confirm",
    closeOnConfirm: false
    },
    function(){
      clearLogin();

      $.ajax({

        type: "POST",
        url: '/ElectronicVotingSystem/communication/CastVote.php',
        data: votingData,
        async : false,

        success: function(votingSuccess){

            if(votingSuccess == 1){

              $("#VotingModal").modal("hide");
			  blockchainWalletTransaction(partySelected);
              swal("E.V.S. Response", "Your Vote has been Successfully Placed.", "success");

            }
            else
            if(votingSuccess == 0){

              swal("E.V.S Response", "An Error occurred and your Vote was not Placed,\n Please Try Again.", "error");

            }
            else{

              swal("E.V.S Response", "Lost Connection To Database.\nPlease Ensure There is an Internet Connection.", "error");

            }

        }


      });

  });

  return false;
}

function getPartySelection(){
  if($("label[for='ndc']").hasClass("selectNDC"))
    return "NDC";
  if ($("label[for='nnp']").hasClass("selectNNP"))
    return "NNP";
  return false;
}

function login(){

      var LoginRegistration = $("#user").val();
      var password = $("#pass").val();

      var loginData = {

        'RegistrationNumber': LoginRegistration,
        'Password': password

      };

      $.ajax({

        type: "POST",
        url: '/ElectronicVotingSystem/communication/VotingProcess.php',
        data: loginData,
        async : false,

        success: function(loginSuccess){

            if(loginSuccess == 1){
			  startWebCam();
              swal("E.V.S Response", "Login Successful", "success");
              $("#verify_portrait").show(200)
              displayCapture();
            }
            else
            if(loginSuccess == 0){
              swal("E.V.S Response", "Login Error\nPlease Ensure Registration Number and Password\n are entered correctly.", "error");
            }
            else
            if(loginSuccess == -1){
              swal("E.V.S Response", "Users are allowed to vote only ONCE.\n continued attempts to login is punishable by the law.", "warning");
            }
            else{
              swal("E.V.S Response", "Cannot Connect to Database\n Please ensure device is connected to the internet.", "error");
            }

        }

      });

      return false;

}

function loadConstituencies(){

    $.get("/ElectronicVotingSystem/communication/ConstituencyStartup.php",function(cons){

          //console.log(cons);

          if ($("#Constituency").length > 0){
                cons.forEach(function(constituency){
                  var htmlStr = "<option value='"+constituency+"'>"+constituency+"</option>";
                  $("#Constituency").append(htmlStr);
                })
              }

    }, "json");


}

function register() {

  var constituency = $("#Constituency").val();
  var sex = $("input[name=Sex]:checked").val();

  var data = {

    'Constituency' : constituency,
    'Sex' : sex,
    'Function' : "check"

  };


  console.log(data);

  $.ajax({

      type: "POST",
      url: '/ElectronicVotingSystem/communication/RegisterVoter.php',
      data: data,
      async : false,

      success: function(check){
        console.log(check);
        if(check == 1){
		  startWebCam();
          swal("E.V.S Response", "Information Verified.", "success");
          $("#scan_portrait").show(200);
          displayCapture();
        }
        else
        if(check == -1){
          swal("E.V.S Response", "Please ensure Constituency and Sex are appropriately Selected.", "error");
        }
        else{
          swal("E.V.S Response", "Database Could Not Be Accessed.\n Please ensure device is connected to the Internet.", "error");
        }

      }

  });

  return false;
}

function completeRegistration(){

  swal({
    title: "E.V.S Confimation",
    text: "Please Confirm that all Information was Entered Correctly\n Before Proceeding",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3076da",
    confirmButtonText: "Confirm",
    closeOnConfirm: false
    },
    function(){
      KairosEnroll();
  });

}

function clearLogin(){
  $("#user").val("");
  $("#pass").val("");
}

function clearRegistration() {
  $("#RegistrationNumber").val("");
  $("#Constituency").val(0);
  $("#Surname").val("");
  $("#GivenNames").val("");
  $("#DateOfBirth").val("");
  $("#CountryOfBirth").val("");
  $("#DateIssued").val("");
  $("#ExpireDate").val("");
  $("#HeightFeet").val("");
  $("#HeightInches").val("");
  $("input[name=Sex]:checked").val("");
}
