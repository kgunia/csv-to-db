<!DOCTYPE html>
<html>    
	<head>
		<title>Import pliku CSV do bazy MySQL</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
	</head>
	<body>
		<h2>Import pliku CSV do bazy MySQL</h2>
		<div class="outer-container">
			<form action="" method="post"	name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
				<div>
					<label>Wybierz plik CSV</label> 
					<input type="file" name="file" id="file" accept=".csv">
					<button type="submit" id="submit" name="import" class="btn-submit">Import</button>
				</div>
			</form>
		</div>
		<div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>				 
		