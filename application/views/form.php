<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
</head>
<body>

<div id="container">
	<h3>Form</h3>
	<form method="post" action="/form/output">

		Primary: <input type="text" name="model.id" value="876"><br>
		First name: <input type="text" name="model.fname" value="Johnny"><br>
		Last name: <input type="text" name="model.lname" value="Appleseed"><br>
		Age: <input type="text" name="model.age" value="23"><br>

		Test 1: <input type="text" name="model.test1" value="&"><br>
		Test 2: <input type="text" name="model.test2" value="="><br>

		Remove: <input type="text" name="model.removeme" value="foobar"><br>
		Move: <input type="text" name="model.moveme" value="movebar"><br>

		<hr>

		model 2 first name: <input type="text" name="model2.fname" value="Jenny"><br>
		model 2 last name: <input type="text" name="model2.lname" value="Appleseed"><br>
		model 2 age: <input type="text" name="model2.age" value="21"><br>

		<hr>

		repeating first name: <input type="text" name="model.repeatable.#.fname" value="Albert"><br>
		repeating last name: <input type="text" name="model.repeatable.#.lname" value="Appleseed"><br>
		repeating age: <input type="text" name="model.repeatable.#.age" value="12"><br>

		repeating first name: <input type="text" name="model.repeatable.#.fname" value="Peter"><br>
		repeating last name: <input type="text" name="model.repeatable.#.lname" value="Appleseed"><br>
		repeating age: <input type="text" name="model.repeatable.#.age" value="13"><br>

		repeating first name: <input type="text" name="model.repeatable.#.fname" value="Lynn"><br>
		repeating last name: <input type="text" name="model.repeatable.#.lname" value="Appleseed"><br>
		repeating age: <input type="text" name="model.repeatable.#.age" value="14"><br>

		<input type="submit" value="Submit"><br>

	</form>
</div>

</body>
</html>