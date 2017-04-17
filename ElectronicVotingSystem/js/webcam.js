"use strict"


$(document).ready(function(){
  //Line 8 Documentation
  //What it suppose to do: Wait until I clicked the button with id saveR to start webcam
  //What it actually does: Starts the webcam as soon as page loads
  //$("#saveR").click(navigator.getUserMedia(constraints, sucessCallBack, errorCallback));

});

function startWebCam(){
	
	navigator.getUserMedia(constraints, sucessCallBack, errorCallback);
	
}

function stopWebCam(){

	window.stream.getTracks()[0].stop;
	console.log("Web Cam Stop Function");
	
}

//Line 13 Explaination : https://developer.mozilla.org/en/docs/Web/API/Navigator/getUserMedia
navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

var constraints = {
  audio: false,
  video: true
};

var camera = document.querySelector('video'); //gets the first element in the document with class/HTML tag 'video'.

function sucessCallBack(stream){
  //Access live feed from webcam and displays it on page.
  window.stream = stream;
  if(window.URL){
    camera.src = window.URL.createObjectURL(stream);
  }
  else {
    camera.src = stream;
  }
}

function errorCallback(error){
  swal("E.V.S. Error", "Unable To Access WebCam\n Please Ensure that browser has permission\n to access webcam.",  "error");
}

function takeSnapShot(){

  var photo = document.getElementById('snap_shot');
  var context = photo.getContext('2d');
  //Copy the content of the live stream and pastes in on the canvas.
  context.drawImage(camera, 0, 0, photo.width, photo.height);

  photo.style.display = null;

}
