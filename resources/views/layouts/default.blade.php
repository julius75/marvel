<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Marvel Character Demo</title>
	<link rel="stylesheet" href="{{ url('lib/picnic/releases/picnic.min.css') }}">
	<link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1>Marvel Viewer Comic Character</h1>
		</div>
		@yield('content')
	</div>
</body>
</html>
