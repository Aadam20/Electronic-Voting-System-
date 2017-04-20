"use strict"

//Stores Image to be Processed
var global_image;
var FaceDetected = false;

//KAIROS IMPLEMENTATION
// instantiates a new instance of the Kairos client
var kairos = new Kairos("37c98abe", "e892f9b918a0db2db364c4d00bb11051");

$(document).ready(function(){

//Uncomment to view all galleries contained in KAIROS API
//kairos.viewGalleries(myCallbackVG);
var gallery = "Voters";
//Displays portraits in gallery when website loads
//kairos.viewSubjectsInGallery(gallery, myCallbackVG);


});

//Callback Function of kairos.viewSubjectsInGallery
function myCallbackVG(response){
	//Uncomment below to view current ids 
    swal("Response", response.responseText, "info");
	//swal("E.V.S Startup", "Connected To KAIROS API.", "success");
};


///////////////////////////////////////////////////////ENROLLMENT OF FACIAL PORTRAITS SECTION///////////////////////////////////////


function KairosDetect(){
  var c = document.getElementById('snap_shot');
  //Converts canvas to a base64 encoded format in order to be processed by KAIROS
  global_image = c.toDataURL("image/png;base64");
  //Adjusts face detector to dectect all aspects of face, not just frontal.(Works better in paid version probably.)
  var options = {"selector" : "FULL"};
  kairos.detect(global_image, myDetectCallback, options);


}

//KairosDetect Callback
function myDetectCallback(response){

   var kairosJSON = JSON.parse(response.responseText);

   if(typeof kairosJSON.images == 'undefined'){
      swal("Status", "Portrait Scan was Unsuccessful,\n No Faces detected,\n Please Try Again.", "error");
      switchToCamera();
      return;
   }

  swal("E.V.S\nResponse", "Successfully captured Facial Portrait", "success");

   drawCanvas(kairosJSON.images[0].faces[0]);

   $("#swap_camera").show(800);
   $("#save_snapshot").show(800);


};

//Removes a subject_id from KAIROS gallery_name
function KairosRemove(){

  var subject_id = "814001534"; //HArdcode Subject to remove until admin page is operational.
  var gallery_name = "Voters";
  kairos.removeSubjectFromGallery(subject_id, gallery_name, myRemoveCallBack);


}

function myRemoveCallBack(response){

    swal("Hack", response.responseText, "info");


}

function KairosEnroll(){

  ////////////////////////////////////////////Sending Registration Data to Database///////////////////////////////////////////////////////////

  var toContinue = true;

  var regNo = $("#RegistrationNumber").val();
  var constituency = $("#Constituency").val();
  var surname = $("#Surname").val();
  var names = $("#GivenNames").val();
  var DOB = $("#DateOfBirth").val();
  var COB = $("#CountryOfBirth").val();
  var DateIssued = $("#DateIssued").val();
  var ExpireDate = $("#ExpireDate").val();
  var HeightFeet = $("#HeightFeet").val();
  var HeightInches = $("#HeightInches").val();
  var sex = $("input[name=Sex]:checked").val();
  var phone = $("#Phone").val();
  var email = $("#E-Mail").val();

  var data = {
    'RegistrationNumber' : regNo,
    'Constituency' : constituency,
    'Surname' : surname,
    'GivenNames' : names,
    'DateOfBirth' : DOB,
    'CountryOfBirth' : COB,
    'DateIssued' : DateIssued,
    'ExpireDate' : ExpireDate,
    'HeightFeet' : HeightFeet,
    'HeightInches' : HeightInches,
    'Sex' : sex,
    'Phone' : phone,
    'E-Mail' : email,
    'Function': "register"
  };

  console.log(data);


  $.ajax({

      type: "POST",
      url: '/ElectronicVotingSystem/communication/RegisterVoter.php',
      data: data,
      async : false,

      success: function(id){
        console.log(id);
        if(id == 1)
          toContinue = true;

        else
        if(id == -1){

            swal("E.V.S Response", "Information was not uploaded to database.\nPlease Try Again.", "error");
            toContinue = false;

        }

        else{

          swal("E.V.S Response", "Databse Could Not Be Accessed.\n Please ensure device is connected to the Internet.", "error");
          toContinue = false;

        }

      }

    });

///////////////////////////////////////////Uploading Relevant Information to KAIROS Server////////////////////////////////////////////////////

  if(toContinue == true){

  var subject_id = regNo; //The registration Number is used because of its unique attribute.
  var gallery_name = "Voters";
  //One image can be enrolled multiple times under the same subject_id to increase accuracy.
  //However execution time was increased and no drastic improvement was seen.

  /*kairos.enroll(global_image, gallery_name, subject_id, myEnrollCallBack);
  kairos.enroll(global_image, gallery_name, subject_id, myEnrollCallBack);
  kairos.enroll(global_image, gallery_name, subject_id, myEnrollCallBack);
  kairos.enroll(global_image, gallery_name, subject_id, myEnrollCallBack);
  kairos.enroll(global_image, gallery_name, subject_id, myEnrollCallBack);
  kairos.enroll(global_image, gallery_name, subject_id, myEnrollCallBack);*/
  kairos.enroll(global_image, gallery_name, subject_id, myLastEnrollCallBack);
  }


}

