function Bridge(io){
    this.io = io;
};

Bridge.prototype = {

    emit: function(){
        var args = [];
        var eventName = null;

        for(var i in arguments){
            var argument = arguments[i];
            if(eventName == null){
                eventName = argument;
            }else{
                args.push(argument);
            }
        }

        var popedArgs = args.slice(0);
        var cb = popedArgs.pop();

        if(typeof(cb) == "function"){
            args = popedArgs;
        }else{
            cb = null;
        }

        this.io.emit('message', eventName, args, cb);

        return this;
    },

    on: function(eventName, cb){
        var self = this;

        this.io.on(eventName, function(){
            console.log(arguments);
            cb.apply(self, arguments);
        });
    }

};

var mikemeier_php_node_bridge = Bridge;