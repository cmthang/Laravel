var fs = require('fs');
var app = require('express')();
var https = require('http');
var redis = require('redis');
var port = 6002;

app.get('/', function(req, res) {
    res.sendFile('/home/ubuntu/website-admin/index.html');
});

// Certificate were created from openssl command
//create certifcate https://stackoverflow.com/questions/31156884/how-to-use-https-on-node-js-using-express-socket-io
var options = {
    // key: fs.readFileSync('./file.pem'),
    // cert: fs.readFileSync('./file.crt')
};

var server = https.createServer(options, app);
var io = require('socket.io')(server);

server.listen(port, function(){
    console.log('Listening on Port '+ port);
});

io.on('connection', function (socket){
    console.log('A new client connected: ' + socket.id);

    var redisClient = redis.createClient();

    redisClient.subscribe('channel-name');
    redisClient.on('message', function(channel, message) {
        console.log('New message: ' + message + '. In channel: ' + channel);
        message = JSON.parse(message);
        socket.emit(channel + ':' + message.event, message.data);
    });

    socket.on('disconnect', function () {
        console.log('A new client disconnected: ' + socket.id);
        redisClient.quit();
    });
});

//
// var fs = require('fs');
// var app = require('express')();
// var https = require('https');
// var redis = require('redis');
// var port = 3000;
//
// // Certificate were created from openssl command
// //create certifcate https://stackoverflow.com/questions/31156884/how-to-use-https-on-node-js-using-express-socket-io
// var options = {
//     key: fs.readFileSync('file.pem'),
//     cert: fs.readFileSync('file-cert.pem')
// };
//
// var server = https.createServer(options, app);
// var io = require('socket.io')(server);
//
// server.listen(port, function(){
//     console.log('Listening on Port '+ port);
// });
//
// io.on('connection', function (socket){
//     console.log('A new client connected: ' + socket.id);
//
//     var redisClient = redis.createClient();
//
//     redisClient.subscribe('channel-name');
//     redisClient.on('message', function(channel, message) {
//         console.log('New message: ' + message + '. In channel: ' + channel);
//         message = JSON.parse(message);
//         socket.emit(channel + ':' + message.event, message.data);
//     });
//
//     socket.on('disconnect', function () {
//         console.log('A new client disconnected: ' + socket.id);
//         redisClient.quit();
//     });
// });
