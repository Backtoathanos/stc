import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import Footer from "./layouts/Footer";
import Navbar from "./layouts/Navbar";
import Sidebar from './layouts/Sidebar';
import { useLocation } from 'react-router-dom';
import { RotatingLines } from 'react-loader-spinner';
import './Datatable.css';
import axios from 'axios';
import { debounce } from 'lodash';
import { Modal, Button, Form } from 'react-bootstrap';
import Swal from 'sweetalert2';
import { FaEdit, FaTrash, FaCheckCircle } from 'react-icons/fa';

export default function Requisitions() {
    const location = useLocation();
    useEffect(() => {
        document.title = "STC GLD || Requisitions";
    }, []);
    const API_BASE_URL = process.env.NODE_ENV === 'production'
        ? 'https://stcassociate.com/stc_gld/vanaheim'
        : 'http://localhost/stc/stc_gld/vanaheim';
    const [showModal, setShowModal] = useState(false);
    const [data, setData] = useState([]);
    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(true);
    const [modalFields, setModalFields] = useState({ name: '', quantity: '', unit: '', remarks: '' });
    const [editId, setEditId] = useState(null);
    const [page, setPage] = useState(1);
    const [limit, setLimit] = useState(10);
    const [totalRows, setTotalRows] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);
    const [modalLoading, setModalLoading] = useState(false);
    const [modalError, setModalError] = useState('');
    const currentRoute = location.pathname === "/requisitions" ? "requisitions" : "dashboard";

    const fetchData = debounce((query = '', pageNum = page, rowLimit = limit) => {
        setLoading(true);
        axios.get(`${API_BASE_URL}/index.php?action=getRequisitions`, {
            params: { search: query, page: pageNum, limit: rowLimit }
        })
            .then(response => {
                if (response.data && response.data.records) {
                    setData(response.data.records);
                    setTotalRows(response.data.total);
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
    }, 500);

    useEffect(() => {
        fetchData(search, page, limit);
    }, [search, page, limit]);

    const handlePageChange = (newPage) => {
        setPage(newPage);
    };
    const handleLimitChange = (newLimit, newPage) => {
        setLimit(newLimit);
        setPage(newPage || 1);
    };

    const handleModalFieldChange = (e) => {
        setModalFields({ ...modalFields, [e.target.name]: e.target.value });
    };

    const handleAddOrEdit = () => {
        setModalLoading(true);
        setModalError('');
        if (parseFloat(modalFields.quantity) < 0) {
            setModalError('Quantity cannot be less than 0.');
            setModalLoading(false);
            return;
        }
        const action = editId ? 'editRequisition' : 'addRequisition';
        axios.post(`${API_BASE_URL}/index.php?action=${action}`, {
            ...modalFields,
            id: editId
        })
            .then((response) => {
                if (response.data && response.data.success === false && response.data.error) {
                    setModalError(response.data.message || response.data.error);
                    setModalLoading(false);
                    return;
                }
                setShowModal(false);
                setEditId(null);
                fetchData(search, page, limit);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: editId ? 'Requisition updated successfully.' : 'Requisition added successfully.',
                    confirmButtonText: 'OK'
                });
            })
            .catch(error => {
                setModalError('Error saving requisition');
                console.error(error);
            })
            .finally(() => setModalLoading(false));
    };

    const handleEdit = (row) => {
        setModalFields({
            name: row.name,
            quantity: row.quantity,
            unit: row.unit,
            remarks: row.remarks
        });
        setEditId(row.id);
        setShowModal(false); // Ensure modal is closed first
        setTimeout(() => setShowModal(true), 50); // Open after state reset
    };

    const handleAddNew = () => {
        setModalFields({ name: '', quantity: '', unit: '', remarks: '' });
        setEditId(null);
        setShowModal(false);
        setTimeout(() => setShowModal(true), 50);
    };

    const handleDelete = (id) => {
        if (window.confirm('Are you sure you want to delete this requisition?')) {
            axios.post(`${API_BASE_URL}/index.php?action=deleteRequisition`, { id })
                .then(() => {
                    fetchData(search, page, limit);
                })
                .catch(error => {
                    alert('Error deleting requisition');
                    console.error(error);
                });
        }
    };

    const handleReceive = (row) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to mark this requisition as received?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, receive it',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(`${API_BASE_URL}/index.php?action=updateRequisitionStatus`, { id: row.id, status: 4 })
                    .then(() => {
                        fetchData(search, page, limit);
                        Swal.fire({
                            icon: 'success',
                            title: 'Received!',
                            text: 'Requisition marked as received.',
                            confirmButtonText: 'OK'
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status.',
                            confirmButtonText: 'OK'
                        });
                        console.error(error);
                    });
            }
        });
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

    const columns = [
        { name: 'Name', selector: row => row.name, sortable: true },
        { name: 'Quantity', selector: row => row.quantity, sortable: true, center: true },
        { name: 'Unit', selector: row => row.unit, sortable: true, center: true },
        { name: 'Remarks', selector: row => row.remarks, sortable: true, center: true },
        {
            name: 'Status',
            selector: row => row.status,
            sortable: true,
            center: true,
            cell: row => {
                const label = statusLabelMap[row.status] || 'Unknown';
                const color = statusColorMap[row.status] || statusColorMap.default;
                return (
                    <span style={{
                        background: color,
                        color: '#fff',
                        padding: '4px 12px',
                        borderRadius: '12px',
                        fontWeight: 'bold',
                        display: 'inline-block',
                        minWidth: '90px',
                        textAlign: 'center'
                    }}>{label}</span>
                );
            }
        },
        {
            name: 'Action',
            cell: row => {
                const status = parseInt(row.status);
                if (status > 1 && status !== 3) return null;
                if (status === 3) {
                    return (
                        <Button
                            variant="link"
                            size="sm"
                            onClick={() => handleReceive(row)}
                            title="Mark as Received"
                            style={{ color: '#28a745', padding: '0 8px', fontSize: '25px' }}
                        >
                            <FaCheckCircle />
                        </Button>
                    );
                }
                return (
                    <>
                        <Button
                            variant="link"
                            size="sm"
                            onClick={() => handleEdit(row)}
                            title="Edit"
                            style={{ color: '#ffc107', padding: '0 8px', fontSize: '25px' }}
                        >
                            <FaEdit />
                        </Button>
                        <Button
                            variant="link"
                            size="sm"
                            onClick={() => handleDelete(row.id)}
                            title="Delete"
                            style={{ color: '#dc3545', padding: '0 8px', fontSize: '25px' }}
                        >
                            <FaTrash />
                        </Button>
                    </>
                );
            },
            ignoreRowClick: true,
            allowOverflow: true,
            center: true
        }
    ];

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
                                        <h2 className="text-center">Requisitions</h2>
                                    </div>
                                    <div className="card-body">
                                        <div className="form-group">
                                            <Button variant="primary" onClick={handleAddNew}>
                                                Add Requisition
                                            </Button>
                                            <input
                                                type="text"
                                                className="form-control"
                                                placeholder="Search by name, unit, remarks..."
                                                value={search}
                                                onChange={(e) => setSearch(e.target.value)}
                                                style={{ marginTop: 10 }}
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
                                                paginationDefaultPage={currentPage}
                                                paginationRowsPerPageOptions={[10, 20, 50, 100]}
                                                onChangePage={handlePageChange}
                                                onChangeRowsPerPage={handleLimitChange}
                                                highlightOnHover
                                                striped
                                            />
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Footer />
            </div>
            <Modal show={showModal} onHide={() => setShowModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>{editId ? 'Edit' : 'Add'} Requisition</Modal.Title>
                </Modal.Header>
                <Form onSubmit={e => { e.preventDefault(); if (!modalLoading) handleAddOrEdit(); }}>
                    <Modal.Body>
                        {modalError && <div style={{ color: 'red', marginBottom: 10 }}>{modalError}</div>}
                        <Form.Group controlId="formName">
                            <Form.Label>Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="name"
                                value={modalFields.name}
                                onChange={handleModalFieldChange}
                                placeholder="Enter requisition name"
                            />
                        </Form.Group>
                        <Form.Group controlId="formQuantity">
                            <Form.Label>Quantity</Form.Label>
                            <Form.Control
                                type="number"
                                name="quantity"
                                value={modalFields.quantity}
                                onChange={handleModalFieldChange}
                                placeholder="Enter quantity"
                                min={0}
                            />
                        </Form.Group>
                        <Form.Group controlId="formUnit">
                            <Form.Label>Unit</Form.Label>
                            <Form.Control
                                type="text"
                                name="unit"
                                value={modalFields.unit}
                                onChange={handleModalFieldChange}
                                placeholder="Enter unit"
                            />
                        </Form.Group>
                        <Form.Group controlId="formRemarks">
                            <Form.Label>Remarks</Form.Label>
                            <Form.Control
                                as="textarea"
                                name="remarks"
                                value={modalFields.remarks}
                                onChange={handleModalFieldChange}
                                placeholder="Enter remarks"
                            />
                        </Form.Group>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={() => setShowModal(false)}>
                            Cancel
                        </Button>
                        <Button variant="primary" type="submit" disabled={modalLoading}>
                            {modalLoading ? 'Saving...' : (editId ? 'Update' : 'Add')}
                        </Button>
                    </Modal.Footer>
                </Form>
            </Modal>
        </div>
    );
}
