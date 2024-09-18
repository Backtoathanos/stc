import React, { useState, useEffect } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';
import Select from 'react-select';
import axios from 'axios';
import './CustomerModal.css';

const CustomerModal = ({ show, handleClose, productId }) => {
    const [customerOptions, setCustomerOptions] = useState([]);
    const [selectedCustomer, setSelectedCustomer] = useState(null);
    const [customerName, setCustomerName] = useState('');
    const [customerContact, setCustomerContact] = useState('');
    const [customerAddress, setCustomerAddress] = useState('');
    const [quantity, setQuantity] = useState(1); // New state for product quantity
    const [rate, setRate] = useState(1); // New state for product quantity

    useEffect(() => {
    axios.get('http://localhost/stc/stc_gld/vanaheim/index.php', {
        params: {
            action: 'getCustomers' // Specify the action parameter for fetching customers
        }
    })
    .then(response => {
        if (Array.isArray(response.data)) {
            const options = response.data.map(stc_trading_customer => ({
                value: stc_trading_customer.stc_trading_customer_id,
                label: stc_trading_customer.stc_trading_customer_title
            }));
            setCustomerOptions(options);
        } else {
            console.error('Unexpected response format:', response.data);
        }
    })
    .catch(error => console.error('Error fetching customer options:', error));
}, []);

    const handleAddCustomer = () => {
        // Check if a customer is selected or if a new customer needs to be added
        const customerId = selectedCustomer ? selectedCustomer.value : null;
        const customerData = {
            product_id: productId, // Include product ID
            quantity: quantity,    // Include quantity
            rate: rate,    // Include rate
            id: customerId,
            name: customerName,
            contact: customerContact,
            address: customerAddress
        };

        // API call to add customer and link with product
        axios.post('http://localhost/stc/stc_gld/vanaheim/index.php', customerData)
            .then(response => {
                console.log('Customer and product added successfully:', response.data);
                handleClose();  // Close the modal
            })
            .catch(error => console.error('Error adding customer and product:', error));
    };

    return (
        <Modal show={show} onHide={handleClose}>
            <Modal.Header>
                <Modal.Title>Add Customer</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form>
                    <Form.Group controlId="formProductId">
                        <Form.Label>Product ID</Form.Label>
                        <Form.Control
                            type="text"
                            value={productId}
                            readOnly
                        />
                    </Form.Group>

                    <Form.Group controlId="formQuantity">
                        <Form.Label>Quantity</Form.Label>
                        <Form.Control
                            type="number"
                            value={quantity}
                            placeholder="Enter Quantity"
                            onChange={e => setQuantity(e.target.value)}
                            min="1"
                        />
                    </Form.Group>

                    <Form.Group controlId="formRate">
                        <Form.Label>Rate</Form.Label>
                        <Form.Control
                            type="number"
                            value={rate}
                            placeholder="Enter Rate"
                            onChange={e => setRate(e.target.value)}
                            min="1"
                        />
                    </Form.Group>

                    <Form.Group controlId="formCustomerSelect">
                        <Form.Label>Customer</Form.Label>
                        <Select
                            options={customerOptions}
                            onChange={setSelectedCustomer}
                            value={selectedCustomer}
                            placeholder="Select or add new customer"
                        />
                        <Form.Text className="text-muted">
                            If customer is not listed, add new details below.
                        </Form.Text>
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
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={handleClose}>
                    Close
                </Button>
                <Button variant="primary" onClick={handleAddCustomer}>
                    Add Customer and Product
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

export default CustomerModal;
