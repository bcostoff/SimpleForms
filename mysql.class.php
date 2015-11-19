<?php
/**
session_set_cookie_params(3600 * 24 * 7); 
session_save_path('../tmp/');
ini_set('session.gc_maxlifetime', '604800'); 
ini_set('session.gc_probability',1);
ini_set('session.gc_divisor',1);
session_start(); 	// Initialize Session data
ob_start(); 	 	// Turn on output buffering

* MySQL Database Connection Class
* @access public
* @package SPLIB
*/
class MySQL {
    /**
    * MySQL server hostname
    * @access private
    * @var string
    */
    var $host;

    /**
    * MySQL username
    * @access private
    * @var string
    */
    var $dbUser;

    /**
    * MySQL user's password
    * @access private
    * @var string
    */
    var $dbPass;

    /**
    * Name of database to use
    * @access private
    * @var string
    */
    var $dbName;

    /**
    * MySQL Resource link identifier stored here
    * @access private
    * @var string
    */
    var $dbConn;

    /**
    * Stores error messages for connection errors
    * @access private
    * @var string
    */
    var $connectError;

    /**
    * MySQL constructor
    * @param string host (MySQL server hostname)
    * @param string dbUser (MySQL User Name)
    * @param string dbPass (MySQL User Password)
    * @param string dbName (Database to select)
    * @access public
    */
    function MySQL ($host,$dbUser,$dbPass,$dbName) {
        $this->host=$host;
        $this->dbUser=$dbUser;
        $this->dbPass=$dbPass;
        $this->dbName=$dbName;
        $this->connectToDb();
    }

    /**
    * Establishes connection to MySQL and selects a database
    * @return void
    * @access private
    */
    function connectToDb () {
	
	$this->dbConn=@mysqli_connect($this->host,$this->dbUser,$this->dbPass,$this->dbName);

	// Check connection
	if (mysqli_connect_errno($this->dbConn))
	{
		trigger_error("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	/*
        // Make connection to MySQL server
        if (!$this->dbConn = @mysqli_connect($this->host,
                                      $this->dbUser,
                                      $this->dbPass,$this->dbName)) {
            // trigger_error('Could not connect to server');
            $this->connectError=true;
        // Select database
        } else if ( !@mysqli_select_db($this->dbName,$this->dbConn) ) {
            // trigger_error('Could not select database');
            $this->connectError=true;
        }*/
    }

    /**
    * Checks for MySQL errors
    * @return boolean
    * @access public
    */
    function isError () {
        if ( $this->connectError )
            return true;
        $error=mysqli_error ($this->dbConn);
        if ( empty ($error) )
            return false;
        else
            return true;
    }

    /**
    * Returns an instance of MySQLResult to fetch rows with
    * @param $sql string the database query to run
    * @return MySQLResult
    * @access public
    */
    function query($sql) {
        if (!$queryResource=mysqli_query($this->dbConn,$sql))
            trigger_error ('Query failed: '.mysqli_error($this->dbConn).' SQL: '.$sql);
        return new MySQLResult($this,$queryResource);
    }   

	function multi_query($sql) {
	$result ="";
        if (!$queryResource=mysqli_multi_query($this->dbConn,$sql))
            trigger_error ('Query failed: '.mysqli_error($this->dbConn).' SQL: '.$sql);
			
			do {
				/* store first result set */
			$result = mysqli_store_result($this->dbConn);

			if (mysqli_more_results($this->dbConn)) {
				if(is_object($result) && get_class($result) == 'mysqli')mysqli_free_result($result);
			}
			
			} while (mysqli_next_result($this->dbConn));
			
			
			
        return $result;
    }
	function real_escape_string($txt) {
       
        return $this->dbConn->real_escape_string($txt);
    }
	function closeConn() {
		if(isset($this->dbConn)) {
			mysqli_close($this->dbConn);
			unset($this->dbConn);
		}
	}
	function new_id() {	
		return mysqli_insert_id($this->dbConn);
	}
	function num_rows($rs) {
		
	   return mysqli_num_rows($rs);
	}
	
	
}

/**
* MySQLResult Data Fetching Class
* @access public
* @package SPLIB
*/
class MySQLResult {
    /**
    * Instance of MySQL providing database connection
    * @access private
    * @var MySQL
    */
    var $mysql;

    /**
    * Query resource
    * @access private
    * @var resource
    */
    var $query;

    /**
    * MySQLResult constructor
    * @param object mysql   (instance of MySQL class)
    * @param resource query (MySQL query resource)
    * @access public
    */
    function MySQLResult(& $mysql,$query) {
        $this->mysql=& $mysql;
        $this->query=$query;
    }

    /**
    * Fetches a row from the result
    * @return array
    * @access public
    */
    function fetch () {
        if ( $row=mysqli_fetch_array($this->query,MYSQLI_ASSOC) ) {
            return $row;
        } else if ( $this->size() > 0 ) {
            mysqli_data_seek($this->query,0);
            return false;
        } else {
            return false;
        }
    }

    /**
    * Returns the number of rows selected
    * @return int
    * @access public
    */
    function size () {
        return mysqli_num_rows($this->query);
    }

    /**
    * Returns the ID of the last row inserted
    * @return int
    * @access public
    */
    function insertID() {
        return mysqli_insert_id($this->mysql->dbConn);
    }
    
    /**
    * Checks for MySQL errors
    * @return boolean
    * @access public
    */
    function isError () {
        return $this->mysql->isError();
    }
}
?>
