<?php

use mikemeier\PHPNodeBridge\Bridge;

/* @var $bridge Bridge */
$bridge = require '../app/service.php';

session_start();

?>
<script type="text/javascript" src="<?=$bridge->getSocketIoClientUri(); ?>"></script>
<script type="text/javascript">
    var socket = io.connect('<?=$bridge->getSocketIoServerUri();?>');
    
    socket.emit('bridge.connect', '<?=$bridge->getSocketBridgeUri()?>', '<?=session_id()?>', function(err, result){
        if(err){
            return alert(err);
        }
        console.log(JSON.parse(result));
    });
    
    socket.on('demo.event', function(paraA, paraB){
        console.log(paraA);
        console.log(paraB);
    });
</script>