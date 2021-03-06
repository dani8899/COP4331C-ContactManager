
<?php
	//JSON FORMATS
	//  RECIEVED JSON
	// 	{
	//		"login" 	: "login of new user"
	//		"password" 	: "hashed password of new user"
	//	}
	//  SENT JSON
	// 	{
	//		"id"	:	"userID"
	//		"login" :	"The username of the user"
	//	}

	$inData = getRequestInfo();

	$id = 0;

	$conn = new mysqli("localhost", "raph", "password", "Contact Manager");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$sql = "SELECT ID FROM Users WHERE Login='" . $inData["login"] . "' and Password='" . $inData["password"] . "'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$id = $row["ID"];

			returnWithInfo($id);
		}
		else
		{
			returnWithError( "No Records Found" );
		}
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"id":0,"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo($id)
	{
		$retValue = '{"id":' . $id . ',"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
