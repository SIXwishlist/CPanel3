<?

    //pdo transaction

    try{

        $dsn = "mysql:host=$host; dbname=$dbname";

        //for persistent connection
        //$options = array( PDO::ATTR_PERSISTENT, true );

        //Open a connection
        $connection = new PDO($dsn, $user, $pass);

        try {

            //#Step 1
            $connection->beginTransaction();

            //$connection->exec($sql);

            $statement = $connection->prepare($sql);

            $statement->bindParam(1, $value, PDO::PARAM_INT);
            $statement->bindParam(2, $value, PDO::PARAM_STR);
            $statement->bindParam(3, $value, PDO::PARAM_INT);
            // ... 

            //$statement->execute( array(":id" => $id, ":name" => $name, ":age" => $age ) );

            $statement->execute($sql);

            $last_affected_rows = $statement->rowCount();


            //$connection->query($sql);
            
            //#Step 2
            $connection->commit();

        } catch (PDOException $exc) {
            //#Step 3
            $connection->rollBack();
        }
        
        $connection = null;

    } catch (PDOException $exc) {
        echo $exc->getTraceAsString();
    }