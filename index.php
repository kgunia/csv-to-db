<?php
	/**
	 * Copyright (c) 2019 Karol Gunia <biuro@kgunia.pl>
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in
	 * all copies or substantial portions of the Software.
	 * 
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 * THE SOFTWARE.
	 * 
	 * @author      Karol Gunia
	 * @copyright   2019 (c) Karol Gunia
	 * @license     http://www.opensource.org/licenses/mit-license
	 * @link        http://github.com/kgunia/csv-to-db
	 * @package     csv-to-db
	 * @version     1.0.0
	 */

	// DB configuration
	$db_host	= ""; 				// DB host
	$db_user	= ""; 				// DB user
	$db_pass	= "";				// DB password
	$db_name	= "";				// DB name
	$db_table	= "";				// BD table

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
