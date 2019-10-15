<?php
	/**
	 * Script name: csv-to-db
	 * Script URI: https://github.com/kgunia/csv-to-db
	 * Author: Karol Gunia
	 * Author URI: https://kgunia.pl
	 * Description: Import data from CSV to MySQL database, developed for CSI S.A. 
	 * Version: 1.0.0
	 * License: MIT 
	 **/

	// DB configuration
	$db_host	=	"localhost"; 				// DB host
	$db_user	= "admin_xml"; 				// DB user
	$db_pass	= "3bUP9YwyDYfPu257";	// DB password
	$db_name	= "admin_presta";			// DB name
	$db_table	= "kg_xml";						// BD table

	// create connection to DB
	$conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

	// form validation
	if (isset($_POST["import"])){  
		
		// allowed file type
		$allowedFileType = ['text/csv','application/octet-stream']; 
		
		// file format validation
		if(in_array($_FILES["file"]["type"],$allowedFileType)){
				 
			// save uploaded file
			$targetPath = 'uploads/'.$_FILES['file']['name'];
			move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			
			// open uploaded file
			$fileHandle = fopen($targetPath, "r");
			
			// clear table
			$query = "TRUNCATE TABLE ".$db_table."";
			$result = mysqli_query($conn, $query);
			
			// flag for skiping first CSV row
			$flag = true;
			
			// loop through CSV rows
			while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
				
				// skip first row 
				if($flag) { $flag = false; continue; }
				
				// assign rows to specific variable
				$sku = $row[0];					
				$vat = $row[1];
				$lenght = $row[2];
				$width = $row[3];
				$height = $row[4];
				$net_weight = $row[5];
				$gross_weight = $row[6];
				
				// if data is not empty, update DB 
				if (!empty($sku) || !empty($vat) || !empty($lenght) || !empty($width) || !empty($height) || !empty($net_weight) || !empty($gross_weight)) {
					$query = "insert into kg_xml(sku,vat,lenght,width,height,net_weight,gross_weight) values('".$sku."','".$vat."','".$lenght."','".$width."','".$height."','".$net_weight."','".$gross_weight."')";
					$result = mysqli_query($conn, $query);
					
					// DB error handling
					if (! empty($result)) {
						$type = "success";
						$message = "Plik zaimportowany do bazy danych";
					} else {
						$type = "error";
						$message = "Problem z importem pliku do bazy danych";
					}
				}
			}
		// file format validation error handling		
		} else { 
			$type = "error";
			$message = "Nieprawidłowy format pliku. Dozwolone wyłacznie pliki CSV.";
		}
	}

	// loading main template
	require_once("./tpl/main.php");

	// select all data from table
	$sqlSelect = "SELECT * FROM ".$db_table."";
	$result = mysqli_query($conn, $sqlSelect);

	// if querry return is not empty, show results table 
	if (mysqli_num_rows($result) > 0){

		// loading results template
		require_once("./tpl/results.php");
	} 
	
	// loading footer template
	require_once("./tpl/footer.php");
	
		// close SQL connection
	mysqli_close($conn);
?>