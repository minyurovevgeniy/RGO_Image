$(function()
{
	$("#right_answer_container").hide();

	// форма для загрузки данных
	$('body').on("input","#images_count",function()
	{
		$("#images").html("");
		var imageCount = $(this).val();
		for (var i=1;i<=imageCount;i++)
		{
			$("#images").append('<input type="file" name="questions[]"><br>');
		}
	});

	$('body').on("submit","#upload",function()
	{
		var imageCount = $("#images_count").val();
		if (imageCount<1)
		{
			alert("Количество изображений должно быть больше нуля");
			return false;
		}

		if ($("#max_height").val()<1)
		{
			alert("Введите новую высоту");
			return false;
		}

		var questionText=$("#question_text").val();
		questionText = questionText.replace(/\s/g,"");
		if (questionText.length<=0)
		{
			alert("Введите текст вопроса");
			return false;
		}

		var imageCount = $("#images_count").val();
		if (imageCount<=0)
		{
			alert("Введите количество вариантов");
			return false;
		}

		if (parseInt($("#right_image").val()) < 1)
		{
			alert("Номер правильного варианта должен быть натуральным числом");
			return false;
		}

		if (parseInt($("#right_image").val()) > imageCount)
		{
			alert("Номер правильной картинки должен быть не больше количества вариантов");
			return false;
		}

		var questionType=$("#question_type").val();
		if (questionType=="1")
		{
			$("#right_image").val($("#right_image").val().replace(/\D/g,""));
		}
		else
		{
			if (questionType=="2")
			{
				var separatedString = $("#right_image").val().split(";");
				if (separatedString[separatedString.length-1]=="" || separatedString[0]=="")
				{
					alert("Ответы на вопросы должны быть разделены знаком ;");
					return false;
				}
			}
			else
			{
				if (questionType=="3")
				{
					if ($("#right_answer").val()=="")
					{
						alert("Ответ на вопрос должен быть непустым");
						return false;
					}
				}
			}
		}
	});

	$('body').on("change","#question_type",function()
	{
		$("#right_answer_container").hide();
		$("#image_count_container").show();
		$("#right_image_container").show();

		$("#max_height").val(170);

		if (parseInt($(this).val())=="3")
		{
			$("#min_width").val(250);
			$("#images").html('<input type="file" name="questions[]">');
			$("#right_answer_container").show();
			$("#image_count_container").hide();
			$("#right_image_container").hide();
		}
	});

	// ввод верного ответа
	$('body').on("input","#right_image",function()
	{
		$(this).val($(this).val().replace(/\D/g,""));
	});

	$('body').on("input","#min_width",function()
	{
		var questionType=$("#question_type").val();
		if (questionType=="1")
		{
			$(this).val($(this).val().replace(/\D/g,""));
		}
	});

	$('body').on("input","#images_count",function()
	{
		$(this).val($(this).val().replace(/\D/g,""));
	});

})
