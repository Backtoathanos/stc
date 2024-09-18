import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import Footer from "./layouts/Footer";
import Navbar from "./layouts/Navbar";
import Sidebar from './layouts/Sidebar';
import { useLocation } from 'react-router-dom';
import CustomerModal from './CustomerModal';
import { RotatingLines } from 'react-loader-spinner'; // Importing spinner from react-loader-spinner
import './Datatable.css';

export default function Dashboard() {
    const location = useLocation();
    const [data, setData] = useState([]);
    const [search, setSearch] = useState(''); // State for search filter
    const [loading, setLoading] = useState(true); // Loading state
    const [modalShow, setModalShow] = useState(false); // State for modal visibility
    const [selectedProductId, setSelectedProductId] = useState(null);
    const currentRoute = location.pathname === "/dashboard" ? "dashboard" : "inventory";

    // Fetch data from PHP API
    useEffect(() => {
        setLoading(true); // Start loading before data fetch
        fetch('http://localhost/stc/stc_gld/vanaheim/getInventoryData.php')  // Update URL with your PHP API path
            .then(response => response.json())
            .then(data => {
                setData(data);
                setLoading(false); // End loading after data fetch
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                setLoading(false); // End loading on error
            });
    }, []);

    // Define columns for DataTable
    const columns = [
        {
            name: 'Slno',
            selector: (row, index) => index + 1,  // auto-generate serial number
            sortable: false,
            center: true
        },
        {
            name: 'Product Id',
            selector: row => row.stc_product_id,
            sortable: true,
            center: true
        },
        {
            name: 'Image',
            selector: row => row.stc_product_image,
            sortable: false,
            center: true,
            cell: row => {
                const imageUrl = `https://stcassociate.com/stc_symbiote/stc_product_image/${row.stc_product_image}`;
                const defaultImageUrl = 'https://img.freepik.com/premium-vector/default-image-icon-vector-missing-picture-page-website-design-mobile-app-no-photo-available_87543-11093.jpg?w=996'; // Replace with the actual path to your default image

                return (
                    <img 
                        src={row.stc_product_image ? imageUrl : defaultImageUrl} 
                        alt="Product" 
                        style={{ width: '100px', height: '80px', borderRadius: '5px' }} 
                        onError={(e) => e.target.src = defaultImageUrl} // If the image fails to load, use default
                    />
                );
            }
        },
        {
            name: 'Product Description',
            selector: row => row.stc_product_name,
            sortable: true,
        },
        {
            name: 'Unit',
            selector: row => row.stc_product_unit,
            sortable: true,
            center: true
        },
        {
            name: 'Inv Qty.',
            selector: row => row.stc_item_inventory_pd_qty,
            sortable: true,
            right: true
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
                <a
                    href="#"
                    className="btn btn-primary"
                    onClick={() => {
                        setSelectedProductId(row.stc_product_id);
                        setModalShow(true);
                    }}
                >
                    Add
                </a>
            )
        }
    ];

    // Filter data based on search input
    const filteredData = data.filter(item =>
        item.stc_product_name.toLowerCase().includes(search.toLowerCase()) ||
        item.stc_product_unit.toLowerCase().includes(search.toLowerCase())
    );

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
                                        <div className="text-center">
                                            <input
                                                type="text"
                                                placeholder="Search..."
                                                value={search}
                                                onChange={(e) => setSearch(e.target.value)}
                                                className="form-control"
                                                style={{ width: '300px', margin: '0 auto' }}
                                            />
                                        </div>
                                    </div>
                                    <div className="card-body">
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
                                                data={filteredData}
                                                pagination
                                                highlightOnHover
                                                striped
                                                className="data-table"  // Custom class for additional styling
                                            />
                                        )}
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            <i className="material-icons">access_time</i> updated a minutes ago
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
            />
        </div>
    );
}
