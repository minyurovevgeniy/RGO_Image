$(function()
{
		
	$('body').on("click","#submit",function()
	{
		var login = $("#login").val();
		var password = $("#password").val();
		var dataToSend={login:login,password:password};
		
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/checkSiteAccess.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);
				location = "http://rgo-image.xn--100-5cdnry0bhchmgqi5d.xn--p1ai/testsManagement.php";

			},
			error:function(xml){alert("error")}
		});
	});
})