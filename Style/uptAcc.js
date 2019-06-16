var dob;

function validateDob()
 {
 	if($("#dob").val() !== dob)
 		$("#scBtn").prop("disabled",false);
 	
 	else
 		$("#scBtn").prop("disabled",true);

 	if($("#oldPwd").val() || $("#newPwd").val() || $("#newRePwd").val())
 		validatePwd();	
 }

 function validatePwd()
 {
 	if($("#oldPwd").val() && $("#newPwd").val() && $("#newRePwd").val() && $("#newPwd").val() == $("#newRePwd").val() && $("#newPwd").val() != $("#oldPwd").val())
 		$("#scBtn").prop("disabled",false);
 	else
 		$("#scBtn").prop("disabled",true);
 }


$(document).ready(function(){

	 dob = $("#dob").val();
	 $("#dob").blur(validateDob);
	 $(".pwd").blur(validatePwd);
});