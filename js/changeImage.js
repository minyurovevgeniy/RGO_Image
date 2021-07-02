$(function()
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
			$("#questions").html();
			for(var i=0;i<questions.length;i++)
			{
				questionHTML+='<option value='+questions[i].id+'>('+questions[i].id+') '+questions[i].text+'</option>';
			}
			$("#questions").html(questionHTML);
		},
		error:function(xml){alert("error1")}
	});

	$('body').on("change","#questions",function()
	{
		var id = $(this).val();
		var dataToSend={id:id};
		$.ajax(
		{
			type: "GET",
			data: dataToSend,
			url: "../php/refreshVariantsList.php",
			dataType: "json",
			success: function(response)
			{
				var ids=response.image_ids;
				var questionHTML="";
				$("#old_images").html();
				for(var i=0;i<ids.length;i++)
				{
					questionHTML+='<option value='+ids[i]+'>'+ids[i]+'</option>';
				}
				$("#old_images").html(questionHTML);
			},
			error:function(xml){alert("error1")}
		});
	});

	$('body').on("submit","#change",function()
	{

		if ($("#min_width").val()<1)
		{
			alert("Введите новую ширину");
			return false;
		}

		if (parseInt($("#old_image").val())<1)
		{
			 alert("Укажите номер изображения, которое требуется заменить");
			 return false;
		}


		if( !$('#questions').val() )
		{
			alert("Вопрос не выбран");
			return false;
		}
	});

	// ввод верного ответа
	$('body').on("input","#old_image",function()
	{
		$(this).val($(this).val().replace(/\D/g,""));
	});

	$('body').on("input","#min_width",function()
	{
		$(this).val($(this).val().replace(/\D/g,""));
	});
})
