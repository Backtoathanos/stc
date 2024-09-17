import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import Footer from "./layouts/Footer";
import Navbar from "./layouts/Navbar";
import Sidebar from './layouts/Sidebar';
import { useLocation } from 'react-router-dom';
import './Datatable.css';

export default function Dashboard() {
    const location = useLocation();
    const [data, setData] = useState([]);
    const [search, setSearch] = useState(''); // State for search filter
    const currentRoute = location.pathname === "/dashboard" ? "dashboard" : "inventory";

    // Fetch data from PHP API
    useEffect(() => {
        fetch('http://localhost/stc/stc_gld/vanaheim/getInventoryData.php')  // Update URL with your PHP API path
            .then(response => response.json())
            .then(data => setData(data))
            .catch(error => console.error('Error fetching data:', error));
    }, []);

    // Define columns for DataTable
    const columns = [
        {
            name: 'Slno',
            selector: (row, index) => index + 1,  // auto-generate serial number
            sortable: true,
            center: true
        },
        {
            name: 'Product Description',
            selector: row => row.stc_product_name,
            sortable: true,
        },
        {
            name: 'Unit',
            selector: row => row.stc_product_unit,
            sortable: true,
            center: true
        },
        {
            name: 'Inv Qty.',
            selector: row => row.stc_item_inventory_pd_qty,
            sortable: true,
            right: true
        },
        {
            name: 'Sale Rate',
            selector: row => row.stc_product_id,
            sortable: true,
            right: true
        }
    ];

    // Filter data based on search input
    const filteredData = data.filter(item =>
        item.stc_product_name.toLowerCase().includes(search.toLowerCase()) ||
        item.stc_product_unit.toLowerCase().includes(search.toLowerCase())
    );

    return (
        <div className="wrapper ">
            <Sidebar activeRoute={currentRoute} />
            <div className="main-panel">
                <Navbar />
                <div className="content">
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col-md-12 col-sm-12">
                                <div className="card card-chart">
                                    <div className="card-header">
                                        <h2 className="text-center">Inventory</h2>
                                        <div className="text-center">
                                            <input
                                                type="text"
                                                placeholder="Search..."
                                                value={search}
                                                onChange={(e) => setSearch(e.target.value)}
                                                className="form-control"
                                                style={{ width: '300px', margin: '0 auto' }}
                                            />
                                        </div>
                                    </div>
                                    <div className="card-body">
                                    <DataTable 
                                        columns={columns}
                                        data={filteredData}  // Use filtered data
                                        pagination   // Enables pagination
                                        highlightOnHover  // Adds hover effect
                                        striped  // Adds striped rows
                                        className="data-table"  // Apply the custom class
                                    />
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            <i className="material-icons">access_time</i> updated a minutes ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Footer />
            </div>
        </div>
    );
}
