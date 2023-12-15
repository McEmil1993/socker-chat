```
mkdir socket-io-server
cd socket-io-server
npm init -y
```
## Install npm
````
npm install socket.io
````
# Source Code of  Socket 
# server.js

``````
const http = require('http');
const { Server } = require('socket.io');

class SocketServer {
  constructor() {
    this.server = http.createServer();
    this.io = new Server(this.server, {
      cors: {
        origin: 'http://192.168.68.123:89',
        methods: ['GET', 'POST']
      }
    });
    this.port = 3000; // Choose any available port

    this.setupSocketEvents();
  }

  setupSocketEvents() {
    this.io.on('connection', (socket) => {
      console.log('A user connected');

      socket.on('typing', (data) => {
        console.log('typing:', data);
        socket.broadcast.emit('typing', { user: data.user, isTyping: data.isTyping ,user_id:  data.user_id, image_name:data.image_name});
      });

      socket.on('message', (data) => {
        console.log('Received message:', data);
        this.io.emit('message', data);
      });

      socket.on('disconnect', () => {
        console.log('A user disconnected');
      });
    });
  }

  start() {
    this.server.listen(this.port, () => {
      console.log(`Server running on port ${this.port}`);
    });
  }
}

const socketServer = new SocketServer();
socketServer.start();
``````

# run server
``
node server.js
``
# or install pm2 and craete eco system 
