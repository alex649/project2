<?php
/****************************************
 * model.php
 *
 * CSCI S-75
 * Project 2
 * Alex Spivakovsky
 *
 * Model for station information chaching
 ****************************************/

// database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'CS75BART');

$pdo = NULL;

/*
 * connect_to_database() - Connect to database using PDO
 *
 * 
 * @return PDO $pdo object
 */
function connect_to_database()
{
    global $pdo;

    // connect to server and select database
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

    return $pdo;
}

/*
 * get_quote_data() - Get Yahoo quote data for a symbol
 *
 * @param string $symbol
 */
function get_quote_data($symbol)
{
	$result = array();
	$url = "http://download.finance.yahoo.com/d/quotes.csv?s={$symbol}&f=sl1n&e=.csv";
	$handle = fopen($url, "r");
	if ($row = fgetcsv($handle))
		if (isset($row[1]))
			$result = array("symbol" => $row[0],
						"last_trade" => $row[1],
							"name" => $row[2]);
	fclose($handle);
	return $result;
}

/*
 * getStationNames() - get station names from database
 *
 * @return array $names
 *
 */
function getStationNames() 
{
    global $pdo;
    
    $error = false;
    $names = array();

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
	return;
    }

    try
    {
	$query = sprintf("SELECT name FROM stations WHERE state='CA'");
        $results = $pdo->query($query);

	$i = 0;
	// get user id
        foreach ($results as $result)
        {
	    $names[$i] = $result['name'];

	    $i++;
        }
    }
    catch (Exception $e)
    {
	$error = "Could not query table 'stations'.";
	return $error;
    }

    return $names;
}

/*
 * write_station_info_to_db() - write station information for caching to database
 *
 */
function write_station_info_to_db() 
{
    global $pdo;
    
    $error = false;
    $data = array();

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
	return;
    }

    try
    {
	$query = sprintf("SELECT * FROM stations WHERE address='12th St. Oakland City Center'");
        $results = $pdo->query($query);

	if ($results == NULL)
	{
	    $query = sprintf("CREATE TABLE stations (
			        name VARCHAR(30) NOT NULL,
				latitude FLOAT(11,8) NOT NULL,
				longitude FLOAT(11,8) NOT NULL,
				address VARCHAR(30) NOT NULL,
				city VARCHAR(20) NOT NULL,
				state CHAR(2) NOT NULL,
				zipcode INT(6) NOT NULL,
				PRIMARY KEY ( name )
		    );");
            $pdo->query($query);

	    $url = 'http://api.bart.gov/api/stn.aspx?cmd=stns&key=MW9S-E7SL-26DU-VV8V';
            $xml = simplexml_load_file($url);

            foreach ($xml->xpath("/root/stations/station") as $station)
            {
		$name = $station->name;
		$latitude = $station->gtfs_latitude;
		$longitude = $station->gtfs_longitude;
		$address = $station->address;
		$city = $station->city;
		$state = $station->state;
		$zipcode = $station->zipcode;

		try
        	{
            	    $query = sprintf("INSERT INTO stations (`name`, `latitude`, `longitude`, `address`, `city`, `state`, `zipcode`) 
			VALUES ('$name', '$latitude', '$longitude', '$address', '$city', '$state', '$zipcode')");
            	    $results = $pdo->query($query);
        	}
        	catch (Exception $e)
       	        {
            	    $error = "Unable to query database.  Please check username and password.";
	    	    return $error;
        	}	
    	    }
	}
    }
    catch (Exception $e)
    {
	$error = "Could not query table 'stations'.";
	return $error;
    }

    return $error;
}
