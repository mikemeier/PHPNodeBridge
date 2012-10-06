function SocketEvents(requestToBridge){
    this.requestToBridge = requestToBridge;
};

SocketEvents.prototype = {
    
    register: function(socket){
        var requestToBridge = this.requestToBridge;
        
        socket.on('bridge.connect', function(bridgeUri, sessionId, cb){
            socket.set('bridgeUri', bridgeUri);
            socket.set('sessionId', sessionId);

            requestToBridge.execute(socket, 'bridge.connection', {}, cb);
        });

        socket.on('bridge.message', function(){
            var args = [];
            for(i in arguments){
                args.push(arguments[i]);
            }

            var popedArgs = args.slice(0);
            var cb = popedArgs.pop();

            if(typeof(cb) == "function"){
                args = popedArgs;
            }else{
                cb = null;
            }

            requestToBridge.execute(socket, 'bridge.message', args, cb);
        });

        socket.on('disconnect', function(){
            requestToBridge.execute(socket, 'bridge.disconnection');
        });
    }
    
};

module.exports = function(requestToBridge){
    return new SocketEvents(requestToBridge);
};