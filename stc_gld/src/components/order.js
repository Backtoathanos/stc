import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import Footer from "./layouts/Footer";
import Navbar from "./layouts/Navbar";
import Sidebar from './layouts/Sidebar';
import { useLocation } from 'react-router-dom';
import CustomerModal from './CustomerModal';
import ProductModal from './ProductModal';
import { RotatingLines } from 'react-loader-spinner'; // Importing spinner from react-loader-spinner
import './Datatable.css';
import axios from 'axios';
import { debounce } from 'lodash';
import { Modal, Button } from 'react-bootstrap';
import Swal from 'sweetalert2';

export default function Order() {
    const location = useLocation();
    useEffect(() => {
        document.title = "STC GLD || Order"; // Set the title
    }, []);
    const API_BASE_URL = process.env.REACT_APP_API_BASE_URL;
    const [showModal, setShowModal] = useState(false);
    const [isFirstModalOpen, setFirstModalOpen] = useState(false);
    const [isSecondModalOpen, setSecondModalOpen] = useState(false);

    const [data, setData] = useState([]);
    const [search, setSearch] = useState(''); // State for search filter
    const [loading, setLoading] = useState(true); // Loading state
    const [modalShow, setModalShow] = useState(false); // State for modal visibility
    const [selectedProductId, setSelectedProductId] = useState(null);
    const [selectedQuantity, setSelectedQuantity] = useState(null);
    const [selecteduname, setselecteduname] = useState(null);
    const [selectedcontact, setselectedcontact] = useState(null);
    const [selectedAddress, setselectedAddress] = useState(null);
    const [selectedRequisition, setselectedRequisition] = useState(null);
    const [selectedListId, setselectedListId] = useState(null);
    
    const [selectedProductRate, setSelectedProductRate] = useState(null);
    const [selectedProductQuantity, setSelectedProductQuantity] = useState(null);
    const currentRoute = location.pathname === "/dashboard" ? "dashboard" : "order";
    const [filteredData, setFilteredData] = useState([]); // To handle filtered data
    
    const fetchData = debounce((search = '') => {
        setLoading(true);
        axios.get(`${API_BASE_URL}/index.php?action=getRequisitions`, search)
            .then(response => {
                const resultData = response.data;
                if (resultData.success && Array.isArray(resultData.products)) {
                    setData(resultData.products);
                } else {
                    setData([]);
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                setData([]);
                setLoading(false);
            });
    }, 500);
    
    useEffect(() => {
        fetchData(); // Fetch data initially without a query
    }, []);
    
    // Define columns for DataTable
    const columns = [
        {
            name: 'Project Name',
            selector: (row) => row.stc_cust_project_title,
            sortable: true,
            center: true,
        },
        {
            name: 'Requisition Items Title',
            selector: (row) => row.stc_cust_super_requisition_list_items_title,
            sortable: true,
            center: true,
        },
        {
            name: 'Product Id (SKU)',
            selector: row => row.stc_product_id,
            sortable: true,
            center: true,
            cell: row => (
                <a
                    href="#"
                    onClick={() => {
                        setSelectedProductId(row.stc_product_id);
                        setSecondModalOpen(true);
                    }}
                >
                    {row.stc_product_id}
                </a>
            )
        },
        {
            name: 'Products Name',
            selector: (row) => row.stc_product_name,
            sortable: true,
            center: true,
        },
        {
            name: 'Supervisor Name',
            selector: (row) => row.stc_cust_pro_supervisor_fullname,
            sortable: true,
            center: true,
        },
        {
            name: 'Supervisor Contact Number',
            selector: (row) => row.stc_cust_pro_supervisor_contact,
            sortable: true,
            center: true,
        },
        {
            name: 'Quantity',
            selector: (row) => row.stc_cust_super_requisition_items_finalqty,
            sortable: true,
            right: true,
        },{
            name: 'Action',
            selector: row => row.stc_product_id,
            sortable: false,
            center: true,
            cell: row => (
                row.status === 1 ? (
                    <a
                        href="#"
                        className="btn btn-primary"
                        onClick={() => {
                            setSelectedProductId(row.stc_product_id);
                            setSelectedQuantity(row.stc_cust_super_requisition_items_finalqty);
                            setselecteduname(row.stc_cust_pro_supervisor_fullname);
                            setselectedcontact(row.stc_cust_pro_supervisor_contact);
                            setselectedRequisition(row.requisition_list_id);
                            setselectedListId(row.id);
                            setShowModal(true);
                        }}
                    >
                        Add
                    </a>
                ) : (
                    <span>Challaned.</span> // Optional: render something else when condition is false
                )
            )
        }        
    ];

    const handleCloseModal = () => {
        setShowModal(false); // Close modal when the close button is clicked
    };
    const handleSaveProduct = () => {
        const userIdCookie = document.cookie.split('; ').find(row => row.startsWith('user_id='));
        if (!userIdCookie) {
            return;  // Stop the function execution if no user_id cookie is found
        }
        const userId = userIdCookie.split('=')[1];
        // Assuming you want to save the data via an API call:
        const productData = {
            ListId: selectedListId,
            productId: selectedProductId,
            quantity: selectedQuantity,
            userName: selecteduname,
            contact: selectedcontact,
            address: selectedAddress,
            requisition: selectedRequisition,
            userId: userId,
        };
        setLoading(true);
        // Example API call
        axios.post(`${API_BASE_URL}/index.php?action=setChallanRequisition`, productData)
            .then(response => {
                setShowModal(false);
                if(response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: "Challan created successfully!"
                    }).then(() => {
                        // Reload data after success
                        fetchData();  // Fetch updated challan data
                    })
                    .finally(() => {
                        setLoading(false);
                    });
                } else {
                    // Handle if the server responds with failure
                    Swal.fire(
                        'Failed!',
                        response.data.message,
                        'error'
                    )
                    .finally(() => {
                        setLoading(false);
                    });
                }
            })
            .catch(error => {
                // Handle error
                console.error("Error saving product", error);
                alert("Error saving product");
            });
    };
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
                                        <h2 className="text-center">Order</h2>
                                    </div>
                                    <div className="card-body">
                                        <div className="form-group">
                                            <input
                                                type="text"
                                                className="form-control"
                                                placeholder="Search by product name or unit..."
                                                value={search}
                                                onChange={(e) => setSearch(e.target.value)} // Update search state
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
                                                className="data-table"  // Custom class for additional styling
                                            />
                                        )}
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            {/* <i className="material-icons">access_time</i> updated a minutes ago */}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Footer />
            </div>
            <Modal show={showModal} onHide={handleCloseModal}>
                <Modal.Header>
                    <Modal.Title>Add Product</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                        <>
                            <p>Product ID</p>
                            <input
                                type="number"
                                className="form-control"
                                placeholder="Enter Produt ID"
                                value={selectedProductId}
                                onChange={(e) => setSelectedProductId(e.target.value)}
                            />
                            <p>Quantity</p>
                            <input
                                type="number"
                                className="form-control"
                                placeholder="Enter Quantity"
                                value={selectedQuantity}
                                onChange={(e) => setSelectedQuantity(e.target.value)}
                            />
                            <p>User Name</p>
                            <input
                                type="name"
                                className="form-control"
                                placeholder="Enter User Name"
                                value={selecteduname}
                                onChange={(e) => setselecteduname(e.target.value)}
                            />
                            <p>Contact</p>
                            <input
                                type="number"
                                className="form-control"
                                placeholder="Enter Contact"
                                value={selectedcontact}
                                onChange={(e) => setselectedcontact(e.target.value)}
                            />
                            <p>Address</p>
                            <textarea
                                className="form-control"
                                placeholder="Enter Address"
                                value={selectedAddress}
                                onChange={(e) => setselectedAddress(e.target.value)}
                            ></textarea>
                        </>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleCloseModal}>
                        Close
                    </Button>
                    <Button variant="primary" onClick={handleSaveProduct}>
                        Save Product
                    </Button>
                </Modal.Footer>
            </Modal>
            
            <ProductModal
                show={isSecondModalOpen}  // Pass the boolean variable, not as a function
                handleClose={() => setSecondModalOpen(false)}
                productId={selectedProductId}
            />
        </div>
    );
}
