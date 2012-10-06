<?php

use mikemeier\PHPNodeBridge\Bridge;

/* @var $bridge Bridge */
$bridge = require '../app/service.php';

session_start();

$identification = hash('sha512', session_id());

?>
<script type="text/javascript" src="<?=$bridge->getSocketIoClientUri(); ?>"></script>
<script type="text/javascript">
    var socket = io.connect('<?=$bridge->getSocketIoServerConnectionUri($identification);?>');
    
    socket.on('demo.event', function(paraA, paraB){
        console.log(paraA);
        console.log(paraB);
    });
</script>