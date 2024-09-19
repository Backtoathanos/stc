import React, { useEffect } from 'react';
import Footer from "./layouts/Footer";
import Navbar from "./layouts/Navbar";
import Sidebar from './layouts/Sidebar';
import { useLocation } from 'react-router-dom';

export default function Dashboard() {
    const location = useLocation();
    useEffect(() => {
        document.title = "STC GLD || Dashboard"; // Set the title
      }, []);
    // Extracting the current route
    const currentRoute = location.pathname === "/dashboard" ? "dashboard" : "inventory";

    return (
        <div className="wrapper ">
            <Sidebar activeRoute={currentRoute} />
            <div className="main-panel">
                <Navbar />
                <div className="content">
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col-lg-3 col-md-6 col-sm-6">
                                <div className="card card-stats">
                                    <div className="card-header card-header-warning card-header-icon">
                                        <div className="card-icon">
                                            <i className="material-icons">content_copy</i>
                                        </div>
                                        <p className="card-category">Total Customer</p>
                                        <h3 className="card-title">
                                            <small></small>
                                        </h3>
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            <i className="material-icons">local_offer</i> Carry On
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 col-sm-6">
                                <div className="card card-stats">
                                    <div className="card-header card-header-success card-header-icon">
                                        <div className="card-icon">
                                            <i className="material-icons">store</i>
                                        </div>
                                        <p className="card-category">Revenue</p>
                                        <p className="card-title">₹ 0</p>
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            <i className="material-icons">date_range</i> Last 1 Month
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 col-sm-6">
                                <div className="card card-stats">
                                    <div className="card-header card-header-danger card-header-icon">
                                        <div className="card-icon">
                                            <i className="fa fa-user-secret" aria-hidden="true"></i>
                                        </div>
                                        <p className="card-category">Total Agent</p>
                                        <h3 className="card-title">
                                            0
                                        </h3>
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            <i className="material-icons">local_offer</i> Tracked from STC Electronics
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 col-sm-6">
                                <div className="card card-stats">
                                    <div className="card-header card-header-info card-header-icon">
                                        <div className="card-icon">
                                            <i className="fa fa-instagram"></i>
                                        </div>
                                        <p className="card-category">Followers</p>
                                        <h3 className="card-title">+
                                            0
                                        </h3>
                                    </div>
                                    <div className="card-footer">
                                        <div className="stats">
                                            <i className="material-icons">update</i> Just Updated
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-12 col-sm-12">
                                <div className="card card-chart">
                                    <div className="card-header">
                                        <h2 className="text-center">Analytics Reports From 0</h2>
                                        0
                                        <div style={{ width: "100%", height: "20%", textAlign: "center" }}>
                                            <div>Sales </div>
                                            <canvas id="chartjs_bar"></canvas>
                                        </div>
                                    </div>
                                    <div className="card-body">
                                        <h4 className="card-title">Daily Sales</h4>
                                        <p className="card-category">
                                            <span className="text-success"><i className="fa fa-long-arrow-up"></i>  </span> </p>
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
        </div>
    );
}
