	var proceed;
$(document).ready(function(){
	

	$(".promote").click(function()
	{
		proceed = confirm("Are you sure you want to promote this user?");
		if(proceed)
		{
			this.parentElement.action = "promoteUser.php";
			this.parentElement.submit();
		}
	});

	$(".demote").click(function()
	{
		var proceed = confirm("Are you sure you want to demote this user?");
		if(proceed)
		{
			this.parentElement.action = "demoteUser.php";
			this.parentElement.submit();
		}
	});

	$(".delete").click(function()
	{
		var proceed = confirm("Are you sure you want to delete this user?");
		if(proceed)
		{
			this.parentElement.action = "deleteUser.php";
			this.parentElement.submit();
		}
	});

		$(".deleteInstitute").click(function(){	
			var proceed = confirm("Are you sure you want to delete this institute?");
			if(proceed)
			{
				this.parentElement.submit();
			}
		});
});
