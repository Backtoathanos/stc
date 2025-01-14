import React, { useState, useEffect } from 'react';
import { Modal } from 'react-bootstrap';
import axios from 'axios';
import './CustomerModal.css';

const ProductModal = ({ show, handleClose, productId }) => {
    const API_BASE_URL = process.env.NODE_ENV === 'production'
    ? 'https://stcassociate.com/stc_gld/vanaheim'
    : 'http://localhost/stc/stc_gld/vanaheim';
    console.log(API_BASE_URL);
    console.log(`${API_BASE_URL}/index.php?action=getProductDetails&productId=${productId}`);
    const [productName, setProductName] = useState('');
    const [productDescription, setProductDescription] = useState('');
    const [productCategory, setProductCategory] = useState('');
    const [hsnCode, setHsnCode] = useState('');
    const [percentage, setPercentage] = useState('');
    const [subCategory, setSubCategory] = useState('');
    const [unit, setUnit] = useState('');
    const [gst, setGst] = useState('');
    const [brand, setBrand] = useState('');
    const [image, setImage] = useState('');

    // Fetch customer options when the modal is shown
    useEffect(() => {
        if (show) {
            axios.get(`${API_BASE_URL}/index.php?action=getProductDetails&productId=${productId}`)
                .then(response => {
                    let product = response.data.product;
                    let productName=product.productName;
                    if(product.subCategoryName!=='OTHERS'){
                        productName=product.subCategoryName + ' ' + product.productName;
                    }
                    setProductName(productName);
                    setProductDescription(product.productDescription);
                    setProductCategory(product.categoryName);
                    setHsnCode(product.hsnCode);
                    setPercentage(product.salePercentage);
                    setSubCategory(product.subCategoryName);
                    setUnit(product.unit);
                    setBrand(product.brandName);
                    setGst(product.gst+"%");
                    setImage("https://stcassociate.com/stc_symbiote/stc_product_image/" + product.productImage);
                })
                .catch(error => console.error('Error fetching customer options:', error));
        }
    }, [show]);

    // Reset form fields
    const resetForm = () => {
        setProductName('');
        setProductDescription('');
        setProductCategory('');
        setHsnCode('');
        setPercentage('');
        setSubCategory('');
        setUnit('');
        setGst('');
        setBrand('');
    };

    useEffect(() => {
        // Reset form fields when modal is closed
        if (!show) resetForm();
    }, [show]);

    return (
        <Modal show={show} onHide={handleClose} size="xl">
            <Modal.Header>
                <Modal.Title>Show Product Details</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <div className="product-details-container">
                    <div className="product-details-grid">
                        <div className="detail-item image-container">
                            <a href={image} target="_blank">
                                <img src={image} alt="Product" className="product-image" />
                            </a>
                        </div>
                        <div className="details-container">
                            <div className="detail-item">
                                <span className="detail-label">Product ID (SKU):</span>
                                <span className="detail-value">{productId}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Product Name:</span>
                                <span className="detail-value">{productName}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Description:</span>
                                <span className="detail-value">{productDescription}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Category:</span>
                                <span className="detail-value">{productCategory}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">HSN:</span>
                                <span className="detail-value">{hsnCode}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Percentage:</span>
                                <span className="detail-value">{percentage}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Sub Category:</span>
                                <span className="detail-value">{subCategory}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Unit:</span>
                                <span className="detail-value">{unit}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">GST:</span>
                                <span className="detail-value">{gst}</span>
                            </div>
                            <div className="detail-item">
                                <span className="detail-label">Make:</span>
                                <span className="detail-value">{brand}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </Modal.Body>
        </Modal>
    );
};

export default ProductModal;