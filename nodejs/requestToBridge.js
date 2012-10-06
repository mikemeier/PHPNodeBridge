function RequestToBridge(request, async, queryString){
    this.request = request;
    this.async = async;
    this.queryString = queryString;
};

RequestToBridge.prototype = {

    execute: function(socket, eventName, data, cb){

        var request = this.request;
        var async = this.async;
        var queryString = this.queryString;

        var doRequest = function(err, results){
            if(err){
                if(typeof(cb) == "function"){
                    cb(err);
                }
                return;
            }

            var bodyContent = queryString.stringify({
                socketId: socket.id,
                sessionId: results.sessionId,
                event: eventName,
                data: JSON.stringify(data)
            });

            request.post({
                headers: {'content-type' : 'application/x-www-form-urlencoded'},
                uri: results.bridgeUri,
                body: bodyContent
            }, function(err, response, body){
                if(typeof(cb) == "function"){
                    cb(err, body);
                }
                return;
            });
        };

        async.parallel({
            bridgeUri: function(cb){
                socket.get('bridgeUri', function(err, bridgeUri){
                    if(!bridgeUri){
                        return cb('no bridgeUri defined');
                    }
                    return cb(err, bridgeUri);
                });
            },
            sessionId: function(cb){
                socket.get('sessionId', function(err, sessionId){
                    if(!sessionId){
                        return cb('no sessionId defined');
                    }
                    return cb(err, sessionId);
                });
            }
        }, doRequest);
        
    }
  
};

module.exports = function(request, async, queryString){
    return new RequestToBridge(request, async, queryString);
}