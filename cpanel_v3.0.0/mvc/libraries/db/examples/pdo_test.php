<?php

    try{

        echo '<br />******************************<br />';

        //  Database credentials
        $host   = 'localhost';
        $dbname = 'vehicles_db';

        $user   = "root";
        $pass   = "ahmad";

        $dsn = "mysql:host=$host;dbname=$dbname";

        //'unix_socket' => '/tmp/mysql.sock’,

        //for persistent connection
        //$options = array( PDO::ATTR_PERSISTENT, true );

        //Open a connection
        $connection = new PDO($dsn, $user, $pass);
        
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM `cars` WHERE `car_id` = ? AND `plate` = ?";
        
        $id    = 1;
        $plate = '0001';
        
        $stmt = $connection->prepare($sql);

        $stmt->execute(array($id, $plate));

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($rows);
        
        echo '<br />******************************<br />';
        
////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM `cars` WHERE `car_id` = :id AND `plate` = :plt";
        
        $id    = 1;
        $plate = '0001';
        
        $stmt = $connection->prepare($sql);

        $stmt->bindParam("id",  $id,    PDO::PARAM_INT);
        $stmt->bindParam("plt", $plate, PDO::PARAM_STR);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($rows);
        
        echo '<br />******************************<br />';

////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM `cars` WHERE `car_id` = ?";
        
        $id    = 1;
        $plate = '0001';
        
        $arr = array(1, '0001');
        
        $stmt = $connection->prepare($sql);

        $stmt->bindParam(1, $arr[0], PDO::PARAM_INT);
        //$stmt->bindParam(2, $arr[1], PDO::PARAM_STR);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($rows);
        
        echo '<br />******************************<br />';

////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM `cars` WHERE `car_id` = ? AND `plate` = ?";
        
        $id    = 1;
        $plate = '0001';
        
        $arr = array(1, '0001');
        
        $stmt = $connection->prepare($sql);

        $stmt->bindValue(1, $arr[0], PDO::PARAM_INT);
        $stmt->bindValue(2, $arr[1], PDO::PARAM_STR);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($rows);
        
        echo '<br />******************************<br />';

////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM `cars` WHERE `car_id` = :id AND `plate` = :plt";
        
        $id    = 1;
        $plate = '0001';
        
        $stmt = $connection->prepare($sql);

        $stmt->execute( array( "id" => $id, "plt" => $plate ) );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($rows);
        
        echo '<br />******************************<br />';

////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM `cars` WHERE `car_id` = ? AND `plate` = ?";
        
        $id    = 1;
        $plate = '0001';
        
        $stmt = $connection->prepare($sql);

        $stmt->execute(array($id, $plate));

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($rows);

////////////////////////////////////////////////////////////////////////////////

        
    } catch (PDOException $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
        echo $e->getMessage();
    }

?>
