import React, { useState, useEffect } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';
import Select from 'react-select';
import axios from 'axios';
import Swal from 'sweetalert2'; // Import SweetAlert2
import './CustomerModal.css';

const CustomerModal = ({ show, handleClose, productId, productRate, productQuantity }) => {
    const [customerOptions, setCustomerOptions] = useState([]);
    const [selectedCustomer, setSelectedCustomer] = useState(null);
    const [customerName, setCustomerName] = useState('');
    const [customerContact, setCustomerContact] = useState('');
    const [customerAddress, setCustomerAddress] = useState('');
    const [quantity, setQuantity] = useState(1);
    const [rate, setRate] = useState(productRate); // Start with the initial rate
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Fetch customer options when the modal is shown
    useEffect(() => {
        if (show) {
            axios.get('https://stcassociate.com/stc_gld/vanaheim/index.php', {
                params: { action: 'getCustomers' }
            })
            .then(response => {
                if (Array.isArray(response.data)) {
                    const options = response.data.map(gld_customer => ({
                        value: gld_customer.gld_customer_id,
                        label: gld_customer.gld_customer_cont_no
                    }));
                    setCustomerOptions(options);
                } else {
                    console.error('Unexpected response format:', response.data);
                }
            })
            .catch(error => console.error('Error fetching customer options:', error));
        }
    }, [show]);

    // Handle adding customer and product
    const handleAddCustomer = () => {
        if (isSubmitting) return;

        // Validation: Check if entered quantity exceeds available inventory
        if (quantity > productQuantity) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Entered quantity (${quantity}) exceeds available inventory (${productQuantity}).`,
                confirmButtonText: 'OK'
            });
            return; // Stop submission if validation fails
        }

        setIsSubmitting(true); // Prevent multiple submissions

        const userIdCookie = document.cookie.split('; ').find(row => row.startsWith('user_id='));
        if (!userIdCookie) {
            setIsSubmitting(false);  // Reset the submit state to allow future submissions
            return;  // Stop the function execution if no user_id cookie is found
        }

        const userId = userIdCookie.split('=')[1];
        const customerId = selectedCustomer ? selectedCustomer.value : null;
        const customerData = {
            product_id: productId,
            quantity, // Use the current quantity value
            rate, // Use the current rate value
            id: customerId,
            name: customerName,
            contact: customerContact,
            address: customerAddress,
            userId: userId
        };

        axios.post('https://stcassociate.com/stc_gld/vanaheim/index.php?action=addCustomer', customerData)
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

                // Show SweetAlert2 success message
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
        setQuantity(1);
        setRate(productRate); // Reset rate to the initial product rate
        setIsSubmitting(false); // Reset the submission state
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
                    </Form.Group>

                    <Form.Group controlId="formRate">
                        <Form.Label>Rate</Form.Label>
                        <Form.Control
                            type="number"
                            value={rate}
                            onChange={e => setRate(e.target.value)}
                            min="1"
                            placeholder="Enter Rate"
                        />
                        <Form.Text className="text-muted">
                            Available rate: {productRate}
                        </Form.Text>
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
                <Button variant="primary" onClick={handleAddCustomer} disabled={isSubmitting}>
                    {isSubmitting ? 'Saving...' : 'Add Customer and Product'}
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

export default CustomerModal;
