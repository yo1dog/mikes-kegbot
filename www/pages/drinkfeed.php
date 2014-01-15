<?php
$days = array();

// pours
$query = 'SELECT pours.timestamp AS timestamp, breweries.name AS breweryName, beers.name AS beerName, pours.ounces AS ounces FROM pours LEFT JOIN kegs ON (kegs.id = keg_id) LEFT JOIN beers ON (beers.id = kegs.beer_id) LEFT JOIN breweries ON (breweries.id = beers.brewery_id) ORDER BY pours.id DESC LIMIT 100';
$result = mysql_query($query);

if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

$lastPour = NULL;
while ($pour = mysql_fetch_row($result))
{
	$lastPour = $pour;
	
	$oDate = date_create($pour[0]);
	$date = date_format($oDate, 'ymd');
	$longDate = date_format($oDate, 'l, F jS Y');
	
	if (!isset($days[$date]))
		$days[$date] = array($longDate, array());
	
	array_push($days[$date][1], array(date_format($oDate, 'g:i A'), $pour[1], $pour[2], $pour[3]));
}
?>

<table id="drinkfeed-content" cellpadding="0" cellspacing="0">
	<tbody>
        <tr>
        	<td id="border-top-left"></td>
            <td id="border-top-mid"></td>
            <td id="border-top-right"></td>
        </tr>
        <tr>
        	<td id="border-mid-left"></td>
            <td id="border-mid-mid">
            	<div id="drinkfeed-contain">
					<table id="drinkfeed" cellpadding="0" cellspacing="0">
                    
<?php
$latest = true;
foreach ($days as $day)
{
	?>
                        <tr>
                            <td class="drinkfeed-date<?php if($latest) echo(' latest'); ?>" colspan="3">
                                <?php echo($day[0]); ?>
                                <div class="spacer<?php if($latest) echo('-latest'); ?>"></div>
                            </td>
                        </tr>
                        
	<?php
	$first = $latest;
    foreach ($day[1] as $pour)
	{
		?>
                        <tr<?php if($first) { echo(' class="latest"'); $first = false; } ?>>
                            <td class="drink-time">
                            	<div class="align-bottom">
                                    <div class="left">
										<?php echo($pour[0]); ?>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="drink-beer">
                            	<div class="align-bottom">
                                    <div class="left">
                                        <span><?php echo($pour[1]); ?></span>
                                        <span><?php echo($pour[2]); ?></span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="drink-amount">
                            	<div class="align-bottom">
                                    <div class="right">
										<?php echo($pour[3]); ?> oz
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
		<?php
	}
	
	if ($latest)
		$latest = false;
}
?>
                    
					</table>
                 </div>
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