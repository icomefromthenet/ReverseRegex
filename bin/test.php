<?php
//-----------------------------------------
// Bootstrap an app for testing
//-----------------------------------------

require __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';

call_user_func(function(){
    // Pimple uses static , its a global by another name.

    $boot = new \ReverseRegex\PimpleBootstrap(); 
    $pimple = $boot->boot(new \Pimple());                   
    $_GLOBALS['pimple'] = $pimple;
    return $pimple;                    

});
/* End of File */