import './App.css';
import {BrowserRouter, Routes, Route} from 'react-router-dom';
import Protected from './Protected';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import Inventory from './components/Inventory';
import Challan from './components/Challan';
import PrintPreview from './components/PrintPreview';
import Invoice from './components/invoice';
import Order from './components/order';
import Requisitions from './components/Requisitions';
import CookieDebugger from './components/CookieDebugger';

function App() {
  return (
    <BrowserRouter basename="/stc_gld">
      <Routes>
        <Route index element={<Login />}/>
        <Route path="/dashboard" element={<Protected Component={Dashboard} />}/>
        <Route path="/inventory" element={<Protected Component={Inventory} />}/>
        <Route path="/challan" element={<Protected Component={Challan} />}/>
        <Route path="/invoice" element={<Protected Component={Invoice} />}/>
        <Route path="/print-preview" element={<Protected Component={PrintPreview} />}/>
        <Route path="/order" element={<Protected Component={Order} />}/>
        <Route path="/requisitions" element={<Protected Component={Requisitions} />}/>
      </Routes>
      <CookieDebugger />
    </BrowserRouter>
  );
}

export default App;
