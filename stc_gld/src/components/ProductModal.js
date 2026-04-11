import React, { useState, useEffect } from 'react';
import { Modal } from 'react-bootstrap';
import axios from 'axios';
import './CustomerModal.css';

function formatInr(value) {
    if (value == null || value === '' || Number.isNaN(Number(value))) return '—';
    return `₹${Number(value).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function formatStockQty(value) {
    if (value == null || value === '' || Number.isNaN(Number(value))) return '—';
    return Number(value).toLocaleString('en-IN', { maximumFractionDigits: 3 });
}

const ProductModal = ({ show, handleClose, productId }) => {
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
    const [costPrice, setCostPrice] = useState(null);
    const [mrp, setMrp] = useState(null);
    const [sellingPrice, setSellingPrice] = useState(null);
    const [stockQty, setStockQty] = useState(null);
    const [statusLabel, setStatusLabel] = useState('');

    const API_BASE_URL = process.env.NODE_ENV === 'production'
    ? 'https://stcassociate.com/stc_gld/vanaheim'
    : 'http://localhost/stc/stc_gld/vanaheim';
    // Fetch customer options when the modal is shown
    useEffect(() => {
        if (show) {
            axios.get(`${API_BASE_URL}/index.php?action=getProductDetails&productId=${productId}`)
                .then(response => {
                    const product = response.data?.product;
                    if (!product) return;
                    let displayName = product.productName;
                    if (product.subCategoryName !== 'OTHERS') {
                        displayName = product.subCategoryName + ' ' + product.productName;
                    }
                    setProductName(displayName);
                    setProductDescription(product.productDescription);
                    setProductCategory(product.categoryName);
                    setHsnCode(product.hsnCode);
                    setPercentage(product.salePercentage);
                    setSubCategory(product.subCategoryName);
                    setUnit(product.unit);
                    setBrand(product.brandName);
                    setGst(
                        product.gst != null && product.gst !== ''
                            ? `${product.gst}%`
                            : '—'
                    );
                    setImage(
                        product.productImage
                            ? 'https://stcassociate.com/stc_symbiote/stc_product_image/' + product.productImage
                            : ''
                    );
                    setCostPrice(product.costPrice ?? null);
                    setMrp(product.mrp ?? null);
                    setSellingPrice(product.sellingPrice ?? null);
                    setStockQty(product.stockQty ?? null);
                    setStatusLabel(product.statusLabel || '');
                })
                .catch(error => console.error('Error fetching customer options:', error));
        }
    }, [show, productId]);

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
        setImage('');
        setCostPrice(null);
        setMrp(null);
        setSellingPrice(null);
        setStockQty(null);
        setStatusLabel('');
    };

    useEffect(() => {
        // Reset form fields when modal is closed
        if (!show) resetForm();
    }, [show]);

    return (
        <Modal
            show={show}
            onHide={handleClose}
            backdrop="static"
            size="xl"
            fullscreen="md-down"
            dialogClassName="product-details-modal-dialog"
            contentClassName="product-details-modal-content"
            centered
        >
            <Modal.Header className="product-pdp-modal-header">
                <button
                    type="button"
                    className="product-pdp-close-btn"
                    onClick={handleClose}
                    aria-label="Close"
                >
                    <i className="fa fa-times" aria-hidden="true" />
                </button>
                <Modal.Title className="product-pdp-modal-title">Product details</Modal.Title>
            </Modal.Header>
            <Modal.Body className="product-details-modal-body">
                <div className="product-details-container product-details-modal-inner">
                    <div className="product-details-grid">
                        <div className="product-pdp-gallery image-container">
                            <a
                                href={image}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="product-pdp-gallery-link"
                            >
                                <img src={image} alt="" className="product-image" />
                                <span className="product-pdp-gallery-hint">View full size</span>
                            </a>
                        </div>
                        <div className="product-pdp-panel">
                            <header className="product-pdp-header">
                                <h2 className="product-pdp-title">{productName || '—'}</h2>
                                <div className="product-pdp-meta">
                                    <div className="product-pdp-sku">
                                        <span className="product-pdp-sku-label">SKU</span>
                                        <span className="product-pdp-sku-value">{productId ?? '—'}</span>
                                    </div>
                                    {brand ? (
                                        <div className="product-pdp-brand-badge" title="Brand / make">
                                            {brand}
                                        </div>
                                    ) : null}
                                </div>
                            </header>

                            <section className="product-pdp-section product-pdp-section--about" aria-labelledby="pdp-about-heading">
                                <h3 id="pdp-about-heading" className="product-pdp-section-title">
                                    About this item
                                </h3>
                                <div className="product-pdp-description">
                                    {productDescription?.trim() ? productDescription : 'No description available.'}
                                </div>
                            </section>

                            <section className="product-pdp-section" aria-labelledby="pdp-specs-heading">
                                <h3 id="pdp-specs-heading" className="product-pdp-section-title">
                                    Product details
                                </h3>
                                <dl className="product-pdp-specs">
                                    <div className="product-pdp-spec-row">
                                        <dt>Category</dt>
                                        <dd>{productCategory || '—'}</dd>
                                    </div>
                                    {String(subCategory || '').trim().toUpperCase() !== 'OTHERS' ? (
                                        <div className="product-pdp-spec-row">
                                            <dt>Sub-category</dt>
                                            <dd>{subCategory || '—'}</dd>
                                        </div>
                                    ) : null}
                                    <div className="product-pdp-spec-row">
                                        <dt>Unit of measure</dt>
                                        <dd>{unit || '—'}</dd>
                                    </div>
                                    <div className="product-pdp-spec-row">
                                        <dt>HSN code</dt>
                                        <dd><code className="product-pdp-code">{hsnCode != null && hsnCode !== '' ? String(hsnCode) : '—'}</code></dd>
                                    </div>
                                    <div className="product-pdp-spec-row">
                                        <dt>Sale discount</dt>
                                        <dd>
                                            {percentage != null && percentage !== ''
                                                ? `${percentage}%`
                                                : '—'}
                                        </dd>
                                    </div>
                                    <div className="product-pdp-spec-row product-pdp-commerce-row">
                                        <dt>
                                            Cost price
                                            <span className="product-pdp-th-desc">Purchase</span>
                                        </dt>
                                        <dd className="text-end text-nowrap">{formatInr(costPrice)}</dd>
                                    </div>
                                    <div className="product-pdp-spec-row product-pdp-commerce-row">
                                        <dt>
                                            MRP
                                            <span className="product-pdp-th-desc">Max. retail</span>
                                        </dt>
                                        <dd className="text-end text-nowrap">{formatInr(mrp)}</dd>
                                    </div>
                                    <div className="product-pdp-spec-row product-pdp-commerce-row">
                                        <dt>
                                            Selling price
                                            <span className="product-pdp-th-desc">Final list</span>
                                        </dt>
                                        <dd className="text-end text-nowrap fw-semibold">{formatInr(sellingPrice)}</dd>
                                    </div>
                                    <div className="product-pdp-spec-row product-pdp-commerce-row">
                                        <dt>
                                            Stock qty
                                            <span className="product-pdp-th-desc">Inventory</span>
                                        </dt>
                                        <dd className="text-end text-nowrap">{formatStockQty(stockQty)}</dd>
                                    </div>
                                    <div className="product-pdp-spec-row product-pdp-commerce-row">
                                        <dt>
                                            Brand
                                            <span className="product-pdp-th-desc">Make</span>
                                        </dt>
                                        <dd>{brand || '—'}</dd>
                                    </div>
                                </dl>
                            </section>
                        </div>
                    </div>
                </div>
            </Modal.Body>
        </Modal>
    );
};

export default ProductModal;