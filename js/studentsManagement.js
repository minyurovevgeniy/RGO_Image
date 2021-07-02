$(function()
{
	$.ajax(
		{
			type: "GET",
			url: "../php/refreshStudents.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);
				
				if (response.students)
				{
					var row="";
					var students=response.students;
					var length = students.length;
					for (var i=0;i<length;i++)
					{
						row+=
						'<div class="row" data-id="'+students[i].id+'">'+
							'<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">'+students[i].id+'</div>'+
							'<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="text" data-id="'+students[i].id+'" value="'+students[i].student_surname+'" class="surname"></div>'+
							'<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="text" data-id="'+students[i].id+'" value="'+students[i].student_name+'" class="name"></div>'+
							'<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="text" data-id="'+students[i].id+'" value="'+students[i].student_patronymic+'" class="patronymic"></div>'+
								'<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">'+
									'<input class="save" type="button" data-id="'+students[i].id+'" value="Сохранить">'+
									'<input class="delete" type="button" data-id="'+students[i].id+'" value="Удалить">'+
								'</div>'+
							'</div>';
					}
					$("#students-list").html(row);
				}
			},
			error:function(xml){alert("error")}
		});
	
	$('body').on("click","#refreshStudents",function()
	{
		$.ajax(
		{
			type: "GET",
			url: "../php/refreshStudents.php",
			dataType: "json",
			success: function(response)
			{
				console.log(response);
				
				if (response.students)
				{
					var row="";
					var students=response.students;
					var length = students.length;
					for (var i=0;i<length;i++)
					{
						row+=
						'<div class="row" data-id="'+students[i].id+'">'+
							'<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">'+students[i].id+'</div>'+
							'<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="text" data-id="'+students[i].id+'" value="'+students[i].student_surname+'" class="surname"></div>'+
							'<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="text" data-id="'+students[i].id+'" value="'+students[i].student_name+'" class="name"></div>'+
							'<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="text" data-id="'+students[i].id+'" value="'+students[i].student_patronymic+'" class="patronymic"></div>'+
								'<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">'+
									'<input class="save" type="button" data-id="'+students[i].id+'" value="Сохранить">'+
									'<input class="delete" type="button" data-id="'+students[i].id+'" value="Удалить">'+
								'</div>'+
							'</div>';
					}
					$("#students-list").html(row);
				}
			},
			error:function(xml){alert("error")}
		});
	});

	
		
	$('body').on("click",".save",function()
	{
			var id=$(this).attr("data-id");
			var inputId=$(this).attr("data-id");
			
			var surname=$('.surname[data-id='+id+']').val();
			var name=$('.name[data-id='+id+']').val();
			var patronymic=$('.patronymic[data-id='+id+']').val();
			
			var passwordToSave=$("#save_password").val();
			var dataToSend={id:id,new_surname:surname,new_name:name,new_patronymic:patronymic,password:passwordToSave};
			$.ajax(
			{
				type: "POST",
				data:dataToSend,
				url: "../php/saveStudent.php",
				dataType: "json",
				success: function(response)
				{
					alert(response.response);
				},
				error:function(response){alert("error")}
			});
	});
	
	$('body').on("click","#addStudentManually",function()
	{
		var inputId=$(this).attr("data-id");
		var surname=$("#newSurname").val().replace(/\s/g,"");
		var name=$('#newName').val().replace(/\s/g,"");
		var patronymic=$('#newPatronymic').val().replace(/\s/g,"");
		if (surname.length<1)
		{
			alert("Введите фамилию");
		}
		else
		{
			if (name.length<1)
			{
				alert("Введите имя");
			}
			else
			{
				if (patronymic.length<1)
				{
					alert("Введите отчество");
				}
				else
				{
					/*
					var passwordToSave=$("#save_password").val();
					var dataToSend={name:name,surname:surname,patronymic:patronymic,password:passwordToSave};
					$.ajax(
					{
						type: "POST",
						data:dataToSend,
						url: "../php/addStudentManually.php",
						dataType: "json",
						success: function(response)
						{
							alert(response.response);
						},
						error:function(response){alert("error")}
					});
					*/
				}
			}
		}
		
		
	});
})