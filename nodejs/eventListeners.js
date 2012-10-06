function EventListeners(eventEmitter){
    this.eventEmitter = eventEmitter;
}

EventListeners.prototype = {
    
    register: function(){
        var eventEmitter = this.eventEmitter;
        
        eventEmitter.on('bridge.request.user.message', function(io, req, res, next){
            
            
            next();
        });
        
    }
    
}

module.exports = function(eventEmitter){
    return new EventListeners(eventEmitter);
}