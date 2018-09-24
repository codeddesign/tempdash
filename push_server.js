const app = require('express')();
const http = require('http').Server(app);
const io = require('socket.io')(http);
const pusher = require('./push_server_library/pusher');
const port = 4584;
const process_timeout_interval_ms = 5000;
let clients = [];

/**
 * Handle "on connection" event
 */
io.on('connection', (socket) => {

    // Add connection to list of clients upon connection
    pusher.addClient({socket: socket, user_data: socket.conn.request._query.user_data});

    // Remove the client from the collection of clients when one disconnects
    socket.on('disconnect', () => {
        pusher.removeClient(socket.id);
    });
});

// Start process to poll Lexicon connector for each client
setInterval(() => { pusher.fetchAndBroadcastDataToClients(); }, process_timeout_interval_ms);

/**
 * POST - Handle incoming data from
 */
app.post('/', function(req, res){
    res.status(200).json({status: 200, client_count: clients.length});
});

// Start server
http.listen(port, function(){
    console.log(`Listening on port ${port}...`);
});

