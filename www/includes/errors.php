<?php
/**************************************************
* errors.php
*
* Contains the error number constants and an Exception object for handeling errors
*
* Copyright © 2011 Design Wonders
*/

// error codes
define("MYSQL_CONNECTION_FAILED", 90000);
define("MYSQL_QUERY_ERROR", 90001);
define("MYSQL_RESULT_ERROR", 90002);
define("MYSQL_NO_ROWS", 90003);
define("MISSING_URL_PARAMETER", 90004);
define("PERMISSION_ERROR", 90005);
define("PAGE_NOT_FOUND", 90404);


class CustomException extends Exception
{
	public $mySQLErrorMessage;
	public $mySQLErrorCode;
	public $mySQLQuery;
	
	public function __construct($code, $file, $line, $mySQLErrorMessage = NULL, $mySQLErrorCode = 0, $mySQLQuery = NULL)
	{
		$this->code = $code;
		$this->file = $file;
		$this->line = $line;
		$this->mySQLErrorMessage = $mySQLErrorMessage;
		$this->mySQLErrorCode = $mySQLErrorCode;
		$this->mySQLQuery = $mySQLQuery;
		$this->simpleMessage = NULL;
		
		// set the message text
		switch ($code)
		{
			case MYSQL_CONNECTION_FAILED:
				$this->simpleMessage = "Unable to connect to the database at this time. Please try back latter.";
				break;
			case MYSQL_QUERY_ERROR:
				$this->simpleMessage = "Error executing a database command.";
				break;
			case MYSQL_RESULT_ERROR:
				$this->simpleMessage = "Error reading the database result.";
				break;
			case MYSQL_NO_ROWS:
				$this->simpleMessage = "No rows affected after a database command.";
				break;
			case PAGE_NOT_FOUND:
				$this->simpleMessage = "The page you are looking for could not be found.";
				break;
			case MISSING_URL_PARAMETER:
				$this->simpleMessage = "There is one or more URL parmaters missing.";
				break;
			
			default:
				$this->simpleMessage = "An error has occurred.";
		}
		
		$this->message = 'Error ' .  $this->code . ' in ' . $this->file . ' at line ' . $this->line . ': '. $this->simpleMessage;
		
		if ($this->mySQLErrorMessage !== NULL)
		{
			$this->message .= "\nMySQL Error " . $this->mySQLErrorCode . ': ' . $this->mySQLErrorMessage;
			
			if ($this->mySQLQuery !== NULL)
				$this->message .= "\nQuery Info:" . $this->mySQLQuery;
		}
	}
	
	//////////////////////////////////////////////////
	// GenerateMessage
	//
	//  echos HTML that displays the error message and details in a dynamic show/hide box
	//
	public function GenerateMessage()
	{
		// javascript for showing and hiding the error details
		?>
		<script language="javascript">
		function showErrorDetails()
		{
			document.getElementById("errorDetails").style.display = "block";
			document.getElementById("errorDetailsLink").style.display = "none";
		}
		</script>
        
        <br />
        <div class="errorMessage">
            <strong>Error <?php echo($this->code); ?>:</strong> <?php echo($this->simpleMessage); ?><br />
            <br />	
            <a id="errorDetailsLink" class="errorDetailsLink" href="javascript:showErrorDetails()">Show Error Details<br /></a>
            <div id="errorDetails" class="errorDetails">
                <?php echo($this->file); ?> at line <strong><?php echo($this->line); ?></strong>
            
		<?php
		// only show the MySQL error information if there was a MySQL error
		if ($this->mySQLErrorMessage !== NULL)
		{
			?>
            
                <br /><br />
                MySQL Error:<br />
                <strong><?php echo($this->mySQLErrorCode); ?></strong> <?php
				echo($this->mySQLErrorMessage);
				
			if ($this->mySQLQuery !== NULL)
			{
				?>
                    
                    <br /><br />
                    <strong>Query Info:</strong><br />
                    
                <?php
					echo($this->mySQLQuery);
			}
		}
		
		echo('<br /><br /><strong>Stack Trace:</strong><br />' . $this->getTraceAsString() . '</div></div>');
	}
	
	//////////////////////////////////////////////////
	// GenerateSimpleMessage
	//
	//  echos HTML that displays the error message and details
	//
	public function GenerateSimpleMessage()
	{
		// javascript for showing and hiding the error details
		?>
            <strong><?php echo($this->code); ?></strong> <?php echo($this->simpleMessage); ?><br />
            <br />
            <?php echo($this->file); ?> at line <strong><?php echo($this->line); ?></strong>
            
		<?php
		// only show the MySQL error information if there was a MySQL error
		if ($this->mySQLErrorMessage !== NULL)
		{
			?>
            
            <br /><br />
            MySQL Error:<br />
            <strong><?php echo($this->mySQLErrorCode); ?></strong> <?php
            echo($this->mySQLErrorMessage);
			
			if ($this->mySQLQuery !== NULL)
			{
				?>
                
                <br /><br />
                <strong>Query Info:</strong><br />
                    
                <?php
				echo($this->mySQLQuery);
			}
		}
	}
}
?>