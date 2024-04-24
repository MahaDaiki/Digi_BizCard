import './bootstrap';
const express = require('express');
const cors = require('cors');
const app = express();
const PORT = process.env.PORT || 8000;

// Middleware
app.use(cors());
app.use(express.json()); // for parsing application/json

// Routes
app.get('/api/user', (req, res) => {
  // Handle user endpoint logic here
});

app.post('/api/login', (req, res) => {
  // Handle login endpoint logic here
});

app.post('/api/cards/add', (req, res) => {
  // Handle add card endpoint logic here
});

// Start the server
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
