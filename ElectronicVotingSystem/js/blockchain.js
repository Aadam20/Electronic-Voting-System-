function getBlockChainVotes(){
	
	$.ajax({
	
		type: "POST",
        url: 'http://45.55.80.29/checkBalance.php',
		crossDomain: true,
        async : false,
		
		success: function(balance){
		
		var json_object = JSON.parse(balance);
		
		if(json_object['balance'] != undefined){
		
			console.log(json_object['balance']);
		
		}
		
		else{
		console.log(json_object['error']);
		}
		
		
		}
		
		
	
	});


}

function checkPartyVotes(party){

	var data = {
		
		'party': party
	
	};
	
	$.ajax({
	
		type: "POST",
        url: 'http://45.55.80.29/checkAddress.php',
        data: data,
		crossDomain: true,
        async : false,
		
		success: function(address){
		
		var json_object = JSON.parse(address);
		
		if(json_object['error'] != undefined){
		
			console.log(json_object['error']);
		
		}
		
		else{
		console.log(json_object);
		}
		
		
		}
		
		
	
	});



}


function blockchainWalletTransaction(party){
	
	var data = {
		
		'party': party
	
	};
	
	$.ajax({
	
		type: "POST",
        url: 'http://45.55.80.29/transaction.php',
        data: data,
		crossDomain: true,
        async : false,
		
		success: function(success){
		
		var json_object = JSON.parse(success);
		
		if(json_object['error'] != undefined){
		
			console.log(json_object['error']);
		
		}
		
		else{
		console.log(json_object);
		}
		
		
		}
		
		
	
	});


}
