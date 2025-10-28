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
import { Modal, Button, Form } from 'react-bootstrap';
import Swal from 'sweetalert2';
import { FaEdit } from 'react-icons/fa';

export default function Dashboard() {
    // console.log("Hi");
    const location = useLocation();
    useEffect(() => {
        document.title = "STC GLD || Inventory"; // Set the title
    }, []);
    const API_BASE_URL = process.env.NODE_ENV === 'production'
        ? 'https://stcassociate.com/stc_gld/vanaheim'
        : 'http://localhost/stc/stc_gld/vanaheim';
    const [isFirstModalOpen, setFirstModalOpen] = useState(false);
    const [isSecondModalOpen, setSecondModalOpen] = useState(false);

    const [data, setData] = useState([]);
    const [search, setSearch] = useState(''); // State for search filter
    const [loading, setLoading] = useState(true); // Loading state
    const [modalShow, setModalShow] = useState(false); // State for modal visibility
    const [selectedProductId, setSelectedProductId] = useState(null);
    const [selectedProductRate, setSelectedProductRate] = useState(null);
    const [selectedProductQuantity, setSelectedProductQuantity] = useState(null);
    const currentRoute = location.pathname === "/dashboard" ? "dashboard" : "inventory";

    const [page, setPage] = useState(1); // Current page
    const [limit, setLimit] = useState(10); // Rows per page
    const [totalRows, setTotalRows] = useState(0); // Total records from API
    const [currentPage, setCurrentPage] = useState(1);
    function getCookie(name) {
        // 1. Encode the cookie name to handle special characters
        const encodedName = encodeURIComponent(name) + "=";
        
        // 2. Split all cookies into an array
        const cookies = document.cookie.split(';');
        
        // 3. Loop through cookies to find the matching one
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i];
            
            // 4. Remove leading whitespace
            while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
            }
            
            // 5. Check if this cookie starts with our encoded name
            if (cookie.indexOf(encodedName) === 0) {
            // 6. Return the decoded value
            return decodeURIComponent(cookie.substring(encodedName.length, cookie.length));
            }
        }
        
        // 7. Return null if not found
        return null;
    }

    const locationcookie = getCookie("location_stc");
    const fetchData = debounce((query = '', pageNum = page, rowLimit = limit) => {
        if(query.length === 0 || query.length >= 3) {
            setLoading(true);
            axios.get(`${API_BASE_URL}/getInventoryData.php`, {
                params: { search: query, page: pageNum, limit: rowLimit, location: locationcookie }
            })
                .then(response => {
                    if (response.data && response.data.records) {
                        setData(response.data.records);
                        setTotalRows(response.data.total); // Assuming API returns total count
                        setCurrentPage(pageNum);
                    } else {
                        setData([]);
                        setTotalRows(0);
                    }
                    setLoading(false);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    setData([]);
                    setTotalRows(0);
                    setLoading(false);
                });
        }
    }, 500);

    // Fetch data on component mount, page change, search change
    useEffect(() => {
        fetchData(search, page, limit);
    }, [search, page, limit]); // fetchData is called when search, page, or limit changes

    // Handle page change
    const handlePageChange = (newPage) => {
        setPage(newPage); // This will trigger the useEffect above
    };

    // Handle limit change
    const handleLimitChange = (newLimit, newPage) => {
        setLimit(newLimit);
        setPage(newPage || 1); // Reset to page 1 if not specified
    };
    // Define columns for DataTable
    const columns = [
        {
            name: 'Product',
            selector: row => row.stc_product_name,
            sortable: true,
            center: true,
            cell: row => {
                const imageUrl = `https://stcassociate.com/stc_symbiote/stc_product_image/${row.stc_product_image}`;
                const defaultImageUrl = 'https://img.freepik.com/premium-vector/default-image-icon-vector-missing-picture-page-website-design-mobile-app-no-photo-available_87543-11093.jpg?w=996';

                return (
                    <div style={{ position: 'absolute', left: '20px', marginTop: '10px', marginBottom: '10px', display: 'flex', alignItems: 'center', maxWidth: '250px' }}>
                        <div style={{ width: '60px', flexShrink: 0 }}>
                            <img
                                src={row.stc_product_image ? imageUrl : defaultImageUrl}
                                alt="Product"
                                style={{ width: '100%', height: '60px', borderRadius: '5px' }}
                                onError={(e) => e.target.src = defaultImageUrl} // Use default if image fails to load
                            />
                        </div>
                    </div>
                );
            }
        },
        {
            name: 'Product Name',
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
                    <div style={{ marginLeft: '10px', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                        <strong>
                            {row.stc_product_name.length > 20
                                ? `${row.stc_product_name.substring(0, 20)}...`
                                : row.stc_product_name}
                        </strong>
                    </div>
                </a>
            )
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
            name: 'Rack',
            selector: row => row.stc_rack_name,
            sortable: true,
            center: true,
            cell: row => (
                locationcookie !== 'Root' ? (
                    <Button
                        style={{ display: 'flex', alignItems: 'center', gap: 6, backgroundColor: 'rgb(155 216 143)', color: '#000000', fontWeight: 'bold' }}
                        onClick={() => handleOpenRackModal(row)}
                        title="Edit Rack"
                    >
                        <FaEdit style={{ color: 'rgb(129 10 255)', fontSize: '18px' }} />
                        <span>{row.stc_rack_name}</span>
                    </Button>
                ) : (
                    <span>{row.stc_rack_name}</span>
                )
            )
        },
        {
            name: 'Unit',
            selector: row => row.stc_product_unit,
            sortable: true,
            center: true
        },
        {
            name: 'Inv Qty.',
            selector: row => row.stc_item_inventory_pd_qty + ' ' + row.stc_product_unit,
            sortable: true,
            right: true,
            cell: row => (
                <span
                    style={{
                        background: '#afafaf',
                        borderRadius: '10%',
                        padding: '10px',
                        color: '#000000',
                        fontWeight: 'bold',
                        display: 'inline-block',
                        minWidth: '100px',
                        textAlign: 'right'
                    }}
                >
                    {`${row.stc_item_inventory_pd_qty}`}
                    <i style={{
                        fontWeight: '400',
                        minWidth: '100px',
                        textAlign: 'right'
                    }}>{` ${row.stc_product_unit}`}</i>
                </span>
            ),
        },

        {
            name: 'Sale Rate',
            selector: row => row.rate_including_gst,
            sortable: true,
            right: true
        },
        {
            name: 'Action',
            selector: row => row.stc_product_id,
            sortable: false,
            center: true,
            cell: row => (
                locationcookie !== 'Root' ? (
                    <>
                        {row.stc_item_inventory_pd_qty > 0 && (
                            <Button
                                variant="info"
                                size="sm"
                                style={{ marginRight: 8 }}
                                onClick={() => handleOpenTransferModal(row)}
                            >
                                Transfer
                            </Button>
                        )}
                        {row.stc_item_inventory_pd_qty === 0 ? (
                            <Button
                                variant="warning"
                                size="sm"
                                onClick={() => handleOpenRequisitionModal(row)}
                            >
                                Add Requisition
                            </Button>
                        ) : (
                            <a
                                href="#"
                                className="btn btn-primary"
                                onClick={() => {
                                    setSelectedProductId(row.stc_product_id);
                                    setSelectedProductRate(row.rate_including_gst);
                                    setSelectedProductQuantity(row.stc_item_inventory_pd_qty);
                                    setModalShow(true);
                                }}
                            >
                                Add
                            </a>
                        )}
                    </>
                ) : null
            )
        }
    ];

    const [showRequisitionModal, setShowRequisitionModal] = useState(false);
    const [requisitionFields, setRequisitionFields] = useState({ name: '', unit: '', quantity: '', remarks: '' });
    const [requisitionLoading, setRequisitionLoading] = useState(false);
    const [modalError, setModalError] = useState('');

    const handleOpenRequisitionModal = (row) => {
        setRequisitionFields({
            name: row.stc_product_name,
            unit: row.stc_product_unit,
            quantity: '',
            remarks: '',
            product_id: row.stc_product_id
        });
        setShowRequisitionModal(false); // Ensure modal is closed first
        setTimeout(() => setShowRequisitionModal(true), 50); // Open after state reset
    };

    const userIdCookie = document.cookie.split('; ').find(row => row.startsWith('user_id='));
    const userId = userIdCookie.split('=')[1];
    const handleRequisitionFieldChange = (e) => {
        setRequisitionFields({ ...requisitionFields, [e.target.name]: e.target.value, userId: userId });
    };

    const handleAddRequisition = () => {
        setRequisitionLoading(true);
        setModalError('');
        if (parseFloat(requisitionFields.quantity) < 0) {
            setModalError('Quantity cannot be less than 0.');
            setRequisitionLoading(false);
            return;
        }
        // Ensure product_id is sent
        const reqData = { ...requisitionFields, product_id: requisitionFields.product_id };
        axios.post(`${API_BASE_URL}/index.php?action=addRequisition`, reqData)
            .then((response) => {
                if (response.data && response.data.success === false && response.data.error) {
                    setModalError(response.data.message || response.data.error);
                    return;
                }
                setShowRequisitionModal(false);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Requisition added successfully.',
                    confirmButtonText: 'OK'
                });
            })
            .catch(error => {
                setModalError('Error saving requisition');
                console.error(error);
            })
            .finally(() => setRequisitionLoading(false));
    };

    const statusLabelMap = {
        1: 'Pending',
        2: 'Approved',
        3: 'In Transit',
        4: 'Completed'
    };
    const statusColorMap = {
        1: '#ffc107', // yellow
        2: '#17a2b8', // blue
        3: '#fd7e14', // orange
        4: '#28a745', // green
        default: '#6c757d' // gray
    };

    const [showTransferModal, setShowTransferModal] = useState(false);
    const [transferProductId, setTransferProductId] = useState(null);
    const [transferBranch, setTransferBranch] = useState('');
    const [transferError, setTransferError] = useState('');
    const branchOptions = [
        { value: 'Dhatkidih', label: 'Dhatkidih' },
        { value: 'Kolkata', label: 'Kolkata' },
        { value: 'Sehrabazar', label: 'Sehrabazar' }
    ];

    const handleOpenTransferModal = (row) => {
        setTransferProductId(row.stc_product_id);
        setTransferBranch('');
        setTransferError('');
        setShowTransferModal(true);
    };

    const handleTransfer = () => {
        setTransferError('');
        if (!transferBranch) {
            setTransferError('Please select a branch.');
            return;
        }
        const existingbranch=getCookie("location_stc");
        axios.post(`${API_BASE_URL}/index.php?action=transferProduct`, {
            product_id: transferProductId,
            branch: transferBranch,
            existingbranch: existingbranch
        })
            .then((response) => {
                if (response.data && response.data.success === false && response.data.error) {
                    setTransferError(response.data.message || response.data.error);
                    return;
                }
                setShowTransferModal(false);
                fetchData(search, page, limit);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Product transferred successfully.',
                    confirmButtonText: 'OK'
                });
            })
            .catch(error => {
                setTransferError('Error transferring product');
                console.error(error);
            });
    };

    const [showRackModal, setShowRackModal] = useState(false);
    const [rackProductId, setRackProductId] = useState(null);
    const [rackAdhocId, setRackAdhocId] = useState(null);
    const [rackList, setRackList] = useState([]);
    const [selectedRack, setSelectedRack] = useState('');
    const [newRackName, setNewRackName] = useState('');
    const [rackError, setRackError] = useState('');
    const [rackLoading, setRackLoading] = useState(false);

    const handleOpenRackModal = (row) => {
        setRackProductId(row.stc_product_id);
        setRackAdhocId(row.adhoc_id || row.stc_purchase_product_adhoc_id || null);
        setSelectedRack(row.rack_id || '');
        setNewRackName('');
        setRackError('');
        setShowRackModal(true);
        setRackLoading(true);
        axios.get(`${API_BASE_URL}/index.php?action=getRacks`, {
            params: {
                locationcookie: locationcookie
            }
        })
            .then(res => {
                setRackList(res.data.racks || []);
                setRackLoading(false);
            })
            .catch(() => setRackLoading(false));
    };

    const handleSaveRack = () => {
        setRackError('');
        if (!selectedRack && !newRackName) {
            setRackError('Please select or enter a rack name.');
            return;
        }
        setRackLoading(true);
        if (newRackName) {
            // Create new rack first
            axios.post(`${API_BASE_URL}/index.php?action=createRack`, { locationcookie:locationcookie,rack_name: newRackName })
                .then(res => {
                    if (res.data && res.data.success) {
                        setSelectedRack(res.data.id);
                        handleUpdateRack(res.data.id);
                    } else {
                        setRackError(res.data.error || 'Failed to create rack.');
                        setRackLoading(false);
                    }
                })
                .catch(() => {
                    setRackError('Failed to create rack.');
                    setRackLoading(false);
                });
        } else {
            handleUpdateRack(selectedRack);
        }
    };

    const handleUpdateRack = (rackId) => {
        axios.post(`${API_BASE_URL}/index.php?action=updateProductRack`, {
            locationcookie:locationcookie,
            rack_id: rackId,
            product_id: rackProductId
        })
            .then(res => {
                if (res.data && res.data.success) {
                    setShowRackModal(false);
                    fetchData(search, page, limit);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Rack updated successfully.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    setRackError(res.data.error || 'Failed to update rack.');
                }
            })
            .catch(() => setRackError('Failed to update rack.'))
            .finally(() => setRackLoading(false));
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
                                        <h2 className="text-center">Inventory</h2>
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
                                                progressPending={loading}
                                                pagination
                                                paginationServer
                                                paginationTotalRows={totalRows}
                                                paginationPerPage={limit}
                                                paginationDefaultPage={currentPage} // This is crucial!
                                                paginationRowsPerPageOptions={[10, 20, 50, 100]}
                                                onChangePage={handlePageChange}
                                                onChangeRowsPerPage={handleLimitChange}
                                                highlightOnHover
                                                striped
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
            <CustomerModal
                show={modalShow}
                handleClose={() => setModalShow(false)}
                productId={selectedProductId}
                productRate={selectedProductRate}
                productQuantity={selectedProductQuantity}
            />
            <ProductModal
                show={isSecondModalOpen}  // Pass the boolean variable, not as a function
                handleClose={() => setSecondModalOpen(false)}
                productId={selectedProductId}
            />
            <Modal show={showRequisitionModal} onHide={() => setShowRequisitionModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>Add Requisition</Modal.Title>
                </Modal.Header>
                <Form onSubmit={e => { e.preventDefault(); if (!requisitionLoading) handleAddRequisition(); }}>
                    <Modal.Body>
                        {modalError && <div style={{ color: 'red', marginBottom: 10 }}>{modalError}</div>}
                        <Form.Group controlId="formProductName">
                            <Form.Label>Product Name</Form.Label>
                            <Form.Control type="text" value={requisitionFields.name} readOnly />
                        </Form.Group>
                        <Form.Group controlId="formUnit">
                            <Form.Label>Unit</Form.Label>
                            <Form.Control type="text" value={requisitionFields.unit} readOnly />
                        </Form.Group>
                        <Form.Group controlId="formQuantity">
                            <Form.Label>Quantity</Form.Label>
                            <Form.Control
                                type="number"
                                name="quantity"
                                value={requisitionFields.quantity}
                                onChange={handleRequisitionFieldChange}
                                placeholder="Enter quantity"
                                min={0}
                                step="0.01"
                                required
                            />
                        </Form.Group>
                        <Form.Group controlId="formRemarks">
                            <Form.Label>Remarks</Form.Label>
                            <Form.Control
                                as="textarea"
                                name="remarks"
                                value={requisitionFields.remarks}
                                onChange={handleRequisitionFieldChange}
                                placeholder="Enter remarks"
                            />
                        </Form.Group>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={() => setShowRequisitionModal(false)}>
                            Cancel
                        </Button>
                        <Button variant="primary" type="submit" disabled={requisitionLoading}>
                            {requisitionLoading ? 'Saving...' : 'Add Requisition'}
                        </Button>
                    </Modal.Footer>
                </Form>
            </Modal>
            <Modal show={showTransferModal} onHide={() => setShowTransferModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>Transfer Product</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {transferError && <div style={{ color: 'red', marginBottom: 10 }}>{transferError}</div>}
                    <Form.Group controlId="formBranch">
                        <Form.Label>Select Branch</Form.Label>
                        <Form.Control
                            as="select"
                            value={transferBranch}
                            onChange={e => setTransferBranch(e.target.value)}
                        >
                            <option value="">Select branch</option>
                            {branchOptions.map(opt => (
                                <option key={opt.value} value={opt.value}>{opt.label}</option>
                            ))}
                        </Form.Control>
                    </Form.Group>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShowTransferModal(false)}>
                        Cancel
                    </Button>
                    <Button variant="primary" onClick={handleTransfer}>
                        Submit
                    </Button>
                </Modal.Footer>
            </Modal>
            <Modal show={showRackModal} onHide={() => setShowRackModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>Edit Rack</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {rackError && <div style={{ color: 'red', marginBottom: 10 }}>{rackError}</div>}
                    {rackLoading ? (
                        <div>Loading racks...</div>
                    ) : (
                        <>
                            <Form.Group controlId="formRackSelect">
                                <Form.Label>Select Rack</Form.Label>
                                <Form.Control
                                    as="select"
                                    value={selectedRack}
                                    onChange={e => setSelectedRack(e.target.value)}
                                >
                                    <option value="">Select rack</option>
                                    {rackList.map(rack => (
                                        <option key={rack.id} value={rack.id}>{rack.name}</option>
                                    ))}
                                </Form.Control>
                            </Form.Group>
                            <div style={{ margin: '10px 0', textAlign: 'center' }}>or</div>
                            <Form.Group controlId="formNewRack">
                                <Form.Label>Add New Rack</Form.Label>
                                <Form.Control
                                    type="text"
                                    value={newRackName}
                                    onChange={e => setNewRackName(e.target.value)}
                                    placeholder="Enter new rack name"
                                />
                            </Form.Group>
                        </>
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShowRackModal(false)}>
                        Cancel
                    </Button>
                    <Button variant="primary" onClick={handleSaveRack} disabled={rackLoading}>
                        Save
                    </Button>
                </Modal.Footer>
            </Modal>
        </div>
    );
}
