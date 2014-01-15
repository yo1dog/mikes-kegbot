<form action="?s=newBeer" method="post">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>Name:</td>
            <td>
                <input name="name" type="text" maxlength="45" />
            </td>
        </tr>
        <tr>
            <td>Brewery:</td>
            <td>
                <select name="breweryId">
                
                <?php
                $query = 'SELECT id, name FROM breweries ORDER BY name ASC';
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
            <td>ABV:</td>
            <td>
                <input name="abv" type="text" maxlength="5" />
            </td>
        </tr>
        <tr>
            <td>Style:</td>
            <td>
                <input name="style" type="text" maxlength="45" />
            </td>
        </tr>
        <tr>
            <td>IBU:</td>
            <td>
                <input name="ibu" type="text" maxlength="4" />
            </td>
        </tr>
        <tr>
            <td>Description:</td>
            <td>
                <textarea name="description"></textarea>
            </td>
        </tr>
    </table>
    <br />
    <input type="submit" value="Submit" />
</form>
