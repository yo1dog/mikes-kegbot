<table id="on-tap-content" cellpadding="0" cellspacing="0">
	<tbody>
        <tr>
        	<td id="border-top-left"></td>
            <td id="border-top-mid"></td>
            <td id="border-top-right"></td>
        </tr>
        <tr>
        	<td id="border-mid-left"></td>
			<td id="border-mid-mid">

<?php
$query = '
SELECT taps.keg_id AS kegId, breweries.name AS breweryName, beers.name AS beerName, (keg_sizes.ounces - IFNULL((SELECT SUM(pours.ounces) FROM pours WHERE pours.keg_id = kegs.id), 0)) AS remaining
FROM taps
LEFT JOIN kegs ON (kegs.id = taps.keg_id)
LEFT JOIN keg_sizes ON (keg_sizes.id = kegs.keg_size_id)
LEFT JOIN beers ON (beers.id = kegs.beer_id)
LEFT JOIN breweries ON (breweries.id = beers.brewery_id)
ORDER BY taps.id ASC';

$result = mysql_query($query);

if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

$end = false;
$none = true;
while ($tap = mysql_fetch_assoc($result))
{
	if ($tap['kegId'] !== NULL)
	{
		$none = false;
		$remaining = floatval($tap['remaining']);
		$beers = $remaining / 12.0;
		?>
        
				<div class="card-container">
                    <div class="card">
                        <div class="card-title">
                            <div>
                                <span><?php echo($tap['breweryName']); ?></span>
                                <span><?php echo($tap['beerName']); ?></span>
                            </div>
                        </div>
                        <div class="card-img" style="background-image:url('images/bottles/<?php echo(str_replace(' ', '', $tap['breweryName'] . $tap['beerName'])); ?>.png');">&nbsp;</div>
                        <div class="card-amount<?php if ($beers < 21) echo(' warning'); ?>"><?php echo(round($remaining)); ?> oz - <?php echo(round($beers)); ?> Beers</div>
                    </div>
                </div>
                        
		<?php
	}
	
	if ($end)
	{
		echo('</div><class id="cardContainer">');
		$end = false;
	}
	else
		$end = true;
}

if ($none)
{
	?>
    				
                <div class="warning">
                    <h2>Nothing on Tap!</h2>
                    <br />
                </div>
    
    <?php
}
?>

			</td>
            <td id="border-mid-right"></td>
        </tr>
        <tr>
        	<td id="border-bottom-left"></td>
            <td id="border-bottom-mid"></td>
            <td id="border-bottom-right"></td>
        </tr>
	</tbody>
</table>