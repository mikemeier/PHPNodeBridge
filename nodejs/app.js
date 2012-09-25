var config = require('./config');

var request = require('request');
var async = require('async');
var bodyParser = require('connect-hopeful-body-parser');
var queryString = require('querystring');

var app = require('http').createServer(handler)
  , io = require('socket.io').listen(app);

app.listen(config.socket.port);

function handler(req, res){
	if(req.method == 'POST'){	
		
		bodyParser()(req, res, function(){
			var socketId = req.body.socketId;
					
			if(!socketId){
				return;
			}
			
			var args = Array.isArray(req.body.args) ? req.body.args : [];
			args.unshift('bridge.message');
			
			io.sockets.clients().every(function(socket){
				if(socketId == socket.id){
					socket.emit.apply(socket, args);
					return false;
				}
				return true;
			});
			
			res.writeHead(200, [["Content-Type", "text/plain"]]);
			res.write('thanks for POST');
			res.end();
			
		});
		
	}else{
		res.writeHead(403, [["Content-Type", "text/plain"]]);
		res.write('only POST allowed');
		res.end();
	}
};

io.sockets.on('connection', function(socket){
	socket.on('bridge.connect', function(bridgeUri, sessionId, cb){
		socket.set('bridgeUri', bridgeUri);
		socket.set('sessionId', sessionId);
		requestToBridge(socket, 'bridge.connect', {}, cb);
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
		
		requestToBridge(socket, 'bridge.message', args, cb);
	});
	
	socket.on('disconnect', function(){
		requestToBridge(socket, 'bridge.disconnect');
  	});
});


function requestToBridge(socket, eventName, data, cb){
	
	var doRequest = function(err, results){
		if(err){
			if(typeof(cb) == "function"){
				cb(err, body);
			}
			return;
		}
        
        var bodyContent = querystring.stringify({
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