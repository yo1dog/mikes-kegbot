<?php
// order
$orderBy = NULL;
$asc = true;
if (isset($_GET['o']))
{
	$orderBy = $_GET['o'];
	
	if (isset($_GET['a']))
		$asc = $_GET['a'] === 't';
}
else
	$orderBy = 'breweryName';

// get taps
$query = 'SELECT taps.id, taps.name, taps.keg_id
FROM taps
LEFT JOIN kegs ON (kegs.id = taps.keg_id)';

$result = mysql_query($query);

if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

$taps = array();
$tapsInput = '" onChange="onTapBeerChanged(this);"><option value="NULL"></option>';

while ($tap = mysql_fetch_row($result))
{
	$tapsInput .= '<option value="' . $tap[0] . '">' . $tap[1] . '</option>';
	array_push($taps, $tap);
}

$tapsInput .= '</select>';
?>




<table cellpadding="0" cellspacing="0">
	<tr>
    	<th>Tap</th>
        <th><a href="?p=adminMenu&o=kegSizeId&a=<?php if ($orderBy === 'kegSizeId') { if ($asc) echo('f'); else echo('t'); } else echo('t'); ?>">Size</a></th>
        <th><a href="?p=adminMenu&o=breweryName&a=<?php if ($orderBy === 'breweryName') { if ($asc) echo('f'); else echo('t'); } else echo('t'); ?>">Brewery</a></th>
        <th><a href="?p=adminMenu&o=beerName&a=<?php if ($orderBy === 'beerName') { if ($asc) echo('f'); else echo('t'); } else echo('t'); ?>">Beer</a></th>
        <th><a href="?p=adminMenu&o=remaining&a=<?php if ($orderBy === 'remaining') { if ($asc) echo('f'); else echo('t'); } else echo('t'); ?>">Remaining</a></th>
        <th></th>

<?php
// get kegs
$query =
'SELECT kegs.id AS id, kegs.is_empty AS isEmpty, beers.name AS beerName, beers.id AS beerId, breweries.name AS breweryName, keg_sizes.id AS kegSizeId, keg_sizes.name AS kegSizeName, (keg_sizes.ounces - IFNULL((SELECT SUM(pours.ounces) FROM pours WHERE pours.keg_id = kegs.id), 0)) AS remaining
FROM kegs
LEFT JOIN beers ON (beers.id = kegs.beer_id)
LEFT JOIN breweries ON (breweries.id = beers.brewery_id)
LEFT JOIN keg_sizes ON (keg_sizes.id = kegs.keg_size_id)';

// empties
if (isset($_GET['e']))
{
	if ($_GET['e'] === 'f')
		$query .= ' WHERE kegs.is_empty = 0';
}

// order
$query .= ' ORDER BY ' . mysql_real_escape_string($orderBy) . ' ' . ($asc? 'ASC' : 'DESC');

$result = mysql_query($query);

if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);




$alt = false;
while ($keg = mysql_fetch_assoc($result))
{
	$class = '';
	$name = '';
	if ($alt)
	{
		$class = 'alt';
		$name = 'alt';
		$alt = false;
	}
	else
		$alt = true;
	
	if ($keg['isEmpty'] === '1')
		$class .= ' empty-keg';
	?>
    
	<tr class="<?php echo($class); ?>" name="<?php echo($name); ?>">
    	<td>
    		<div style="white-space:nowrap;"><?php echo('<select id="' . $keg['id'] . $tapsInput); ?></div>
        </td>
        <td><?php echo($keg['kegSizeName']); ?></td>
        <td><?php echo($keg['breweryName']); ?></td>
        <td><?php echo($keg['beerName']); ?></td>
        <td><?php echo($keg['remaining']); ?></td>
        <td>
        	<a id="<?php echo($keg['id']); ?>Empty" href="javascript:emptyKeg(<?php echo($keg['id'] . ', ' . (($keg['isEmpty'] === '1')? '0' : '1')); ?>)"><?php if ($keg['isEmpty'] === '1') echo('Restore'); else echo('Empty'); ?></a>
        </td>
    </tr>
    
    <?php
}
?>

</table>
<br />
<input type="button" id="submitButton" value="Update" onclick="formSubmit()" /><img id="submitLoading" src="../images/ajaxLoader.gif" style="display:none" /><span id="submitAlertText" class="alertText"></span>

<script type="text/javascript">
var tapKegs = new Array();
var tapKeg;

