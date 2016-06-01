
<table class="striped responsive-table">
	<thead>
		<tr>
			<th>Taak</th>
			<th>Status</th>
			<th>Voltooid om</th>
		</tr>
	</thead>
	<tbody>


<?php
$tussenstappen = json_decode($_POST["tussenstappen"], true);


foreach ($tussenstappen as $tussenstap) { ?>
	<tr>

	<?php
	if ($tussenstap["tussenstap_type"] == 1) { ?>
		<td>Ophalen van verzendadres</td>
	<?php
	} else
	if ($tussenstap["tussenstap_type"] == 2) { ?>
		<td>Overhandigen op station</td>
	<?php
	} else
	if ($tussenstap["tussenstap_type"] == 3) { ?>
		<td>Afleveren op bestemming</td>
	<?php
	} ?>
		<td><?= $tussenstap["verwerkt"] ? "Voltooid" : "Niet voltooid" ?></td>
		<td><?= $tussenstap["verwerkt_ts"] ?></td>
	</tr>

<?php }


?>

	</tbody>

</table>