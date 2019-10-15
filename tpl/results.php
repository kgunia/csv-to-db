		<table class='tutorial-table'>
			<thead>
				<tr>
					<th>SKU</th>
					<th>VAT</th>
					<th>Długość</th>
					<th>Szerokość</th>
					<th>Wysokość</th>
					<th>Waga netto</th>
					<th>Waga brutto</th>
				</tr>
			</thead>
			<?php
				// loop for displaying results
				while ($row = mysqli_fetch_array($result)) {
			?>                  
			<tbody>
				<tr>
						<td><?php  echo $row['sku']; ?></td>
						<td><?php  echo $row['vat']; ?></td>
						<td><?php  echo $row['lenght']; ?></td>
						<td><?php  echo $row['width']; ?></td>
						<td><?php  echo $row['height']; ?></td>
						<td><?php  echo $row['net_weight']; ?></td>
						<td><?php  echo $row['gross_weight']; ?></td>
				</tr>
			<?php
				// end loop
				}
			?>
			</tbody>
		</table>