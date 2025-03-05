import React, { useState, useEffect } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';
import Select from 'react-select';
import axios from 'axios';
import Swal from 'sweetalert2'; // Import SweetAlert2
import './CustomerModal.css';

const CustomerModal = ({ show, handleClose, productId, productRate, productQuantity }) => {
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
    const [customerAddress, setCustomerAddress] = useState('');
    const [agentOptions, setAgentOptions] = useState([]);
    const [selecteAgent, setSelectedAgent] = useState(null);
    const [requisition, setRequisition] = useState();
    const [quantity, setQuantity] = useState(1);
    const [rate, setRate] = useState(productRate); // Start with the initial rate
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
                            label: gld_customer.gld_customer_cont_no + ' - ' + gld_customer.gld_customer_title
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
                            value: gld_agents.stc_own_agents_id,
                            label: gld_agents.stc_own_agents_name
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

        const sanitizedRate = typeof productRate === 'string' ? productRate.replace(/,/g, '') : productRate;
        // Parse quantity and rate as numbers
        const parsedQuantity = parseFloat(quantity);
        const parsedRate = parseFloat(sanitizedRate);

        // Validation: Check if entered quantity exceeds available inventory or is invalid
        if (isNaN(parsedQuantity) || parsedQuantity <= 0 || parsedQuantity > productQuantity) {
            if (isNaN(parsedQuantity) || parsedQuantity <= 0) {
                setQuantityError('Invalid Quantity. Quantity must be greater than 0.');
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
                setCustomerError(`Select customer.`);
                return;
            }
        }
        const agentId = selecteAgent ? selecteAgent.value : 0;

        setIsSubmitting(true); // Prevent multiple submissions

        const userIdCookie = document.cookie.split('; ').find(row => row.startsWith('user_id='));
        if (!userIdCookie) {
            setIsSubmitting(false);  // Reset the submit state to allow future submissions
            return;  // Stop the function execution if no user_id cookie is found
        }
        const userId = userIdCookie.split('=')[1];
        const customerData = {
            product_id: productId,
            requisition: requisition,
            quantity: parsedQuantity, // Use the parsed quantity
            rate: parsedRate, // Use the parsed rate
            id: customerId,
            name: customerName,
            contact: customerContact,
            address: customerAddress,
            userId: userId,
            agentId: agentId
        };

        axios.post(`${API_BASE_URL}/index.php?action=addCustomer`, customerData)
            .then(response => {
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

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Customer and product added successfully.',
                    confirmButtonText: 'OK'
                });
            })
            .catch(error => console.error('Error adding customer and product:', error))
            .finally(() => setIsSubmitting(false));
    };


    // Reset form fields
    const resetForm = () => {
        setSelectedCustomer(null);
        setCustomerName('');
        setCustomerContact('');
        setCustomerAddress('');
        setRequisition('');
        setQuantity(1);
        setRate(productRate); // Reset rate to the initial product rate
        setIsSubmitting(false); // Reset the submission state
        setQuantityError(''); // Clear quantity error
        setRateError(''); // Clear rate error
    };

    useEffect(() => {
        // Reset form fields when modal is closed
        if (!show) resetForm();
    }, [show]);

    return (
        <Modal show={show} onHide={handleClose}>
            <Modal.Header>
                <Modal.Title>Add Customer</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form>
                    <Form.Group controlId="formProductId">
                        <Form.Label>Product ID</Form.Label>
                        <Form.Control type="text" value={productId} readOnly />
                    </Form.Group>

                    <Form.Group controlId="formRequisition">
                        <Form.Label>Requisition Number</Form.Label>
                        <Form.Control
                            type="number"
                            value={requisition}
                            onChange={e => setRequisition(e.target.value)}
                            placeholder="Enter Requisition Number"
                        />
                    </Form.Group>

                    <Form.Group controlId="formQuantity">
                        <Form.Label>Quantity</Form.Label>
                        <Form.Control
                            type="number"
                            value={quantity}
                            onChange={e => setQuantity(e.target.value)}
                            min="1"
                            max={productQuantity} // Ensure the quantity doesn't exceed inventory
                            placeholder="Enter Quantity"
                        />
                        <Form.Text className="text-muted">
                            Available quantity: {productQuantity}
                        </Form.Text>
                        {quantityError && <div style={{ color: 'red' }}>{quantityError}</div>} {/* Display quantity error */}
                    </Form.Group>

                    <Form.Group controlId="formRate">
                        <Form.Label>Rate</Form.Label>
                        <Form.Control
                            type="number"
                            value={productRate}
                            onChange={e => setRate(e.target.value)}
                            min="1"
                            placeholder="Enter Rate"
                            readOnly
                        />
                        <Form.Text className="text-muted">
                            Available rate: {productRate}
                        </Form.Text>
                        {rateError && <div style={{ color: 'red' }}>{rateError}</div>} {/* Display rate error */}
                    </Form.Group>

                    <Form.Group controlId="formCustomerSelect">
                        <Form.Label>Customer</Form.Label>
                        <Select
                            options={customerOptions}
                            value={selectedCustomer}
                            onChange={setSelectedCustomer}
                            placeholder="Select or add new customer"
                        />
                        <Form.Text className="text-muted">
                            If customer is not listed, add new details below.
                        </Form.Text>
                        {customerError && <div style={{ color: 'red' }}>{customerError}</div>} {/* Display quantity error */}
                    </Form.Group>

                    {!selectedCustomer && (
                        <>
                            <Form.Group controlId="formCustomerName">
                                <Form.Label>Name</Form.Label>
                                <Form.Control
                                    type="text"
                                    placeholder="Enter customer name"
                                    value={customerName}
                                    onChange={e => setCustomerName(e.target.value)}
                                />
                            </Form.Group>

                            <Form.Group controlId="formCustomerContact">
                                <Form.Label>Contact</Form.Label>
                                <Form.Control
                                    type="text"
                                    placeholder="Enter customer contact"
                                    value={customerContact}
                                    onChange={e => setCustomerContact(e.target.value)}
                                />
                            </Form.Group>

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
                        </>
                    )}
                </Form>


                <Form.Group controlId="formAgent">
                    <Form.Label>Agent</Form.Label>
                    <Select
                        options={agentOptions}
                        value={selecteAgent}
                        onChange={setSelectedAgent}
                        placeholder="Select agent"
                    />
                    {agentError && <div style={{ color: 'red' }}>{agentError}</div>} {/* Display rate error */}
                </Form.Group>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={handleClose}>
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
