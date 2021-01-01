import React, { Component } from 'react';
import axios from 'axios';
import logo from './logo.svg';
import './App.css';

class App extends Component<{}, { [key: string]: any}> {
  public constructor(props: any) {
    super(props);

    this.state = {
      persons: [],
    };
  }

  componentDidMount() {
    axios.get(`${process.env.REACT_APP_BACKEND_ENDPOINT}/users`)
      .then((res) => {
        const persons = res.data.data;
        this.setState({ persons });
      });
  }

  render() {
    const { persons } = this.state;

    if (!persons.length) {
      return 'loading...';
    }

    return (
      <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <p>
            Edit <code>src/App.tsx</code> and save to reload.
          </p>
          <ul>
            { persons.map((person: any) => <li>{person.firstName}</li>)}
          </ul>
        </header>
      </div>
    );
  }
}

export default App;
