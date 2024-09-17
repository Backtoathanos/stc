import logo from './logo.svg';
import './App.css';
import {BrowserRouter, Routes, Route, Link} from 'react-router-dom';
import Protected from './Protected';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import Inventory from './components/Inventory';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route index element={<Login />}/>
        <Route path="/dashboard" element={<Protected Component={Dashboard} />}/>
        <Route path="/inventory" element={<Protected Component={Inventory} />}/>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
