<?php 

require_once "system/config.php";

 ?>

<hmtl>
	<head>
		<title>Anket Yarat</title>
		<link rel="stylesheet" href="dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="dist/css/style.css">
	</head>
	<body>
		


		<div class="header">
			Anket Yarat
		</div>


		<?php 

		if(isset($_POST))
		{

			/* ANKETLƏRİ DB`yə Əlavə etmə SURVEY */

			$name = $_POST['name'];

			$surveySorgu = $db->prepare("INSERT INTO survey(name) VALUES(?)");
			$surveySorgu->execute(array($name));
			$surveyId = $db->lastInsertId(); //Son yüklənən anketin id`si

			/* ANKETLƏRİ DB`yə Əlavə etmə */

			$questions = $_POST['questions'];
			foreach ($questions as $key => $value)
			{
				/* SUALLARI DB`yə əlavə etmə */

				$questionsName = $value['name'];

				$questionsSorgu = $db->prepare("INSERT INTO questions(surveyid,name) VALUES(?,?)");
				$questionsSorgu->execute(array($surveyId,$questionsName));
				$questionsId = $db->lastInsertId(); //Son yüklənən Sualın id`si

				/* SUALLARI DB`yə əlavə etmə */

				$questionsCorrect = $value['correct'];

				foreach ($value['answers'] as $key2 => $value2) 
				{
					/* CAVABLARI DB`yə yükləmə */

					if($key2 == $questionsCorrect) //Əgər key2dən gələn doğru cavab POST dan gələn doğru cavabla eynidirsə
					{
						$correct = 1;
					}
					else
					{
						$correct = 0;
					}
					$answersSorgu = $db->prepare("INSERT INTO answers(questionid,name,correct) VALUES(?,?,?)");
					$answersSorgu->execute(array($questionsId,$value2,$correct));

					/* CAVABLARI DB`yə yükləmə */
				}
			}
		}


		?>






		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<form action="" method="POST">

						<div class="form-group">
							<label for="">Anket Adı: </label>
							<input type="text" class="form-control" name="name" required>
						</div>

						<button type="button" id="new-questions" class="btn btn-info">Yeni Sual</button>

						<div class="questions--row">
							
						</div>

						<button style="margin-top: 10px;" class="btn btn-success">Yarat</button>

					</form>
				</div>
			</div>
		</div>



		<!-- JQUERY.com dan aldığım Jquery 2x => jquery Core 2.2.4 versiyasından uncompressed(sıxılmamış) formatında alınmışdır -->
		<script
		src="https://code.jquery.com/jquery-2.2.4.js"
		integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
		crossorigin="anonymous"></script>
		<!-- JQUERY.com dan aldığım Jquery 2x => jquery Core 2.2.4 versiyasından uncompressed(sıxılmamış) formatında alınmışdır -->

		<script>
			
			$(document).ready(function()
			{

				var i = $(".questions--item").length; //i Questions--item`ın uzunluğuna bərabər olsun

				$("#new-questions").click(function() //New-questions(buttonun id`si->#la işarə olunur) klikləndiyində
				{
					var html = '<div class="row questions--item">' +
					'<div class="col-md-12" style="margin-bottom: 5px;">' +
					'<label>Sual Adı: </label>' +
					'<input type="text" name="questions['+i+'][name]" class="form-control">' +
					'</div>' +
					'<div class="col-md-12">' +
					'<button data-id="'+i+'" type="button" id="new-answers" class="btn btn-primary">Yeni Cavab</button>' + //Data-id burada seçimə qoyulan cavabların hansı suala aid olduğunu dəqiqləşdirir
					'</div>' +
					'<div class="answers--row"></div>' +
					'</div>';
					$(".questions--row").append(html); //Questions--row Classına hər klikləndiyində
					i++;
				});




				var i2 = $(".answers--item").length;




				$("body").on("click","#new-answers",function()
				{
					var id = $(this).data("id");
					
					var html = '<div class="col-md-12 answers--item">' +
					'<div class="col-md-8">' +
					'<label>Cavab Adı: </label>' +
					'<input type="text" name="questions['+id+'][answers]['+i2+']" class="form-control">' + //id -> hansı sualın cavabı olduğunu göstərməy üçündür
					'</div>' +
					'<div class="col-md-4">' +
					'<label>Doğru Cavab: </label> <br>' +
					'<input type="radio" name="questions['+id+'][correct]" value="'+i2+'">' + //Hansı cavabın (i2) doğru cavab(correct) olduğunu göstərir
					'</div>' +
					'</div>';

					$(this).closest(".questions--item").find('.answers--row').append(html);
					i2++;
				});
			});

		</script>


	</body>
</hmtl>






