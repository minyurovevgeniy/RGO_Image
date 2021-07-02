$(function()
{
	$("#single").show();
	$("#multiple").hide();
	$("#open").hide();


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
				url: "../php/refreshQuestionsList.php",
				dataType: "json",
				success: function(response)
				{
					var questions=response.questions;
					if (questions)
					{
						var questionHTML="";
						$("#question").html();
						for(var i=0;i<questions.length;i++)
						{
							questionHTML+='<option value='+questions[i].id+'>('+questions[i].id+') '+questions[i].text+'</option>';
						}
						$("#question").html(questionHTML);
					}

				},
				error:function(xml){alert("error1")}
			});

	$('body').on("click","#refreshQuestionsList",function()
	{
		$.ajax(
			{
				type: "GET",
				url: "../php/refreshQuestionsList.php",
				dataType: "json",
				success: function(response)
				{
					var questions=response.questions;
					var questionHTML="";
					$("#question").html();
					for(var i=0;i<questions.length;i++)
					{
						questionHTML+='<option value='+questions[i].id+'>('+questions[i].id+') '+questions[i].text+'</option>';
					}
					$("#question").html(questionHTML);
					alert("Список обновлен");
				},
				error:function(xml){alert("error1")}
			});
	});



	$('body').on("click","#choose-question",function()
	{
		var id=$("#question").val();
		var dataToSend={id:id};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/refreshQuestion.php",
			dataType: "json",
			success: function(response)
			{
				$("#images").html("");
				console.log(response);
				var save_open_view = '<div id="save-o-view"><input type="button" value="Сохранить" id="save-open"><input type="button" value="Удалить вопрос" id="delete"></div>';
				var save_s_m_view = '<div id="save-s-m-view"><input type="button" value="Сохранить" id="save-single-multiple"><input type="button" value="Удалить вопрос" id="delete"></div>';
				var correctAnswer=response.response.correct_answers;

				$("#open-question-answer-container").html('');

				$("#open-question-answer").val("");
				$("#question-text").val(response.response.question_text);
				$("#question-type-name").text(response.response.question_type_name);
				if (response.response.question_type=="1")
				{
					var answersImages=response.response.answers;
					var imageInfo="";
					$("#controls").html(save_s_m_view);
					for (var i=0;i<answersImages.length;i++)
					{

						imageInfo=answersImages[i].split("__");
						if (imageInfo[0]==correctAnswer)
						{
							$("#images").append('('+imageInfo[0]+')<img  src="'+imageInfo[1]+'"><input class="img_variant" data-variant-id="'+imageInfo[0]+'" name="single_variant" checked type="radio"><br>');
						}
						else
						{
							$("#images").append('('+imageInfo[0]+')<img src="'+imageInfo[1]+'"><input class="img_variant" data-variant-id="'+imageInfo[0]+'" name="single_variant" type="radio"><br>');
						}
					}
				}
				else
				{
					if (response.response.question_type=="2")
					{
						$("#controls").html(save_s_m_view);
						var answersImages=response.response.answers;
						var correctImages=response.response.correct_answers;
						var imageInfo="";
						for (var i=0;i<answersImages.length;i++)
						{
							imageInfo=answersImages[i].split("__");
							if (correctImages.includes(imageInfo[0]))
							{
								$("#images").append('('+imageInfo[0]+')<img src="'+imageInfo[1]+'"><input class="img_variant" data-variant-id="'+imageInfo[0]+'" name="multiple_variant" checked type="checkbox"><br>');
							}
							else
							{
								$("#images").append('('+imageInfo[0]+')<img src="'+imageInfo[1]+'"><input class="img_variant" data-variant-id="'+imageInfo[0]+'" name="multiple_variant" type="checkbox"><br>');
							}

						}
					}
					else
					{
						if (response.response.question_type=="3")
						{
							$("#controls").html(save_open_view);
							$("#open-question-answer-container").html('<input id="open-question-answer">');
							$("#images").html('<img src="'+response.response.answers+'"<br>');
							$("#open-question-answer").val(response.response.correct_answers);
						}
					}
				}
			},
			error:function(xml){alert("error1")}
		});
	});


	$('body').on("click","#delete",function()
	{
		var id=$("#question").val();
		var dataToSend={id:id};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/deleteQuestion.php",
			dataType: "json",
			success: function(response)
			{},
			error:function()
			{}
		});


	});

	$('body').on("click","#save-single-multiple",function()
	{
		var id=$("#question").val();

		var newChosenCorrectImages=$(".img_variant:checked");
		var length = newChosenCorrectImages.length;
		console.log(newChosenCorrectImages);
		var newCorrectIds=[];

		for(var i=0;i<length;i++)
		{
			newCorrectIds.push(newChosenCorrectImages[i].getAttribute("data-variant-id"));
		}
		newCorrectIds=newCorrectIds.join("_");

		var dataToSend={questionId:id,correctAnswers:newCorrectIds};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/saveQuestionCorrectVariant.php",
			dataType: "json",
			success: function(response)
			{
				alert("ОК");
			},
			error:function()
			{
				alert("error");
			}
		});
	});

	$('body').on("click","#save-open",function()
	{
		var id=$("#question").val();

		var answer = $("#open-question-answer").val();

		var dataToSend={questionId:id,openQuestionCorrect:answer};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/saveQuestionOpen.php",
			dataType: "json",
			success: function(response)
			{
				alert("ОК");
			},
			error:function()
			{
				alert("error");
			}
		});
	});

	$('body').on("click","#save-question-text",function()
	{
		var id=$("#question").val();
		var newQuestionText = $("#question-text").val();

		var dataToSend={questionId:id,newQuestionText:newQuestionText};
		$.ajax(
		{
			type: "POST",
			data:dataToSend,
			url: "../php/saveQuestionText.php",
			dataType: "json",
			success: function(response)
			{
				alert("ОК");
			},
			error:function()
			{
				alert("error");
			}
		});

	});

})