<?php
$l = count($taps);
for ($i = 0; $i < $l; $i++)
{
	$kegId = $taps[$i][2];
	if ($kegId === NULL || strlen($kegId) == 0)
		$kegId = 'null';
	else
		$kegId = '"' . $kegId . '"';
	?>

tapKegs[<?php echo($i); ?>] = ["<?php echo($taps[$i][0]); ?>", <?php echo($kegId); ?>];

	<?php
	if ($taps[$i][2] !== NULL)
	{
		?>

tapKeg = document.getElementById("<?php echo($taps[$i][2]); ?>");
tapKeg.value = "<?php echo($taps[$i][0]); ?>";
tapKeg.name = "tapKeg";

		<?php
	}
}
?>

function onTapBeerChanged(e)
{
	for (var i = 0; i < tapKegs.length; i++)
	{
		if (tapKegs[i][1] === e.id)
		{
			tapKegs[i][1] = null;
			break;
		}
	}
	
	if (e.value !== "NULL")
	{
		for (var i = 0; i < tapKegs.length; i++)
		{
			if (tapKegs[i][0] === e.value)
			{
				if (tapKegs[i][1] !== null)
					document.getElementById(tapKegs[i][1]).value = "NULL";
				
				tapKegs[i][1] = e.id;
				
				break;
			}
		}
	}
}

<?php
include('../includes/AJAX.js');
include('../includes/errorDisplay.js');
?>

var submitDisabled = false;
function formSubmit(action)
{
	if (submitDisabled)
		return false;
	
	document.getElementById("submitAlertText").style.display = "none";
	document.getElementById("submitLoading").style.display = "inline";
	document.getElementById("submitButton").disabled = true;
	submitDisabled = true;
	
	var params = "taps=";
	
	for (var i = 0; i < tapKegs.length; i++)
	{
		var kegId = tapKegs[i][1];
		
		if (kegId === null)
			kegId = "NULL";
		
		params += tapKegs[i][0] + ':' + kegId + ',';
	}
	
	AJAXHttpRequest(true, "?s=setTaps", params, formValidate, action);
	
	return false;
}

function formValidate(result)
{
	var submitButton = document.getElementById("submitButton");
	var submitAlertText = document.getElementById("submitAlertText");
	var submitLoading = document.getElementById("submitLoading");
	
	submitLoading.style.display = "none";
	submitAlertText.style.display = "inline";
	submitButton.disabled = false;
	submitDisabled = false;
	
	if (result === 404)
	{
		submitAlertText.style.color = "#FF0000";
		submitAlertText.innerHTML = "Error";
		
		showError("Update", "404");
	}
	else if (result.charAt(0) === '1')
	{
		submitAlertText.style.color = "#008000";
		submitAlertText.innerHTML = "Success!";
	}
	else
	{
		submitAlertText.style.color = "#FF0000";
		submitAlertText.innerHTML = "Error";
		
		showError("Update", result);
	}
}

var emptyKegDisabled = false;
function emptyKeg(kegId, emptyVal)
{
	if (emptyKegDisabled)
		return false;
	
	emptyKegDisabled = true;
	
	var anchorElement = document.getElementById(kegId + "Empty");
	anchorElement.kegId = kegId;
	anchorElement.emptyVal = emptyVal;
	
	AJAXHttpRequest(true, "?s=emptyKeg", 'kegId=' + kegId + '&emptyVal=' + emptyVal, emptyKegValidate, anchorElement);
}

function emptyKegValidate(result, anchorElement)
{
	emptyKegDisabled = false;
	
	if (result === 404)
		showError("Empty Keg", "404");
	else if (result.charAt(0) === '1')
	{
		anchorElement.href = "javascript:emptyKeg(" + anchorElement.kegId + ", " + ((anchorElement.emptyVal == 1)? '0' : '1') + ")";
		
		if (anchorElement.emptyVal == 1)
			anchorElement.innerHTML = "Restore";
		else
			anchorElement.innerHTML = "Empty";
		
		var nClass = "";
		if (anchorElement.parentNode.parentNode.name == "alt")
			nClass = "alt ";
		
		if (anchorElement.emptyVal == 1)
			nClass += "empty-keg";
		
		anchorElement.parentNode.parentNode.className = nClass;
	}
	else
		showError("Empty Keg", result);
}

var image = new Image();
image.src = "../images/ajaxLoader.gif";
</script>
