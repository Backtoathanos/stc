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

export default function Dashboard() {
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
    const [filteredData, setFilteredData] = useState([]); // To handle filtered data

    const [page, setPage] = useState(1); // Current page
    const [limit, setLimit] = useState(10); // Rows per page
    const [totalRows, setTotalRows] = useState(0); // Total records from API

    // Fetch data from API with pagination
    const fetchData = debounce((query = '', pageNum = 1, rowLimit = 10) => {
        setLoading(true);
        axios.get(`${API_BASE_URL}/getInventoryData.php`, {
            params: { search: query, page: pageNum, limit: rowLimit }
        })
            .then(response => {
                if (response.data && response.data.records) {
                    setData(response.data.records);
                    setTotalRows(response.data.total); // Assuming API returns total count
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

    // Fetch data on component mount, page change, search change
    useEffect(() => {
        fetchData(search, page, limit);
    }, [search, page, limit]);

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
            center: true
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
            )
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
                                                paginationTotalRows={totalRows} // Total rows from API
                                                paginationPerPage={limit} // Default rows per page
                                                paginationRowsPerPageOptions={[10, 20, 50, 100]}
                                                onChangePage={(newPage) => setPage(newPage)}
                                                onChangeRowsPerPage={(newLimit, newPage) => {
                                                    setLimit(newLimit);
                                                    setPage(newPage);
                                                }}
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
        </div>
    );
}
