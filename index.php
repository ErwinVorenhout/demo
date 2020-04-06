<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>WebForm</title>
	<link rel="stylesheet" type="text/css" href="src/css/style.css">
	<link rel="stylesheet" href="src/css/countrySelect.css">

</head>
<body>
	<form action="api/insert" id= 'registrationForm'>
		<p id = 'result'></p>
		<div>
			<label for="name">Aanhef:</label>
			<select name="salution">
				<option value="1">De heer</option>
				<option value="2">Mevrouw</option>
				<option value="3">-</option>
			</select>
		</div>
		<div>
			<label for="firstName">Voornaam:</label>
			<input type="text" id="firstName" name="firstName" />
		</div>
		<div>
			<label for="prefix">Tussenvoegsel:</label>
			<input type="text" id="prefix" name="prefix" />
		</div>
		<div>
			<label for="lastName">Achternaam:</label>
			<input type="text" id="lastName" name="lastName" />
		</div>
		<div>
			<label for="email">E-Mail:</label>
			<input type="email" id="email" name="email" />
		</div>
		<div>
			<label for="email">Land:</label>
			  	<input type="text" id="country" />
				<input type="hidden" id="country_code"  name = 'country_code'/>

				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script src="src/js/countrySelect.min.js"></script>
		</div>
		<div class="button">
			<button type="submit">Aanmelden</button>
		</div>

	</form>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
 <script src="src/js/countrySelect.min.js"></script>
 <script>
 	
 	$(function() {
 		$("#country").countrySelect({
 			 defaultCountry:"nl"
		});

 	});

	$("#registrationForm").submit(function(e) {

	e.preventDefault(); // avoid to execute the actual submit of the form.

	var form = $(this);
	var url = form.attr('action');

	$.ajax({
		type: "POST",
		url: url,
		data: form.serialize(), // serializes the form's elements.
		success: function(data)
		{
			result = jQuery.parseJSON(data);
			if(result.meta.success)
			{
				$('#result').text('Bedankt voor uw registratie');
				$('#registrationForm').trigger("reset");
			}
			else
			{
				$('#result').text(result.meta.message);
			}
		}
	});


});
 </script>
</body>
</html>