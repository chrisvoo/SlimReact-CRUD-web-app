import React from 'react';
import { BrowserRouter as Router } from 'react-router-dom';
import { Header } from './navbar/Header';
import { Main } from './main/Main';

function App() {
  return (
    <Router>
      <Header />
      <Main />
    </Router>
  );
}

export default App;