//FUNTION myEnrollCallBack will only be needed if enrolling one picture multiple times.
/*
function myEnrollCallBack(response){

  console.log(response.responseText);

}*/


function myLastEnrollCallBack(response){

    switchToCamera();
    clearRegistration();
    displayContent();
    swal("E.V.S Response", "Portrait Saved Successfully\nRegistration Complete", "success");

}

/////////////////////////////////////////////////////VERIFICATION OF FACIAL PORTRAITS//////////////////////////////////////////////////////

function VerifyByKairos(){

  var c = document.getElementById('snap_shot');
  global_image = c.toDataURL("image/png;base64");
  var options = {"selector" : "FULL"};
  kairos.detect(global_image, myVDetectCallBack, options);

}

function myVDetectCallBack(response){

  var kairosJSON = JSON.parse(response.responseText);

  if(typeof kairosJSON.images == 'undefined'){
     swal("Status", "Portrait Scan was Unsuccessful,\n No Faces detected,\n Please Try Again.", "error");
     switchToCamera();
     return;
  }

  //Uncomment to view JSON object returned by KAIROS API
  //console.log(kairosJSON);

  swal("E.V.S\nResponse", "Successfully captured Facial Portrait", "success");

  //KairosVerify();
  KairosRecognize(); //Will change to verify if option is discovered to change the threshold from 0.6 to 0.8

}

function KairosVerify(){

  var subject_id = "votersT";
  var gallery_name = "Voters";

  kairos.verify(global_image, gallery_name, subject_id, myVerifyCallBack);

}

function myVerifyCallBack(response){

    var kairosJSON = JSON.parse(response.responseText);
    console.log(kairosJSON);
    if(kairosJSON.images[0].transaction.status == "success")
      swal("EVS Verification","Verification Successful.", "success");
    else{
      swal("EVS Verification", "Verification Failure", "error");
    }
}

function KairosRecognize(){

    var gallery_name = "Voters";
    var options = {"threshold" : "80"};
    kairos.recognize(global_image, gallery_name, myRecognizeCallBack, options);

}

function myRecognizeCallBack(response){

    var kairosJSON = JSON.parse(response.responseText);
    //Uncomment to view JSON object returned by KAIROS API
    console.log(kairosJSON);
    //TO ACCESS TOP CANDIDATE
    //kairosJSON.images[0].candidates[0].subject_id

    if((kairosJSON.images == 'undefined') || (kairosJSON.images[0].transaction.message == "No match found")){
      swal("E.V.S Verification","Verification Failure\n No Match Found in database.", "error");
      switchToCamera();
      return;
    }

    var found = false;
    var id = $("#user").val();
    kairosJSON.images[0].candidates.forEach(function(candidate){
      //Display all possible matches and current id.
      //console.log(candidate.subject_id + " " + id);

      if(candidate.subject_id == id){
        //swal("E.V.S Verification", "Verification Success, Match Found.", "success");
        swal({title:"E.V.S Verification", text:"Verification Success, \nClick OK to Proceed to Voting Screen.", type: "success"}, function(){
        $("#VotingModal").modal("show");
        switchToCamera();
		stopWebCam();
        displayContent();
      });
        found = true;
      }

    });

    if(found == false){
      swal("E.V.S Verification","Verification Failure.\n Portrait does not match Portrait of \n" + id, "error");
      switchToCamera();
      return;
    }


    //Will place what happens after successful verification here.
    //clearLogin();
}



