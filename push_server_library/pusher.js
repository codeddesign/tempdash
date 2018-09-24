
class Pusher {

    constructor() {
        this.clients = [];
    }

    /**
     * Add client to collection
     *
     * @param client
     */
    addClient(client) {
        this.clients.push(client);
    }

    /**
     * Remove client by socket id.
     *
     * @param client_socket_id
     */
    removeClient(client_socket_id) {
        this.clients = this.clients.filter(c => c.socket.id !== client_socket_id);
    }

    /**
     * Fetches relevant data for each client from upstream Lexicon connector
     * for each client and passes it to the dashboard view
     *
     * @param clients
     */
    fetchAndBroadcastDataToClients() {
        for (let client of this.clients)
        {
            console.log(client.client_id);
        }
    }
}

module.exports = new Pusher();