<?php

include './bootstrap.php';
//
////error handler function
//function customError($errno, $errstr) {
//    echo "<b>Error:</b> [$errno] $errstr";
//}
//
////set error handler
//set_error_handler("customError");
try{

    $c = null;

    echo 'before c';

    $c->getError();

    echo 'after c';

} catch (Error $e){
    echo 'catched ';
    echo 'catched '.$e->getMessage();    
} catch (Exception $e){
    echo 'catched '.$e->getMessage();    
}
//trigger error
echo($test);

?> 