//Function used to trace outline of face during enrollment
function drawCanvas(face){

  var canvas = document.getElementById('snap_shot');
  var context = canvas.getContext('2d');

        // draw face box
        context.beginPath();
        context.rect(face.topLeftX, face.topLeftY, face.width, face.height);
        context.lineWidth = 4;
        context.strokeStyle = '#3076da';
        context.stroke();

        // draw left eye
        context.beginPath();
        context.moveTo(face.leftEyeCornerLeftX, face.leftEyeCornerLeftY);
        context.lineTo(face.leftEyeCornerRightX, face.leftEyeCornerRightY);
        context.stroke();

        context.beginPath();
        context.moveTo(face.leftEyeCenterX, (face.leftEyeCenterY + (face.height / 25)));
        context.lineTo(face.leftEyeCenterX, (face.leftEyeCenterY - (face.height / 25)));
        context.stroke();

        // draw right eye
        context.beginPath();
        context.moveTo(face.rightEyeCornerLeftX, face.rightEyeCornerLeftY);
        context.lineTo(face.rightEyeCornerRightX, face.rightEyeCornerRightY);
        context.stroke();

        context.beginPath();
        context.moveTo(face.rightEyeCenterX, (face.rightEyeCenterY + (face.height / 25)));
        context.lineTo(face.rightEyeCenterX, (face.rightEyeCenterY - (face.height / 25)));
        context.stroke();

        // left eyebrow
        context.beginPath();
        context.moveTo(face.leftEyeBrowLeftX, face.leftEyeBrowLeftY);
        context.lineTo(face.leftEyeBrowMiddleX, face.leftEyeBrowMiddleY);
        context.stroke();

        context.beginPath();
        context.moveTo(face.leftEyeBrowMiddleX, face.leftEyeBrowMiddleY);
        context.lineTo(face.leftEyeBrowRightX, face.leftEyeBrowRightY);
        context.stroke();

        // right eyebrow
        context.beginPath();
        context.moveTo(face.rightEyeBrowLeftX, face.rightEyeBrowLeftY);
        context.lineTo(face.rightEyeBrowMiddleX, face.rightEyeBrowMiddleY);
        context.stroke();

        context.beginPath();
        context.moveTo(face.rightEyeBrowMiddleX, face.rightEyeBrowMiddleY);
        context.lineTo(face.rightEyeBrowRightX, face.rightEyeBrowRightY);
        context.stroke();

        // draw mouth
        context.beginPath();
        context.moveTo(face.lipCornerLeftX, face.lipCornerLeftY);
        context.lineTo(face.lipLineMiddleX, face.lipLineMiddleY);
        context.stroke();
        context.beginPath();
        context.moveTo(face.lipLineMiddleX, face.lipLineMiddleY);
        context.lineTo(face.lipCornerRightX, face.lipCornerRightY);
        context.stroke();

        // draw nose
        context.beginPath();
        context.moveTo(face.nostrilLeftSideX, face.nostrilLeftSideY);
        context.lineTo(face.nostrilLeftHoleBottomX, face.nostrilLeftHoleBottomY);
        context.stroke();

        context.beginPath();
        context.moveTo(face.nostrilRightSideX, face.nostrilRightSideY);
        context.lineTo(face.nostrilRightHoleBottomX, face.nostrilRightHoleBottomY);
        context.stroke();


}
