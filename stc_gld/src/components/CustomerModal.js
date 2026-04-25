import React, { useMemo, useState, useEffect } from 'react';
import { Modal, Button, Form, Row, Col, Badge } from 'react-bootstrap';
import Select from 'react-select';
import axios from 'axios';
import Swal from 'sweetalert2'; // Import SweetAlert2
import './CustomerModal.css';

const CustomerModal = ({ show, handleClose, productId, productName = '', productCategory = '', productRate, productQuantity }) => {
    productRate = productRate && productRate.includes(",")
        ? productRate.replace(/,/g, "")
        : productRate;

    const API_BASE_URL = process.env.NODE_ENV === 'production'
        ? 'https://stcassociate.com/stc_gld/vanaheim'
        : 'http://localhost/stc/stc_gld/vanaheim';
    const [customerOptions, setCustomerOptions] = useState([]);
    const [selectedCustomer, setSelectedCustomer] = useState(null);
    const [customerName, setCustomerName] = useState('');
    const [customerContact, setCustomerContact] = useState('');
    const [customerContactError, setCustomerContactError] = useState('');
    const [customerEmail, setCustomerEmail] = useState('');
    const [customerAddress, setCustomerAddress] = useState('');
    const [agentOptions, setAgentOptions] = useState([]);
    const [selecteAgent, setSelectedAgent] = useState(null);
    const [requisition, setRequisition] = useState('');
    const [quantity, setQuantity] = useState(1);
    const [discount, setDiscount] = useState(0);
    const [pmargin, setPmargin] = useState(0);    
    const [rate, setRate] = useState(productRate); // Start with the initial rate
    const [idu, setIdu] = useState('');
    const [odu, setOdu] = useState('');
    const [slno, setSlno] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [quantityError, setQuantityError] = useState('');
    const [rateError, setRateError] = useState('');
    const [customerError, setCustomerError] = useState('');
    const [agentError, setAgentError] = useState('');


    // Fetch customer options when the modal is shown
    useEffect(() => {
        if (show) {
            axios.get(`${API_BASE_URL}/index.php`, {
                params: { action: 'getCustomers' }
            })
                .then(response => {
                    if (Array.isArray(response.data)) {
                        const options = response.data.map(gld_customer => ({
                            value: gld_customer.gld_customer_id,
                            label: gld_customer.gld_customer_email + ' - ' + gld_customer.gld_customer_cont_no + ' - ' + gld_customer.gld_customer_title
                        }));
                        setCustomerOptions(options);
                    } else {
                        console.error('Unexpected response format:', response.data);
                    }
                })
                .catch(error => console.error('Error fetching customer options:', error));
        }
    }, [show]);

    // get agents
    useEffect(() => {
        if (show) {
            axios.get(`${API_BASE_URL}/index.php`, {
                params: { action: 'getAgents' }
            })
                .then(response => {
                    if (Array.isArray(response.data)) {
                        const options1 = response.data.map(gld_agents => ({
                            value: gld_agents.stc_trading_user_id,
                            label: gld_agents.stc_trading_user_name
                        }));
                        setAgentOptions(options1);
                    } else {
                        console.error('Unexpected response format:', response.data);
                    }
                })
                .catch(error => console.error('Error fetching customer options:', error));
        }
    }, [show]);

    // Handle adding customer and product
    const handleAddCustomer = () => {
        // Reset error messages
        setQuantityError('');
        setRateError('');
        setCustomerError('');
        setCustomerContactError('');

        const sanitizedRate = typeof productRate === 'string' ? productRate.replace(/,/g, '') : productRate;
        // Parse quantity and rate as numbers
        const parsedQuantity = parseFloat(quantity);
        const parsedRate = parseFloat(sanitizedRate);

        // Validation: Check if entered quantity is less than 0 or exceeds available inventory or is invalid
        if (isNaN(parsedQuantity) || parsedQuantity < 0 || parsedQuantity > productQuantity) {
            if (isNaN(parsedQuantity) || parsedQuantity < 0) {
                setQuantityError('Invalid Quantity. Quantity cannot be less than 0.');
            } else if (parsedQuantity > productQuantity) {
                setQuantityError(`Entered quantity (${parsedQuantity}) exceeds available inventory (${productQuantity}).`);
            }
            return; // Stop submission if validation fails
        }

        // Validation: Check if rate is valid
        if (isNaN(parsedRate) || parsedRate <= 0 || parsedRate < productRate) {
            if (isNaN(parsedRate) || parsedRate <= 0) {
                setRateError('Invalid Rate. Rate must be greater than 0.');
            } else if (parsedRate < productRate) {
                setRateError(`Entered rate (${parsedRate}) is less than the base product rate (${productRate}).`);
            }
            return; // Stop submission if validation fails
        }
        const customerId = selectedCustomer ? selectedCustomer.value : null;

        if (customerId == null) {
            if (customerName == "") {
                setCustomerError(`Select customer or add new with new complete details.`);
                return;
            }
            if (customerContact == "") {
                if (customerEmail == "") {
                    setCustomerError(`Select customer or add new with new complete details.`);
                    return;
                }
            }

            if (customerContact !== '' && customerContact.length !== 10) {
                setCustomerContactError('Contact number must be exactly 10 digits. Example: 9876542310');
                return;
            }
        } else {

        }
        const agentId = selecteAgent ? selecteAgent.value : 0;

        setIsSubmitting(true); // Prevent multiple submissions

        const userIdCookie = document.cookie.split('; ').find(row => row.startsWith('user_id='));
        if (!userIdCookie) {
            setIsSubmitting(false);  // Reset the submit state to allow future submissions
            return;  // Stop the function execution if no user_id cookie is found
        }
        const userId = userIdCookie.split('=')[1];
        const finalcost=parseFloat(parsedRate) + parseFloat(pmargin);
        const locationcookie = document.cookie.split('; ').find(row => row.startsWith('location_stc='));
        if (!locationcookie) {
            setIsSubmitting(false);  // Reset the submit state to allow future submissions
            return;  // Stop the function execution if no location_stc cookie is found
        }
        const location = locationcookie.split('=')[1];
        const isAirConditioner = /air\s*conditioner/i.test(String(productCategory || '')) || /air\s*conditioner/i.test(String(productName || ''));
        const pdetails = isAirConditioner
            ? [idu ? `IDU - ${idu}` : null, odu ? `ODU - ${odu}` : null].filter(Boolean).join(' & ')
            : (slno ? `Sl No - ${slno}` : '');

        const customerData = {
            product_id: productId,
            requisition: requisition || '',
            quantity: parsedQuantity, // Use the parsed quantity
            rate: finalcost, // Use the parsed rate
            discount: discount,
            pdetails: pdetails,
            id: customerId,
            name: customerName,
            contact: customerContact,
            email: customerEmail,
            address: customerAddress,
            userId: userId,
            agentId: agentId,
            location: locationcookie
        };

        axios.post(`${API_BASE_URL}/index.php?action=addCustomer`, customerData)
            .then(response => {
                // Check if the response contains an error
                if (response.data.error) {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.data.error,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload page after clicking OK on error
                        window.location.reload();
                    });
                } else if (response.data.success) {
                    // If a new customer is added, update the select options
                    if (!customerId) {
                        const newCustomerOption = {
                            value: response.data.newCustomerId,
                            label: customerName
                        };
                        setCustomerOptions([...customerOptions, newCustomerOption]);
                        setSelectedCustomer(newCustomerOption); // Set newly added customer
                    }

                    // Reset fields after successful submission
                    resetForm();
                    handleClose(); // Close the modal after showing the alert

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Customer and product added successfully.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload page after clicking OK on success
                        window.location.reload();
                    });
                } else {
                    // Handle unexpected response format
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Unexpected response from server.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error adding customer and product:', error);
                // Show error message for network/server errors
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to connect to server. Please try again.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            })
            .finally(() => setIsSubmitting(false));
    };


    // Reset form fields
    const resetForm = () => {
        setSelectedCustomer(null);
        setCustomerName('');
        setCustomerContact('');
        setCustomerContactError('');
        setCustomerEmail('');
        setCustomerAddress('');
        setRequisition('');
        setQuantity(1);
        setDiscount(0);
        setPmargin(0);
        setRate(productRate); // Reset rate to the initial product rate
        setIdu('');
        setOdu('');
        setSlno('');
        setIsSubmitting(false); // Reset the submission state
        setQuantityError(''); // Clear quantity error
        setRateError(''); // Clear rate error
    };

    useEffect(() => {
        // Reset form fields when modal is closed
        if (!show) resetForm();
    }, [show]);

    const selectStyles = useMemo(() => ({
        control: (base, state) => ({
            ...base,
            minHeight: 44,
            borderColor: state.isFocused ? '#752180' : base.borderColor,
            boxShadow: state.isFocused ? '0 0 0 0.2rem rgba(117, 33, 128, 0.15)' : base.boxShadow,
            '&:hover': { borderColor: '#752180' }
        }),
        menu: (base) => ({ ...base, zIndex: 1060 }),
        option: (base, state) => ({
            ...base,
            paddingTop: 10,
            paddingBottom: 10,
            backgroundColor: state.isSelected ? 'rgba(117, 33, 128, 0.12)' : state.isFocused ? 'rgba(117, 33, 128, 0.08)' : base.backgroundColor,
            color: '#111'
        }),
        placeholder: (base) => ({ ...base, color: '#6c757d' }),
        singleValue: (base) => ({ ...base, color: '#111' }),
    }), []);

    return (
        <Modal
            show={show}
            onHide={handleClose}
            dialogClassName="customer-modal-dialog"
            contentClassName="customer-modal-content"
        >
            <Modal.Header closeButton className="customer-modal-header">
                <div className="customer-modal-header-title">
                    <Modal.Title className="customer-modal-title">Add Order to Customer</Modal.Title>
                    <div className="customer-modal-subtitle">
                    </div>
                </div>
            </Modal.Header>
            <Modal.Body className="customer-modal-body">
                <Form className="customer-modal-form">
                    <div className="customer-modal-section">
                        <div className="customer-modal-section-title">Order details</div>
                        <Row className="g-3">

                            <Col xs={12} md={12}>
                                <div className="customer-modal-static-field">
                                    <div className="customer-modal-static-value" title={String(productName ?? '')}>
                                        Product ID: <strong>{productId}</strong><br />
                                        Product Name: <strong>{productName || '-'}</strong><br />
                                        Product Category: <strong>{productCategory || '-'}</strong>
                                    </div>
                                </div>
                            </Col>

                            {(() => {
                                const isAirConditioner = /air\s*conditioner/i.test(String(productCategory || '')) || /air\s*conditioner/i.test(String(productName || ''));
                                if (isAirConditioner) {
                                    return (
                                        <>
                                            <Col xs={12} md={6}>
                                                <Form.Group controlId="formIdu">
                                                    <Form.Label>IDU</Form.Label>
                                                    <Form.Control
                                                        type="text"
                                                        value={idu}
                                                        onChange={(e) => setIdu(e.target.value)}
                                                        placeholder="Enter IDU number"
                                                        maxLength={60}
                                                    />
                                                </Form.Group>
                                            </Col>

                                            <Col xs={12} md={6}>
                                                <Form.Group controlId="formOdu">
                                                    <Form.Label>ODU</Form.Label>
                                                    <Form.Control
                                                        type="text"
                                                        value={odu}
                                                        onChange={(e) => setOdu(e.target.value)}
                                                        placeholder="Enter ODU number"
                                                        maxLength={60}
                                                    />
                                                </Form.Group>
                                            </Col>
                                        </>
                                    );
                                }

                                return (
                                    <Col xs={12} md={12}>
                                        <Form.Group controlId="formSlno">
                                            <Form.Label>Sl No</Form.Label>
                                            <Form.Control
                                                type="text"
                                                value={slno}
                                                onChange={(e) => setSlno(e.target.value)}
                                                placeholder="Enter serial number"
                                                maxLength={60}
                                            />
                                        </Form.Group>
                                    </Col>
                                );
                            })()}

                            <Col xs={12} md={6}>
                                <Form.Group controlId="formQuantity">
                                    <Form.Label>Quantity</Form.Label>
                                    <Form.Control
                                        type="number"
                                        value={quantity}
                                        onChange={e => setQuantity(e.target.value)}
                                        min={0}
                                        max={productQuantity}
                                        placeholder="Enter quantity"
                                    />
                                    <div className="customer-modal-help-row">
                                        <Form.Text className="text-muted">Available: {productQuantity}</Form.Text>
                                    </div>
                                    {quantityError && <div className="customer-modal-error">{quantityError}</div>}
                                </Form.Group>
                            </Col>

                            <Col xs={12} md={6}>
                                <Form.Group controlId="formRate">
                                    <Form.Label>Rate</Form.Label>
                                    <Form.Control
                                        type="number"
                                        value={productRate}
                                        onChange={e => setRate(e.target.value)}
                                        min="1"
                                        placeholder="Rate"
                                        readOnly
                                    />
                                    <div className="customer-modal-help-row">
                                        <Form.Text className="text-muted">Base: {productRate}</Form.Text>
                                    </div>
                                    {rateError && <div className="customer-modal-error">{rateError}</div>}
                                </Form.Group>
                            </Col>

                            <Col xs={12} md={6}>
                                <Form.Group controlId="formPmargin">
                                    <Form.Label>Plus Margin</Form.Label>
                                    <Form.Control
                                        type="number"
                                        value={pmargin}
                                        onChange={e => setPmargin(e.target.value)}
                                        min="0"
                                        placeholder="Enter margin"
                                    />
                                </Form.Group>
                            </Col>

                            <Col xs={12} md={6}>
                                <Form.Group controlId="formDiscount">
                                    <Form.Label>Discount</Form.Label>
                                    <Form.Control
                                        type="number"
                                        value={discount}
                                        onChange={e => setDiscount(e.target.value)}
                                        min="0"
                                        placeholder="Enter discount"
                                    />
                                </Form.Group>
                            </Col>
                        </Row>
                    </div>

                    <div className="customer-modal-section">
                        <div className="customer-modal-section-title">Customer</div>
                        <Row className="g-3">
                            <Col xs={12} md={6}>
                                <Form.Group controlId="formCustomerSelect">
                                    <Form.Label>Select Customer</Form.Label>
                                    <Select
                                        options={customerOptions}
                                        value={selectedCustomer}
                                        onChange={setSelectedCustomer}
                                        placeholder="Search by email / phone / name"
                                        maxMenuHeight={6 * 38}
                                        styles={selectStyles}
                                    />
                                    <Form.Text className="text-muted">
                                        Not listed? Fill in the details to create a new customer.
                                    </Form.Text>
                                    {customerError && <div className="customer-modal-error">{customerError}</div>}
                                </Form.Group>
                            </Col>

                            <Col xs={12} md={6}>
                                <Form.Group controlId="formAgent">
                                    <Form.Label>Agent</Form.Label>
                                    <Select
                                        options={agentOptions}
                                        value={selecteAgent}
                                        onChange={setSelectedAgent}
                                        placeholder="Select agent"
                                        maxMenuHeight={6 * 38}
                                        styles={selectStyles}
                                    />
                                    {agentError && <div className="customer-modal-error">{agentError}</div>}
                                </Form.Group>
                            </Col>

                            {!selectedCustomer && (
                                <>
                                    <Col xs={12} md={6}>
                                        <Form.Group controlId="formCustomerName">
                                            <Form.Label>Name</Form.Label>
                                            <Form.Control
                                                type="text"
                                                placeholder="Enter customer name"
                                                value={customerName}
                                                onChange={e => setCustomerName(e.target.value)}
                                            />
                                        </Form.Group>
                                    </Col>

                                    <Col xs={12} md={6}>
                                        <Form.Group controlId="formCustomerContact">
                                            <Form.Label>Contact</Form.Label>
                                            <Form.Control
                                                type="text"
                                                inputMode="numeric"
                                                pattern="\d*"
                                                maxLength={10}
                                                placeholder="10-digit contact number (e.g. 9876542310)"
                                                value={customerContact}
                                                onChange={(e) => {
                                                    const digitsOnly = String(e.target.value).replace(/\D/g, '').slice(0, 10);
                                                    setCustomerContact(digitsOnly);

                                                    if (digitsOnly.length === 0 || digitsOnly.length === 10) {
                                                        setCustomerContactError('');
                                                    } else {
                                                        setCustomerContactError('Contact number must be exactly 10 digits. Example: 9876542310');
                                                    }
                                                }}
                                            />
                                            {customerContactError && <div className="customer-modal-error">{customerContactError}</div>}
                                        </Form.Group>
                                    </Col>

                                    <Col xs={12} md={6}>
                                        <Form.Group controlId="formCustomerEmail">
                                            <Form.Label>Email</Form.Label>
                                            <Form.Control
                                                type="email"
                                                placeholder="Enter customer email"
                                                value={customerEmail}
                                                onChange={e => setCustomerEmail(e.target.value)}
                                            />
                                        </Form.Group>
                                    </Col>

                                    <Col xs={12} md={12}>
                                        <Form.Group controlId="formCustomerAddress">
                                            <Form.Label>Address</Form.Label>
                                            <Form.Control
                                                as="textarea"
                                                rows={3}
                                                placeholder="Enter customer address"
                                                value={customerAddress}
                                                onChange={e => setCustomerAddress(e.target.value)}
                                            />
                                        </Form.Group>
                                    </Col>
                                </>
                            )}
                        </Row>
                    </div>
                </Form>
            </Modal.Body>
            <Modal.Footer className="customer-modal-footer">
                <Button variant="outline-secondary" onClick={handleClose}>
                    Close
                </Button>
                <Button variant="primary" onClick={handleAddCustomer} disabled={isSubmitting}>
                    {isSubmitting ? 'Saving...' : 'Add Customer and Product'}
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

export default CustomerModal;
