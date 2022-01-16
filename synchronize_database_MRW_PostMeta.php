<?php
//Install in WordPress root (www)
//Please, update the fields below with your personal data.
//This information can be found in wp-config 

//The purpose of this script is to find the ID in postmeta and MRW Id associated with that order. 
//Then it creates a new field in post_meta with MRW's tracking number so it will be shown when it's exported via XML (Order Export)

    $host = 'localhost';
    $dbname = '...'; //WordPress Database Name
    $username = '...'; //WordPress Database Username
    $password = '...'; //WordPress Database Pass
    
   try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // $sql = "SELECT post_id FROM wp_postmeta";
    $sql = "SELECT order_id, tracking_number FROM wp_mrw_orders";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll();
    // $results = $query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0) { 
    	foreach ($results as $row) {
	        // echo $row["order_id"] . " -> " . $row["tracking_number"] . "<br />";
	        $sql_meta = 'SELECT * FROM wp_postmeta WHERE post_id=' . $row["order_id"] . ' AND meta_key="_wccf_of_seguimiento"';
	        // echo $sql_meta;
	        // echo "</br>";
	        $query_meta = $dbh->prepare($sql_meta);
		$query_meta->execute();
    		$results_meta = $query_meta->fetchAll();
    		if($query_meta->rowCount() > 0) { 
    			foreach ($results_meta as $row_meta) {
    			$sql_up = 'UPDATE wp_postmeta SET meta_value="' . $row["tracking_number"] . '" WHERE post_id=' . $row["order_id"] . ' AND meta_key="_wccf_of_seguimiento"';
    			//echo $row["order_id"] . " -> " . $row["tracking_number"] . "<br />";
    			echo "</br>";
    			echo "Cambiar " . $row_meta['meta_value'] . " por " . $row["tracking_number"];
    			echo "</br>";
    			echo $sql_up;
    			$query_up = $dbh->prepare($sql_up);
    			$query_up->execute();
    			echo "</br>";
    			}
    		} else {
    			$sql_up = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ('" . $row["order_id"] . "', '_wccf_of_seguimiento', '" . $row["tracking_number"] . "')";
    			//echo $row["order_id"] . " -> " . $row["tracking_number"] . "<br />";
    			echo "</br>";
    			echo "Insertar " . $row["tracking_number"];
    			echo "</br>";
    			echo $sql_up;
    			$query_up = $dbh->prepare($sql_up);
    			$query_up->execute();

    			echo "</br>";
    		
    		}
        }
	        // $sql_meta = "SELECT * FROM wp_postmeta WHERE post_id=$row['order_id']";
	        /*
	        $query_meta = $dbh->prepare($sql_meta);
		$query_meta->execute();
    		$results_meta = $query_meta->fetchAll();
    		if($query_meta->rowCount() > 0) { 
    			echo "<h2> Dentro del if </h2>";
    			foreach ($results_meta as $row_meta) {
	       		echo $row_meta["post_id"] . "<br />";
	        	}
	        */

    }
    } catch (PDOException $pe) {
    	die("Error en la conexión con $dbname :" . $pe->getMessage());
    } 
    
       /*
    if($query->rowCount() > 0) { 
    	echo "<h2> Dentro del if </h2>";
    	foreach ($results as $row) {
	        echo $row["order_id"] . " -> " . $row["tracking_number"] . "<br />";
	        $sql_meta = "SELECT * FROM wp_postmeta WHERE post_id=$row['order_id']";
	        $query_meta = $dbh->prepare($sql_meta);
		$query_meta->execute();
    		$results_meta = $query_meta->fetchAll();
    		if($query_meta->rowCount() > 0) { 
    			echo "<h2> Dentro del if </h2>";
    			foreach ($results_meta as $row_meta) {
	       		echo $row_meta["post_id"] . "<br />";
	        	}
    		}
	    }
    */
