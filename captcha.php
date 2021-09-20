<?php
/*********************************************************************
    captcha.php

**********************************************************************/
require_once('main.inc.php');
require(INCLUDE_DIR.'class.captcha.php');

$captcha = new Captcha(5,12,ROOT_DIR.'images/captcha/');
echo $captcha->getImage();
?>
