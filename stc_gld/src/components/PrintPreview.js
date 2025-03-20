import React, { useEffect, useState } from 'react';
import { useLocation } from 'react-router-dom';
import axios from 'axios';
import './PrintPreview.css'; // Include external CSS file for print styles

const PrintPreview = () => {
    const [challanDetails, setChallanDetails] = useState(null);
    const location = useLocation();

    const API_BASE_URL = process.env.NODE_ENV === 'production'
        ? 'https://stcassociate.com/stc_gld/vanaheim'
        : 'http://localhost/stc/stc_gld/vanaheim';
    
    // Function to extract query parameters
    const getQueryParams = (query) => {
        return new URLSearchParams(query);
    };
    const queryParams = getQueryParams(location.search);
    useEffect(() => {
        let titlename = 'Invoice'; // Default title

        if (queryParams.get('status') === 'billed') {
            titlename = 'Challan';
        }
        document.title = "STC GLD || " + titlename + " Print Preview"; // Set the title
    }, []);

    // Fetch challan details when the page loads
    useEffect(() => {
        let challanNo = queryParams.get('challan_no') || 'default';
        let status = queryParams.get('status') || 'default';
        if (challanNo) {
            let geturl=`${API_BASE_URL}/index.php?action=getChallanDetails&challan_no=${challanNo}&status=challan`;
            if(status=="billed"){
                geturl=`${API_BASE_URL}/index.php?action=getChallanDetails&challan_no=${challanNo}&status=billed`;
            }
            // Fetch details of the selected challan
            axios.get(geturl)
                .then(response => {
                    setChallanDetails(response.data);
                })
                .catch(error => {
                    console.error('Error fetching challan details:', error);
                });
        }
    }, [location.search]);

    if (!challanDetails) {
        return <div>Loading {queryParams.get('status') === 'billed' ? 'invoice' : 'challan'} details...</div>;
    }
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = String(date.getFullYear()).slice(-4); // Get last two digits of the year
        return `${day}/${month}/${year}`;
    };
    
    return (
        <div className="print-preview-container">
            <header className="print-header" style={{ width: '100%', marginTop: '0px', padding: '0' }}>
                {/* Centered Logo and Title */}
                <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', width: '100%', margin: '0', padding: '0' }}>
                    <img
                        src="https://www.globalacsystem.com/ERP/img/Global%20Logo-Home.png"
                        alt="Global Logo"
                        style={{ height: '100px', marginRight: '15px' }}
                    />
                    <h2 style={{ margin: '0', padding: '0' }}>{queryParams.get('status') === 'billed' ? 'Retail Invoice' : 'Challan'}</h2>
                </div>

                {/* Left and Right Aligned Sections */}
                <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '10px' }}>
                    {/* Left 50% for Challan No */}
                    <div style={{ width: '50%', textAlign: 'left' }}>
                        <p>{queryParams.get('status') === 'billed' ? 'Invoice' : 'Challan'} No: {challanDetails?.challan_number}</p>
                        <p>{queryParams.get('status') === 'billed' ? 'Invoice' : 'Challan'} Date: {challanDetails?.challan_date ? formatDate(challanDetails.challan_date) : ''}</p>
                    </div>

                    {/* Right 50% for Customer Info */}
                    <div style={{ width: '50%' }}>
                        <p style={{ textAlign: 'right' }}>Customer: {challanDetails?.customer_name}</p>
                        <p style={{ textAlign: 'right' }}>Phone: {challanDetails?.customer_phone}</p>
                    </div>
                </div>
            </header>


            <main className="print-content">
                <table className="challan-table">
                    <thead>
                        <tr>
                            <th className="text-center">Sl.No</th>
                            <th className="text-center">Item</th>
                            <th className="text-center">Rack</th>
                            <th className="text-center">Unit</th>
                            <th className="text-center">Quantity</th>
                            <th className="text-center">Rate</th>
                            <th className="text-center">Amount</th>
                            <th className="text-center">Dues</th>
                            <th className="text-center">Payment Mode</th>
                            <th className="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        {challanDetails.products && challanDetails.products.length > 0 ? (
                            <>
                                {challanDetails.products.map((product, index) => (
                                    <tr key={index}>
                                        <td className="text-center">{index + 1}</td>
                                        <td className="text-left">{product.product_name}</td>
                                        <td className="text-left">{product.Rackid}</td>
                                        <td className="text-center">{product.unit}</td>
                                        <td className="text-right">{parseFloat(product.qty).toFixed(2)}</td>
                                        <td className="text-right">{parseFloat(product.rate).toFixed(2)}</td>
                                        <td className="text-right">{parseFloat(product.qty * product.rate).toFixed(2)}</td>
                                        <td className="text-right">{parseFloat(product.dues).toFixed(2)}</td>
                                        <td className="text-center">{product.payment_status == 1 ? "Credit" : product.payment_status == 2 ? "AC" : "Cash"}</td>
                                        <td className="text-center"></td>
                                    </tr>
                                ))}

                                {/* Calculate totals */}
                                <tr>
                                    <td className="text-right" colSpan="4"><strong>Total</strong></td>
                                    <td className="text-right">
                                        <strong>{challanDetails.products.reduce((sum, product) => sum + parseFloat(product.qty), 0).toFixed(2)}</strong>
                                    </td>
                                    <td className="text-right"></td>
                                    <td className="text-right">
                                        <strong>{challanDetails.products.reduce((sum, product) => sum + parseFloat(product.qty * product.rate), 0).toFixed(2)}</strong>
                                    </td>
                                    <td className="text-right">
                                        <strong>{challanDetails.products.reduce((sum, product) => sum + parseFloat(product.dues), 0).toFixed(2)}</strong>
                                    </td>
                                    <td className="text-center"><strong></strong></td>
                                    <td className="text-center"><strong></strong></td>
                                </tr>
                            </>
                        ) : (
                            <tr>
                                <td colSpan="4">No products available for this challan</td>
                            </tr>
                        )}
                    </tbody>
                </table>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <p>Delivery Address : <span>{challanDetails?.customer_address}</span></p>
                    <p>Signature</p>
                </div>

            </main>
            <footer className="print-footer">
                <p>Global AC System Jsr Pvt. Ltd. Address: 1502/A, Jawahar Nagar, Road No.: 17, PO - Azad Nagar, Mango, Jamshedpur - 832110, Jharkhand, INDIA</p>
            </footer>
            <button className="print-button" onClick={() => window.print()}>Print</button>
        </div>
    );
};

export default PrintPreview;
