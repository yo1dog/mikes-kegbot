<form action="?s=newKeg" method="post">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>Size:</td>
            <td>
                <select name="kegSizeId">
                
                <?php
                $query = 'SELECT id, name FROM keg_sizes';
                $result = mysql_query($query);
    
                if ($result === false)
                    throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);
                
                $l = mysql_num_rows($result);
                for ($i = 0; $i < $l; $i++)
                    echo('<option value="' . mysql_result($result, $i, 0) . '">' . mysql_result($result, $i, 1) . '</option>');
                ?>
                    
                </select>
            </td>
        </tr>
        <tr>
            <td>Beer:</td>
            <td>
                <select name="beerId">
                
                <?php
                $query = 'SELECT beers.id, breweries.name, beers.name FROM beers LEFT JOIN breweries ON (breweries.id = beers.brewery_id) ORDER BY breweries.name ASC';
                $result = mysql_query($query);
    
                if ($result === false)
                    throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);
                
                $l = mysql_num_rows($result);
                for ($i = 0; $i < $l; $i++)
                    echo('<option value="' . mysql_result($result, $i, 0) . '">' . mysql_result($result, $i, 1) . ' ' . mysql_result($result, $i, 2) . '</option>');
                ?>
                    
                </select>
            </td>
        </tr>
        <tr>
            <td>Tap:</td>
            <td>
                <select name="tapId">
                	<option value="NULL"></option>
                <?php
                $query = 'SELECT id, name FROM taps';
                $result = mysql_query($query);
    
                if ($result === false)
                    throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);
                
                $l = mysql_num_rows($result);
                for ($i = 0; $i < $l; $i++)
                    echo('<option value="' . mysql_result($result, $i, 0) . '">' . mysql_result($result, $i, 1) . '</option>');
                ?>
                
                </select>
            </td>
        </tr>
    </table>
    <br />
    <input type="submit" value="Submit" />
</form>