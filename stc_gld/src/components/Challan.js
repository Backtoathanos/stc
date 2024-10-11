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
import Swal from 'sweetalert2';
import { Modal, Button } from 'react-bootstrap';
import Select from 'react-select';

export default function ChallanDashboard() {
    const location = useLocation();
    useEffect(() => {
        document.title = "STC GLD || Challan";
    }, []);

    const [showModal, setShowModal] = useState(false);
    const [showModal1, setShowModal1] = useState(false);

    const [data, setData] = useState([]);
    const [search, setSearch] = useState(''); // State for search filter
    const [loading, setLoading] = useState(true); // Loading state
    const currentRoute = location.pathname === "/challan" ? "challan" : "dashboard";

    const [selectedRows, setSelectedRows] = useState([]);

    const [challanOptions, setChallanOptions] = useState([]);
    const [selectedChallan, setSelectedChallan] = useState(null);

    const [selectedChallanForPayment, setSelectedChallanForPayment] = useState(null); // Selected row for payment
    const [paymentAmount, setPaymentAmount] = useState(''); // Payment input


    const fetchData = debounce((query = '') => {
        if (query.length > 3 || query === '') {
            setLoading(true);
            // Send the search query as a parameter to the API
            axios.get(`https://stcassociate.com/stc_gld/vanaheim/index.php?action=getChallan&search=${query}`)
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
    const handleAddPayment = (row) => {
        const dues = (row.rate * row.qty) - row.paid_amount; // Calculate dues
        setPaymentAmount(dues.toFixed(2)); // Set dues in paymentAmount with 2 decimal places
        setSelectedChallanForPayment(row); // Set the selected challan
        setShowModal(true); // Show the modal
    };    

    // Define columns for DataTable
    const columns = [
        {
            name: 'Select',
            cell: row => (
                <input
                    type="checkbox"
                    className="form-control"
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
            name: 'Action',
            selector: row => row.created_by,
            cell: row => (
                <button onClick={() => handleAddPayment(row)}>Add Payment</button>
            ),
            button: true,
            sortable: true,
            center: true
        },
        {
            name: 'Product Name',
            selector: row => row.stc_product_name,
            sortable: true,
            cell: (row) => (
                <div style={{ textAlign: 'center' }}>
                    <span>
                        {row.stc_product_name.length > 20 
                            ? `${row.stc_product_name.substring(0, 20)}...` 
                            : row.stc_product_name}
                    </span>
                    {row.stc_product_name.length > 20 && (
                        <button 
                            onClick={() => alert(row.stc_product_name)} 
                            style={{ 
                                marginTop: '5px', // Add some spacing above the button
                                background: 'none', 
                                color: 'blue', 
                                cursor: 'pointer', 
                                border: 'none' 
                            }}>
                            View More
                        </button>
                    )}
                </div>
            )
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
            name: 'Total',
            selector: row => {
                const total = row.rate * row.qty;
                return total ? total.toFixed(2) : '0.00';  // Round to 2 decimal places
            },
            sortable: false,
            right: true
        },
        {
            name: 'Paid Amount',
            selector: row => row.paid_amount,
            sortable: true,
            right: true
        },
        {
            name: 'Dues',
            selector: row => ((row.rate * row.qty) - row.paid_amount).toFixed(2),
            sortable: false,
            right: true,
            cell: row => {
                const duesValue = ((row.rate * row.qty) - row.paid_amount).toFixed(2);
                return (
                    <span style={{ color: duesValue > 0 ? 'red' : 'black' }}>
                        {duesValue}
                    </span>
                );
            }
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
            selector: row => row.stc_trading_user_name,
            sortable: true,
            center: true
        }
    ];

    const handlePrintChallan = () => {
        setShowModal1(true);  // Show modal when "Print Challan" button is clicked
    };

    const handleCloseModal = () => {
        setShowModal(false); // Close modal when the close button is clicked
        setShowModal1(false);
    };

    const handleChallanUpdate = () => {
        if (selectedRows.length > 0) {
            // Show confirmation dialog before proceeding
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to proceed with updating the selected challans?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with the update
                    setLoading(true);  // Show loading spinner
                    const selectedIds = selectedRows.map(row => row);  // Ensure the correct field is mapped for the IDs

                    axios.post('https://stcassociate.com/stc_gld/vanaheim/index.php?action=updateChallanStatus', {
                        ids: selectedIds
                    })
                        .then(response => {
                            console.log('Update success:', response.data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.data.message
                            }).then(() => {
                                // Reload data after success
                                fetchData();  // Fetch updated challan data
                                getChallan();
                            });
                        })
                        .catch(error => {
                            console.error('Error updating challan:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update challan. Please try again.'
                            });
                        })
                        .finally(() => {
                            setLoading(false);  // Hide loading spinner
                        });
                } else {
                    // If cancelled, do nothing
                    Swal.fire({
                        icon: 'info',
                        title: 'Cancelled',
                        text: 'Challan update was cancelled'
                    });
                }
            });
        }
    };

    const getChallan = () => {
        axios.get('https://stcassociate.com/stc_gld/vanaheim/index.php?action=getDistinctChallanNos')
            .then(response => {
                const data = response.data;
                // Map the data to the format required by react-select
                const options = data.map(item => ({
                    value: item.challan_number,
                    label: item.challan_number
                }));
                setChallanOptions(options);
            })
            .catch(error => {
                console.error('Error fetching challan numbers:', error);
            });
    };

    // Call the getChallan function inside useEffect
    useEffect(() => {
        getChallan();
    }, []);

    const handleSavePayment = () => {
        if (paymentAmount && selectedChallanForPayment) {
            setLoading(true);
            axios.post('https://stcassociate.com/stc_gld/vanaheim/index.php?action=addPayment', {
                challan_id: selectedChallanForPayment.id,
                payment_amount: paymentAmount
            })
                .then(response => {
                    handleCloseModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Payment added successfully!'
                    }).then(() => {
                        // Fetch updated data after payment is added
                        fetchData();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to add payment. Please try again.'
                    });
                })
                .finally(() => {
                    setLoading(false);
                });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please enter a valid payment amount.'
            });
        }
    };


    return (
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
                                            <button className="print-challan" onClick={handlePrintChallan}>Print Challan</button>
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
                                            <button onClick={handleChallanUpdate} className="btn btn-primary">
                                                Proceed to Challan
                                            </button>
                                        )}
                                        {/* Modal */}
                                        <Modal show={showModal1} onHide={handleCloseModal}>
                                            <Modal.Header>
                                                <Modal.Title>Print Challan</Modal.Title>
                                            </Modal.Header>
                                            <Modal.Body>
                                                <Select
                                                    value={selectedChallan}
                                                    onChange={setSelectedChallan}
                                                    options={challanOptions}
                                                    placeholder="Select Challan Number"
                                                    isSearchable={true}
                                                    className="form-control"
                                                />
                                            </Modal.Body>
                                            <Modal.Footer>
                                                <Button variant="secondary" onClick={handleCloseModal}>
                                                    Close
                                                </Button>
                                                <Button variant="primary" onClick={() => {
                                                    // Redirect to the print-preview page with the selected challan number in a new tab
                                                    if (selectedChallan) {
                                                        window.open(`/stc_gld/print-preview?challan_no=${selectedChallan.value}`, '_blank');
                                                    } else {
                                                        alert("Please select a challan number");
                                                    }
                                                }}>
                                                    Print
                                                </Button>
                                            </Modal.Footer>
                                        </Modal>
                                        <Modal show={showModal} onHide={handleCloseModal}>
                                            <Modal.Header>
                                                <Modal.Title>Add Payment</Modal.Title>
                                            </Modal.Header>
                                            <Modal.Body>
                                                {selectedChallanForPayment && (
                                                    <>
                                                        <p>Challan Number: {selectedChallanForPayment.challan_number}</p>
                                                        <input
                                                            type="number"
                                                            className="form-control"
                                                            placeholder="Enter payment amount"
                                                            value={paymentAmount}
                                                            onChange={(e) => setPaymentAmount(e.target.value)}
                                                        />
                                                        <p>Total Dues: {paymentAmount}</p>
                                                    </>
                                                )}
                                            </Modal.Body>
                                            <Modal.Footer>
                                                <Button variant="secondary" onClick={handleCloseModal}>
                                                    Close
                                                </Button>
                                                <Button variant="primary" onClick={handleSavePayment}>
                                                    Save Payment
                                                </Button>
                                            </Modal.Footer>
                                        </Modal>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Footer />
            </div>
        </div >
    );
}
