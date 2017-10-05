<?php

    session_start();

    //header("Content-type: image/png");

    //Now lets use md5 to generate a totally random string
    //$md5 = md5(microtime() * mktime());
    //$md5 = md5( ? * time());

    $md5 = md5( rand(10000,99999) );

    /*
        We dont need a 32 character long string so we trim it down to 5
    */
    $string = substr($md5,0,5);

    /*
    Now for the GD stuff, for ease of use lets create
     the image from a background image.
    */

    $captcha = imagecreatefrompng("./images/captcha/captcha_image_bg.png");

    /*
    Lets set the colours, the colour $line is used to generate lines.
     Using a blue misty colours. The colour codes are in RGB
    */

    $color = imagecolorallocate($captcha, 99, 99, 99);
    $line  = imagecolorallocate($captcha,233,239,239);

    /*
    Now to make it a little bit harder for any bots to break,
    assuming they can break it so far. Lets add some lines
    in (static lines) to attempt to make the bots life a little harder
    */
    imageline($captcha, 2,   2,  20,  30, $line);
    imageline($captcha, 20,  2,  40,  30, $line);
    imageline($captcha, 40,  2,  60,  30, $line);
    imageline($captcha, 60,  2,  80,  30, $line);
    imageline($captcha, 80,  2,  100, 30, $line);
    imageline($captcha, 100, 2,  120, 30, $line);
    imageline($captcha, 120, 2,  140, 30, $line);
    imageline($captcha, 140, 2,  148, 20, $line);
    imageline($captcha, 148, 2,  2,   32, $line);

    /*
    Now for the all important writing of the randomly generated string to the image.
    */
    imagestring($captcha, 100, 55, 10, $string, $color);

    /*
    Encrypt and store the key inside of a session
    */

    $_SESSION['captcha_key'] = md5($string);

    /*
    Output the image
    */
    header("Content-type: image/png");
    imagepng($captcha);

?>