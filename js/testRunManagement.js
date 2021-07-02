$(function()
{

	$.ajax(
{
	type: "GET",
	url: "../php/refreshDuration.php",
	dataType: "json",
	success: function(response)
	{
		console.log(response);


			$("#duration").val(response.response);

	},
	error:function(xml){alert("error3")}
});

$.ajax(
	{
		type: "GET",
		url: "../php/checkTestState.php",
		dataType: "json",
		success: function(response)
		{
			console.log(response);

			if (response.state)
			{
				if (response.state=="visible")
				{
					$("#testsToken").html('<input type="button" id="generateTestToken" value="Сгенерировать">');
					$("#studentsToken").html('<input type="button" id="generateStudentsToken" value="Сгенерировать">');
					$("#runTestButton").html('<input type="button" id="runTest" value="Запустить">');
				}

			}
		},
		error:function(xml){alert("error")}
	});

		$.ajax(
	{
		type: "GET",
		url: "../php/showTest.php",
		dataType: "json",
		success: function(response)
		{
			console.log(response);

			if (response.title)
			{
				$("#chosenTest").html(response.title);
			}
		},
		error:function(xml){alert("error")}
	});


	$.ajax(
	{
		type: "GET",
		url: "../php/refreshTokensByTest.php",
		dataType: "json",
		success: function(response)
		{
			console.log(response);

			if (response.tokens)
			{
				var row="";
				var tokens=response.tokens;
				var length = tokens.length;
				for (var i=0;i<length;i++)
				{
					row+=
					'<div class="row">'+
						'<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">'+tokens[i].id+'</div>'+
						'<div class="col-sm-5 col-md-5 col-sm-5 col-xs-5">'+tokens[i].name+'</div>'+
						'<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">'+tokens[i].token+'</div>'+
					'</div>';
				}
				$("#tokens-list").html(row);
			}
		},
		error:function(xml){alert("error")}
	});



		$.ajax(
		{
			type: "GET",
			url: "../php/refreshStudentTokensFileLink.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.tokens_file_path)
				{
					$("#studentsTokenFile").prop("href",response.tokens_file_path);
				}
			},
			error:function(xml){
				alert("error")
			}
		});

		$.ajax(
		{
			type: "GET",
			url: "../php/refreshShortReportFileLink.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.report_short_path)
				{
					$("#studentsReportShortFile").prop("href",response.report_short_path);
				}
			},
			error:function(xml){
				alert("error")
			}
		});

		$.ajax(
		{
			type: "GET",
			url: "../php/refreshFullReportFileLink.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.report_full_path)
				{
					$("#studentsReportFullFile").prop("href",response.report_full_path);
				}
			},
			error:function(xml){
				alert("error")
			}
		});

	$.ajax(
	{
		type: "GET",
		url: "../php/refreshTestToken.php",
		dataType: "json",
		success: function(response)
		{
			console.log(response);

			if (response.test_token)
			{
				$("#testToken").html(response.test_token.token);

			}
		},
		error:function(xml){alert("error")}
	});


	$('body').on("click","#refreshTokens",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/refreshTokensByTest.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.tokens)
				{
					var row="";
					var tokens=response.tokens;
					var length = tokens.length;
					for (var i=0;i<length;i++)
					{
						row+=
						'<div class="row">'+
							'<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">'+tokens[i].id+'</div>'+
							'<div class="col-sm-5 col-md-5 col-sm-5 col-xs-5">'+tokens[i].name+'</div>'+
							'<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">'+tokens[i].token+'</div>'+
						'</div>';
					}
					$("#tokens-list").html(row);
				}
			},
			error:function(xml){alert("error")}
		});
	});


	$('body').on("click","#generateShortReport",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/generalScores.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.report_short_path)
				{
					$("#studentsReportShortFile").prop("href",response.report_short_path);
					alert("ОК");
				}
			},
			error:function(xml){alert("error")}
		});
	});

	$('body').on("click","#generateFullReport",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/detailedScores.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.report_full_path)
				{
					$("#studentsReportFullFile").prop("href",response.report_full_path);
					alert("ОК");
				}
			},
			error:function(xml){alert("error")}
		});
	});

	$('body').on("click","#generateTestToken",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/generateTestToken.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.token)
				{
					$("#testToken").html(response.token);
				}
			},
			error:function(xml){alert("error")}
		});
	});

	$('body').on("click","#generateStudentsToken",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/generateStudentsToken.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.tokens_file_path)
				{
					$("#studentsTokenFile").attr("href",response.tokens_file_path);
					alert("ОК");
				}
			},
			error:function(xml){alert("error")}
		});
	});

	$('body').on("click","#saveDuration",function()
	{
		var duration = $("#duration").val();
		var dataToSend={duration:duration};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/saveDuration.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);
			},
			error:function(xml){alert("error")}
		});
	});


	$('body').on("click","#runTest",function()
	{
		$.ajax(
		{
			type: "POST",
			url: "../php/runTest.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				$("#generateStudentsToken").remove();
				$("#generateTestToken").remove();
				$("#runTest").remove();

				alert("ОК");

			},
			error:function(xml){alert("error")}
		});
	});

	$('body').on("input","#duration",function()
	{
		var duration=$(this).val();
		duration=duration.replace(/\D/gi,"");
		$(this).val(duration);
	});


})
