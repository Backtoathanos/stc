import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import Footer from './layouts/Footer';
import Navbar from './layouts/Navbar';
import Sidebar from './layouts/Sidebar';
import { useLocation } from 'react-router-dom';
import { RotatingLines } from 'react-loader-spinner';
import './Datatable.css';
import axios from 'axios';
import { debounce } from 'lodash';

export default function ChallanDashboard() {
    const location = useLocation();
    useEffect(() => {
        document.title = "STC GLD || Challan";
    }, []);

    const [data, setData] = useState([]);
    const [search, setSearch] = useState(''); // State for search filter
    const [loading, setLoading] = useState(true); // Loading state
    const currentRoute = location.pathname === "/challan" ? "challan" : "dashboard";

    const [selectedRows, setSelectedRows] = useState([]);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [challanNumber, setChallanNumber] = useState('');

    const fetchData = debounce((query = '') => {
        if (query.length > 3 || query === '') {
            setLoading(true);
            // Send the search query as a parameter to the API
            axios.get(`http://localhost/stc/stc_gld/vanaheim/index.php?action=getChallan&search=${query}`)
                .then(response => {
                    const resultData = response.data;
                    if (Array.isArray(resultData)) {
                        setData(resultData); // Ensure data is an array
                    } else {
                        setData([]); // If it's not an array, set it to an empty array
                    }
                    setLoading(false);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    setData([]); // Set to an empty array in case of error
                    setLoading(false);
                });
        }
    }, 500);

    useEffect(() => {
        fetchData(search);
    }, [search]);

    const handleRowSelect = (row) => {
        const isSelected = selectedRows.includes(row.id);
        const newSelectedRows = isSelected
            ? selectedRows.filter(id => id !== row.id)
            : [...selectedRows, row.id];

        setSelectedRows(newSelectedRows);
    };
    // Define columns for DataTable
    const columns = [
        {
            name: 'Select',
            cell: row => (
                <input
                    type="checkbox"
                    checked={selectedRows.includes(row.id)}
                    onChange={() => handleRowSelect(row)}
                />
            ),
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
            width: '50px'
        },
        {
            name: 'Slno',
            selector: (row, index) => index + 1,  // auto-generate serial number
            sortable: false,
            center: true
        },
        {
            name: 'Challan Number',
            selector: row => row.challan_number,
            sortable: true,
            center: true
        },
        {
            name: 'Product Name',
            selector: row => row.stc_product_name,
            sortable: true,
            center: true
        },
        {
            name: 'Customer Name',
            selector: row => row.gld_customer_title,
            sortable: true,
            center: true
        },
        {
            name: 'Quantity',
            selector: row => row.qty,
            sortable: true,
            right: true
        },
        {
            name: 'Rate',
            selector: row => row.rate,
            sortable: true,
            right: true
        },
        {
            name: 'Paid Amount',
            selector: row => row.paid_amount,
            sortable: true,
            right: true
        },
        {
            name: 'Payment Status',
            selector: row => parseInt(row.payment_status) === 0 ? 'Credit' : parseInt(row.payment_status) === 1 ? 'Cash' : 'A/C',
            sortable: true,
            center: true
        },
        {
            name: 'Status',
            selector: row => {
                return parseInt(row.status) === 0 ? 'Unchallaned' :
                    parseInt(row.status) === 1 ? 'Challaned' :
                        parseInt(row.status) === 2 ? 'Billed' : 'Unknown';
            },
            sortable: true,
            center: true
        },
        {
            name: 'Created Date',
            selector: row => row.created_date,
            sortable: true,
            center: true
        },
        {
            name: 'Created By',
            selector: row => row.created_by,
            sortable: true,
            center: true
        }
    ];

    const handleModalSubmit = () => {
        // Perform update query logic here
        // After successful update, close the modal and reset state if necessary
        setIsModalOpen(false);
        setChallanNumber(''); // Reset challan number
    };

    return (
        <>
            <div className="wrapper">
                <Sidebar activeRoute={currentRoute} />
                <div className="main-panel">
                    <Navbar />
                    <div className="content">
                        <div className="container-fluid">
                            <div className="row">
                                <div className="col-md-12 col-sm-12">
                                    <div className="card card-chart">
                                        <div className="card-header">
                                            <h2 className="text-center">Challan</h2>
                                        </div>
                                        <div className="card-body">
                                            <div className="form-group">
                                                <input
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Search by Challan Number, Product ID, etc..."
                                                    value={search}
                                                    onChange={(e) => setSearch(e.target.value)}
                                                />
                                            </div>
                                            {loading ? (
                                                <div className="spinner-container" style={{ textAlign: 'center' }}>
                                                    <RotatingLines
                                                        strokeColor="blue"
                                                        strokeWidth="5"
                                                        animationDuration="0.75"
                                                        width="50"
                                                        visible={true}
                                                    />
                                                </div>
                                            ) : (
                                                <DataTable
                                                    columns={columns}
                                                    data={data}
                                                    pagination
                                                    highlightOnHover
                                                    striped
                                                    className="data-table"
                                                />
                                            )}
                                            {selectedRows.length > 0 && (
                                                <button onClick={() => setIsModalOpen(true)} className="btn btn-primary">
                                                    Proceed to Challan
                                                </button>
                                            )}
                                        </div>
                                        <div className="card-footer">
                                            <div className="stats">
                                                <i className="material-icons">access_time</i> updated a few minutes ago
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
            {isModalOpen && (
                <div className="modal" style={{ display: 'block' }}>
                    <div className="modal-content modal-xm">
                        <label>Previous Challan Number</label>
                        {/* Replace this with the actual previous challan number if available */}
                        <p>{selectedRows.length > 0 ? "Previous Challan: XYZ123" : ""}</p>
                        <h4>Add Challan Number</h4>
                        <input
                            type="text"
                            value={challanNumber}
                            onChange={(e) => setChallanNumber(e.target.value)}
                            placeholder="Enter Challan Number"
                        />
                        <button onClick={handleModalSubmit}>Submit</button>
                        <button className="btn btn-danger" onClick={() => setIsModalOpen(false)}>Cancel</button>
                    </div>
                </div>
            )}
        </>
    );
}
