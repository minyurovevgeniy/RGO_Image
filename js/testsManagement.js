$(function(){

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
			url: "../php/refreshTests.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.tests)
				{
					var row="";
					var select="";
					var tests=response.tests;
					var length = tests.length;
					for (var i=0;i<length;i++)
					{
						row+='<div class="row" data-id="'+tests[i].id+'">'+
								'<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1 id">'+tests[i].id+'</div>'+
								'<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3"><input class="test" data-id="'+tests[i].id+'" type="text" value="'+tests[i].title+'"> </div>'+
								'<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4"><input class="description" data-id="'+tests[i].id+'" type="text" value="'+tests[i].description+'"></div>'+
								'<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4 actions">'+
									'<input class="save" type="button" data-id="'+tests[i].id+'" value="Сохранить">'+
									'<input class="delete" type="button" data-id="'+tests[i].id+'" value="Удалить">'+
								'</div>'+
							'</div>';
						select+='<option value="'+tests[i].id+'">'+tests[i].title+'</option>';
					}
					$("#tests-list").html(row);
					$("#testsToChoose").html(select);
				}
			},
			error:function(xml){alert("error")}
		});

	$('body').on("click","#refreshTests",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/refreshTests.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);

				if (response.tests)
				{
					var select="";
					var row="";
					var tests=response.tests;
					var length = tests.length;
					for (var i=0;i<length;i++)
					{
						row+='<div class="row" data-id="'+tests[i].id+'">'+
							'<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1 id">'+tests[i].id+'</div>'+
							'<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3"><input class="test" data-id="'+tests[i].id+'" type="text" value="'+tests[i].title+'"> </div>'+
							'<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4"><input class="description" data-id="'+tests[i].id+'" type="text" value="'+tests[i].description+'"></div>'+
							'<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4 actions">'+
								'<input class="save" type="button" data-id="'+tests[i].id+'" value="Сохранить">'+
								'<input class="delete" type="button" data-id="'+tests[i].id+'" value="Удалить">'+
							'</div>'+
						'</div>';
						select+='<option value="'+tests[i].id+'">'+tests[i].title+'</option>';
					}
					$("#tests-list").html(row);
					$("#testsToChoose").html(select);
				}
			},
			error:function(xml){alert("error")}
		});
	});

	$('body').on("click",".save",function()
	{
			var id=$(this).attr("data-id");
			var title=$('.test[data-id='+id+']').val();
			var description=$('.description[data-id='+id+']').val();
			var passwordToSave=$("#save_password").val();
			var dataToSend={id:id,new_title:title,new_description:description,password:passwordToSave};
			$.ajax(
			{
				type: "POST",
				data:dataToSend,
				url: "../php/saveTest.php",
				dataType: "json",
				success: function(response)
				{
					alert(response.response);
				},
				error:function(response){alert("error")}
			});
	});

	$('body').on("click",".delete",function()
	{
			var id=$(this).attr("data-id");
			var passwordToSave=$("#save_password").val();
			var dataToSend={id:id};
			$.ajax(
			{
				type: "POST",
				data:dataToSend,
				url: "../php/deleteTest.php",
				dataType: "json",
				success: function(response)
				{
					alert("OK");
					Location.reload();
				},
				error:function(response){alert("error")}
			});
	});

	$('body').on("click","#addTestManually",function()
	{
		var test=$("#newTest").val().replace(/\s{2,}/g," ");
		var testDescription=$("#newTestDescription").val().replace(/\s{2,}/g," ");
		if (test.replace(/\s/g,"").length<1)
		{
			alert("Введите название теста");
		}
		else
		{
			if (testDescription.replace(/\s/g,"").length<1)
			{
				alert("Введите описание теста");
			}
			else
			{
				var passwordToSave=$("#save_password").val();
				var dataToSend={new_title:test,new_description:testDescription,password:passwordToSave};
				$.ajax(
				{
					type: "POST",
					data:dataToSend,
					url: "../php/addTestManually.php",
					dataType: "json",
					success: function(response)
					{
						alert(response.response);
					},
					error:function(response){alert("error")}
				});
			}

		}
	});

	$('body').on("click","#chooseTest",function()
	{
		var test=$("#testsToChoose option:selected").text();
		var testId=$("#testsToChoose").val();
		var passwordToSave=$("#save_password").val();
		var dataToSend={test:testId,password:passwordToSave};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/chooseTest.php",
			dataType: "json",
			success: function(response)
			{
				//alert(response.response);
				$("#chosenTest").html(test);
			},
			error:function(response){alert("error")}
		});
	});

})
