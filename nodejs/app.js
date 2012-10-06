var eventEmitter2 = require('eventemitter2').EventEmitter2;

var eventEmitter = new eventEmitter2({
    wildcard: true,
    delimiter: '.',
    maxListeners: 20
});

var 
    config = require('./config'),

    request = require('request'), 
    async = require('async'), 
    queryString = require('querystring'),
    bodyParser = require('connect-hopeful-body-parser'),
    
    requestHandler = require('./requestHandler')(eventEmitter, bodyParser),
    requestToBridge = require('./requestToBridge')(request, async, queryString),
    
    socketEvents = require('./socketEvents')(requestToBridge),
    eventListeners = require('./eventListeners')(eventEmitter),
    
    server = require('http').createServer(requestHandler.getHandler()),
    io = require('socket.io').listen(server)
;

requestHandler.setIo(io);
eventListeners.register();

io.sockets.on('connection', function(socket){
	socketEvents.register(socket);
});

server.listen(config.socket.port);