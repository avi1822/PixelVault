<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PixelVault</title>
    <link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/adminpanel_style.css">
    <link rel="stylesheet" href="/assets/css/loader_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/assets/js/admin_page.js"></script>
</head>

<body>
    <div class="adminarea">
        <div class="container">
            <nav>
                <div class="logo"><i class="fa-solid fa-bars"></i>
                    <div class="text"><span>P</span>IXELVAULT</div>
                </div>
                <div class="logout"><a href="{{ route("logout") }}" id="logoutbtn">Logout</a></div>
            </nav>
            <div class="admin">
                <div class="subcontainer">
                    <div class="ul">
                        <input type="radio" name="slidmenu" value="Analytics" id="menu12" checked>
                        <label for="menu12">
                            <i class="fa-solid fa-chart-line"></i>
                            <div class="text">Executive Analytics</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Reservations" id="menu1">
                        <label for="menu1">
                            <i class="fa-solid fa-calendar-days"></i>
                            <div class="text">Reservations</div>
                        </label>
                        <input type="radio" name="slidmenu" value="users" id="menu6">
                        <label for="menu6">
                            <i class="fa-solid fa-users"></i>
                            <div class="text">Users</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Computers" id="menu2">
                        <label for="menu2">
                            <i class="fa-solid fa-desktop"></i>
                            <div class="text">Gaming Stations</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Packages" id="menu3">
                        <label for="menu3">
                            <i class="fa-solid fa-box-open"></i>
                            <div class="text">Packages</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Games" id="menu4">
                        <label for="menu4">
                            <i class="fa-solid fa-gamepad"></i>
                            <div class="text">Games</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Settings" id="menu5">
                        <label for="menu5">
                            <i class="fa-solid fa-gear"></i>
                            <div class="text">Settings</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Inventory" id="menu11">
                        <label for="menu11">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <div class="text">Inventory & F&B</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Memberships" id="menu10">
                        <label for="menu10">
                            <i class="fa-solid fa-id-card"></i>
                            <div class="text">Memberships</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Billing" id="menu9">
                        <label for="menu9">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <div class="text">Billing & Invoices</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Sessions" id="menu8">
                        <label for="menu8">
                            <i class="fa-solid fa-stopwatch"></i>
                            <div class="text">Active Sessions</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Visitors" id="menu7">
                        <label for="menu7">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <div class="text">Daily Visitors</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Messages" id="menu13">
                        <label for="menu13" style="position: relative;">
                            <i class="fa-solid fa-envelope"></i>
                            <span id="msgUnreadBadge" style="display: none; position: absolute; top: 6px; right: 12px; background: #ff6b6b; color: #fff; font-size: 0.7rem; font-weight: bold; padding: 2px 6px; border-radius: 10px; border: 1px solid var(--bgcolor);">0</span>
                            <div class="text">Visitor Messages</div>
                        </label>
                    </div>
                </div>
                <div class="subcontainer2">
                    <div class="analyticsdata">
                        <div class="datatcontainer">
                            <!-- Executive Toolbar: Date Range Filter & Manual Refresh -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; background: var(--bgcolor3); padding: 15px; border-radius: 10px; border: 1px solid var(--secondc);">
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <span style="font-weight: bold; color: var(--secondc);"><i class="fa-solid fa-filter"></i> Date Range:</span>
                                    <button class="btn-date-preset" data-preset="today" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 6px 12px; border-radius: 6px; font-weight: bold; cursor: pointer;">Today</button>
                                    <button class="btn-date-preset" data-preset="7days" style="background: var(--bgcolor2); color: #fff; border: 1px solid var(--secondc); padding: 6px 12px; border-radius: 6px; font-weight: bold; cursor: pointer;">Last 7 Days</button>
                                    <button class="btn-date-preset" data-preset="30days" style="background: var(--bgcolor2); color: #fff; border: 1px solid var(--secondc); padding: 6px 12px; border-radius: 6px; font-weight: bold; cursor: pointer;">Last 30 Days</button>
                                    <button class="btn-date-preset" data-preset="month" style="background: var(--bgcolor2); color: #fff; border: 1px solid var(--secondc); padding: 6px 12px; border-radius: 6px; font-weight: bold; cursor: pointer;">This Month</button>
                                    <div style="display: flex; align-items: center; gap: 5px; margin-left: 10px;">
                                        <input type="date" id="analytics_start_date" style="padding: 5px; background: var(--bgcolor2); border: 1px solid var(--secondc); color: #fff; border-radius: 4px;">
                                        <span>to</span>
                                        <input type="date" id="analytics_end_date" style="padding: 5px; background: var(--bgcolor2); border: 1px solid var(--secondc); color: #fff; border-radius: 4px;">
                                        <button id="btnApplyCustomDate" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 6px 10px; border-radius: 4px; font-weight: bold; cursor: pointer;">Apply</button>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <span style="font-size: 0.85rem; color: #aaa;" id="analytics_last_updated">Last updated: --:--:--</span>
                                    <button id="btnRefreshAnalytics" style="background: #339af0; color: #fff; border: none; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;"><i class="fa-solid fa-rotate-right"></i> Refresh</button>
                                </div>
                            </div>

                            <!-- Executive KPI Cards -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">
                                <div style="background: var(--bgcolor3); border-left: 4px solid var(--secondc); padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">TOTAL INVOICED</div>
                                    <div style="font-size: 1.6rem; font-weight: bold; color: var(--secondc);" id="kpi_total_invoiced">Rs.0</div>
                                </div>
                                <div style="background: var(--bgcolor3); border-left: 4px solid #51cf66; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">TOTAL COLLECTED</div>
                                    <div style="font-size: 1.6rem; font-weight: bold; color: #51cf66;" id="kpi_total_paid">Rs.0</div>
                                </div>
                                <div style="background: var(--bgcolor3); border-left: 4px solid #ff6b6b; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">PENDING BALANCE</div>
                                    <div style="font-size: 1.6rem; font-weight: bold; color: #ff6b6b;" id="kpi_total_pending">Rs.0</div>
                                </div>
                                <div style="background: var(--bgcolor3); border-left: 4px solid #339af0; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">GAMING REVENUE</div>
                                    <div style="font-size: 1.6rem; font-weight: bold; color: #339af0;" id="kpi_gaming_rev">Rs.0</div>
                                </div>
                                <div style="background: var(--bgcolor3); border-left: 4px solid #fcc419; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">F&B SALES REVENUE</div>
                                    <div style="font-size: 1.6rem; font-weight: bold; color: #fcc419;" id="kpi_fnb_rev">Rs.0</div>
                                </div>
                                <div style="background: var(--bgcolor3); border-left: 4px solid #cc5de8; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">VIP MEMBERSHIPS</div>
                                    <div style="font-size: 1.6rem; font-weight: bold; color: #cc5de8;" id="kpi_mem_rev">Rs.0</div>
                                </div>
                            </div>

                            <!-- Operational Health Alerts Panel -->
                            <div style="background: var(--bgcolor3); border: 1px solid var(--secondc); border-radius: 10px; padding: 15px; margin-bottom: 25px;">
                                <h4 style="margin: 0 0 12px 0; color: var(--secondc);"><i class="fa-solid fa-heart-pulse"></i> Operational Health & System Alerts</h4>
                                <div id="operationalAlertsList" style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <div style="color: #51cf66;">🟢 All systems operational</div>
                                </div>
                            </div>

                            <!-- Charts Grid Section -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 30px;">
                                <div style="background: var(--bgcolor3); border-radius: 10px; padding: 20px; border: 1px solid rgba(255,255,255,0.1);">
                                    <h4 style="margin: 0 0 15px 0; color: var(--secondc); text-align: center;"><i class="fa-solid fa-chart-pie"></i> Revenue Breakdown</h4>
                                    <div style="height: 240px; position: relative;">
                                        <canvas id="chartRevenueBreakdown"></canvas>
                                    </div>
                                </div>
                                <div style="background: var(--bgcolor3); border-radius: 10px; padding: 20px; border: 1px solid rgba(255,255,255,0.1);">
                                    <h4 style="margin: 0 0 15px 0; color: var(--secondc); text-align: center;"><i class="fa-solid fa-wallet"></i> Payment Methods</h4>
                                    <div style="height: 240px; position: relative;">
                                        <canvas id="chartPaymentMethods"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Station Utilization Section -->
                            <div style="background: var(--bgcolor3); border-radius: 10px; padding: 20px; margin-bottom: 25px; border: 1px solid rgba(255,255,255,0.1);">
                                <h4 style="margin: 0 0 15px 0; color: var(--secondc);"><i class="fa-solid fa-desktop"></i> Gaming Station Utilization Rank</h4>
                                <div id="stationUtilizationGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="reservationsdata">
                        <div class="datatcontainer">
                            <div class="station-overview-cards" style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                                <div class="st-card" style="flex: 1; min-width: 140px; background: var(--bgcolor3); border-left: 4px solid #51cf66; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">🟢 AVAILABLE STATIONS</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #51cf66;" id="cntAvailable">--</div>
                                </div>
                                <div class="st-card" style="flex: 1; min-width: 140px; background: var(--bgcolor3); border-left: 4px solid #cc5de8; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">🟣 RESERVED STATIONS</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #cc5de8;" id="cntReserved">--</div>
                                </div>
                                <div class="st-card" style="flex: 1; min-width: 140px; background: var(--bgcolor3); border-left: 4px solid #339af0; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">🔵 PLAYING STATIONS</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #339af0;" id="cntPlaying">--</div>
                                </div>
                                <div class="st-card" style="flex: 1; min-width: 140px; background: var(--bgcolor3); border-left: 4px solid #fcc419; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">🟡 MAINTENANCE</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #fcc419;" id="cntMaintenance">--</div>
                                </div>
                                <div class="st-card" style="flex: 1; min-width: 140px; background: var(--bgcolor3); border-left: 4px solid #ff6b6b; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">🔴 OFFLINE</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #ff6b6b;" id="cntOffline">--</div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <h3 style="color: var(--secondc); margin: 0;"><i class="fa-solid fa-calendar-check"></i> Today's Reservations</h3>
                            </div>
                            <table id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Station #</th>
                                        <th>Package</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="sessionsdata">
                        <div class="datatcontainer">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h3 style="color: var(--secondc); margin: 0;"><i class="fa-solid fa-stopwatch"></i> Active Gaming Sessions</h3>
                                <button id="btnStartWalkInModal" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">+ Start Walk-in Session</button>
                            </div>
                            <div id="activeSessionsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; margin-bottom: 30px;">
                                <div style="color: #aaa;">No active gaming sessions running right now.</div>
                            </div>

                            <h3 style="color: var(--secondc); margin-bottom: 15px;"><i class="fa-solid fa-clock-rotate-left"></i> Session History</h3>
                            <table id="sessionHistoryTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Session ID</th>
                                        <th>Customer</th>
                                        <th>Station</th>
                                        <th>Started At</th>
                                        <th>Ended At</th>
                                        <th>Duration (mins)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="billingdata">
                        <div class="datatcontainer">
                            <div class="billing-summary-cards" style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 160px; background: var(--bgcolor3); border-left: 4px solid var(--secondc); padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">TODAY'S TOTAL SALES</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--secondc);" id="b_stat_sales">Rs.0</div>
                                </div>
                                <div style="flex: 1; min-width: 160px; background: var(--bgcolor3); border-left: 4px solid #51cf66; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">TODAY'S PAID AMOUNT</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #51cf66;" id="b_stat_paid">Rs.0</div>
                                </div>
                                <div style="flex: 1; min-width: 160px; background: var(--bgcolor3); border-left: 4px solid #ff6b6b; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">TODAY'S PENDING BALANCE</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #ff6b6b;" id="b_stat_pending">Rs.0</div>
                                </div>
                                <div style="flex: 1; min-width: 160px; background: var(--bgcolor3); border-left: 4px solid #339af0; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 0.8rem; color: #aaa;">TOTAL INVOICES</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #339af0;" id="b_stat_count">0</div>
                                </div>
                            </div>
                            <h3 style="color: var(--secondc); margin-bottom: 15px;"><i class="fa-solid fa-file-invoice"></i> Invoices & Payments</h3>
                            <table id="invoiceTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Subtotal</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Status</th>
                                        <th>Issued At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="membershipsdata">
                        <div class="datatcontainer">
                            <h3 style="color: var(--secondc); margin-bottom: 15px;"><i class="fa-solid fa-id-card"></i> Customer Memberships</h3>
                            <table id="membershipTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer Name</th>
                                        <th>Plan</th>
                                        <th>Gaming Hours Left</th>
                                        <th>Discount %</th>
                                        <th>Price</th>
                                        <th>Start Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="inventorydata">
                        <div class="datatcontainer">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h3 style="color: var(--secondc); margin: 0;"><i class="fa-solid fa-boxes-stacked"></i> F&B Product & Stock Management</h3>
                                <button id="btnOpenRestockModal" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">+ Restock / Add Product</button>
                            </div>
                            <table id="inventoryTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Stock Quantity</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="usersdata">
                        <div class="datatcontainer">
                            <table id="userTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>First_name</th>
                                        <th>Last_name</th>
                                        <th>User_name</th>
                                        <th>Phone_number</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="computersdata">
                        <div class="datatcontainer">
                            <div class="station-filter-bar" style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                                <button class="st-filter-btn active" data-status="" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;">ALL</button>
                                <button class="st-filter-btn" data-status="AVAILABLE" style="background: var(--bgcolor3); color: #51cf66; border: 1px solid #51cf66; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;">🟢 AVAILABLE</button>
                                <button class="st-filter-btn" data-status="RESERVED" style="background: var(--bgcolor3); color: #cc5de8; border: 1px solid #cc5de8; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;">🟣 RESERVED</button>
                                <button class="st-filter-btn" data-status="PLAYING" style="background: var(--bgcolor3); color: #339af0; border: 1px solid #339af0; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;">🔵 PLAYING</button>
                                <button class="st-filter-btn" data-status="MAINTENANCE" style="background: var(--bgcolor3); color: #fcc419; border: 1px solid #fcc419; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;">🟡 MAINTENANCE</button>
                                <button class="st-filter-btn" data-status="OFFLINE" style="background: var(--bgcolor3); color: #ff6b6b; border: 1px solid #ff6b6b; padding: 6px 14px; border-radius: 6px; font-weight: bold; cursor: pointer;">🔴 OFFLINE</button>
                            </div>
                            <div class="con">
                                <div class="pcline"></div>
                                <div class="buttons">
                                    <button id="newpcbtn">New</button>
                                    <button id="updatepcbtn">Update Specs</button>
                                    <button id="deletepcbtn">Delete</button>
                                </div>
                            </div>
                            <div class="con2">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <div class="pcname" id="computername">Station #01</div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <label for="stationStatusSelect" style="font-weight: bold; color: var(--secondc);">Operational Status:</label>
                                        <select id="stationStatusSelect" style="background: var(--bgcolor3); color: #fff; border: 1px solid var(--secondc); padding: 8px 12px; border-radius: 6px; font-weight: bold;">
                                            <option value="AVAILABLE">🟢 AVAILABLE</option>
                                            <option value="RESERVED">🟣 RESERVED</option>
                                            <option value="PLAYING">🔵 PLAYING</option>
                                            <option value="MAINTENANCE">🟡 MAINTENANCE</option>
                                            <option value="OFFLINE">🔴 OFFLINE</option>
                                        </select>
                                        <button id="saveStatusBtn" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">Save Status</button>
                                    </div>
                                </div>
                                <div class="exp">
                                    <div class="text">Select Installed Games</div><i class="fa-solid fa-angle-down"></i>
                                </div>
                                <div class="pcgames"></div>
                                <div class="exp">
                                    <div class="text">Edit Specifications</div><i class="fa-solid fa-angle-down"></i>
                                </div>
                                <div class="pcspecs">
                                    <input type="text" id="pcspec1" placeholder="Spec 1 (e.g. PS5 Pro 4K HDR)">
                                    <input type="text" id="pcspec2" placeholder="Spec 2 (e.g. 120Hz OLED Display)">
                                    <input type="text" id="pcspec3" placeholder="Spec 3 (e.g. DualSense Edge Controller)">
                                    <input type="text" id="pcspec4" placeholder="Spec 4 (e.g. 3D Audio Headset)">
                                    <input type="text" id="pcspec5" placeholder="Spec 5">
                                    <input type="text" id="pcspec6" placeholder="Spec 6">
                                    <input type="text" id="pcspec7" placeholder="Spec 7">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="packagesdata">
                        <div class="datatcontainer">
                            <div class="con">
                                <div class="pkg">
                                    <div class="exp">Slect a pakage<span> *</span></div>
                                    <div class="pkglist">
                                    </div>
                                </div>
                                <div class="buttons">
                                    <button id="newpackbtn">New</button>
                                    <button id="updatepackbtn">Update</button>
                                    <button id="delpackbtn">Delete</button>
                                </div>
                            </div>
                            <div class="con2">
                                <div class="exp">Package Name</div>
                                <input type="text" id="packname">
                                <div class="error" id="packnameerror"></div>
                                <div class="exp">Package Time (Hours)</div>
                                <input type="text" id="packtime">
                                <div class="error" id="packtimeerror"></div>
                                <div class="exp">Ground Floor Price - PS5 Arena (Rs)</div>
                                <input type="text" id="packgroundprice" placeholder="e.g. 99 (Rs. 99/hr)">
                                <div class="error" id="packgroundpriceerror"></div>
                                <div class="exp">Upper Floor Price - Ghost of Yōtei VIP (Rs)</div>
                                <input type="text" id="packupperprice" placeholder="e.g. 120 (Rs. 120/hr)">
                                <div class="error" id="packupperpriceerror"></div>
                                <div class="exp">Default/Base Package Price (Rs)</div>
                                <input type="text" id="packprice">
                                <div class="error" id="packpriceerror"></div>
                            </div>
                        </div>
                    </div>
                    <div class="gamesdata">
                        <div class="datatcontainer">
                            <div class="con">
                                <table id="gamedataTable">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="con2">
                                <div class="exp">Game Name</div>
                                <input type="text" id="gamename">
                                <div class="error" id="gamenameerror"></div>
                                <div class="exp">Image link</div>
                                <input type="text" id="gameurl">
                                <div class="error" id="gameurlerror"></div>
                                <div class="img"></div>
                                <div class="buttons">
                                    <button id="newgamebtn">New</button>
                                    <button id="updategamebtn">Update</button>
                                    <button id="delgamebtn">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settingsdata">
                        <div class="datatcontainer">
                            <div class="stcontainer">
                                <div class="propic"></div>
                                <div class="text">Select a profile picture</div>
                                <div class="propiclist">
                                </div>
                            </div>
                            <div class="stcontainer2">
                                <div class="profiledata">
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">First Name</div>
                                            <input type="text" name="firstname" id="fname">
                                            <div class="error" id="uufname"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Last Name</div>
                                            <input type="text" name="lastname" id="lname">
                                            <div class="error" id="uulname"></div>
                                        </div>
                                    </div>
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">User Name</div>
                                            <input type="text" name="username" id="uname">
                                            <div class="error" id="uuuname"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Phone Number</div>
                                            <input type="text" name="phonenumber" id="pnumber">
                                            <div class="error" id="uupnumber"></div>
                                        </div>
                                    </div>

                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">Address</div>
                                            <input type="text" name="address" id="address">
                                            <div class="error" id="uuaddress"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Email Address</div>
                                            <input type="text" name="email" id="email">
                                            <div class="error" id="uuemail"></div>
                                        </div>
                                    </div>
                                    <button id="updateProFileBtn">Update Profile</button>
                                </div>
                                {{-- <div class="text">Change your password</div> --}}
                                <div class="passwordcontainer">
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">Current Password</div>
                                            <input type="text" name="oldpassword" id="oldpassword">
                                            <div class="error" id="uuoldpass"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">New Password</div>
                                            <input type="text" name="newpassword" id="newpassword">
                                            <div class="error" id="uunewpass"></div>
                                        </div>
                                    </div>
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">&nbsp;</div>
                                            <button id="changePasswordBtn">Change Password</button>
                                            <div class="error"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Retype New Password</div>
                                            <input type="text" name="confirmPassword" id="confirmPassword">
                                            <div class="error" id="uuconpass"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="visitorsdata">
                        <div class="datatcontainer">
                            <div class="visitors-header-bar">
                                <h2 style="color: var(--secondc); margin:0; font-size: 1.4rem;"><i class="fa-solid fa-chart-pie"></i> Daily Visitor Logger & Analytics</h2>
                                <div class="visitors-filter-group">
                                    <label style="color:#aaa; font-size:0.85rem;">Filter By:</label>
                                    <input type="date" id="filter_date" title="Filter by Date">
                                    <input type="month" id="filter_month" title="Filter by Month">
                                    <input type="number" id="filter_year" placeholder="Year (2026)" style="width:110px;">
                                    <select id="filter_zone">
                                        <option value="">All Zones</option>
                                        <option value="Upper Floor (PS5 Lounge)">🎮 Upper Floor (PS5)</option>
                                        <option value="Lower Floor (PC Arena)">💻 Lower Floor (PC)</option>
                                    </select>
                                    <button id="resetVisitorFiltersBtn" style="background:#444; color:#fff; border:none; padding:8px 14px; border-radius:6px; cursor:pointer;">Reset</button>
                                </div>
                            </div>

                            <div class="visitor-stat-grid">
                                <div class="v-stat-card">
                                    <div class="v-num" id="v_stat_total">0</div>
                                    <div class="v-lbl">Total Visitors</div>
                                </div>
                                <div class="v-stat-card">
                                    <div class="v-num" id="v_stat_hours">0</div>
                                    <div class="v-lbl">Total Hours Played</div>
                                </div>
                                <div class="v-stat-card">
                                    <div class="v-num" id="v_stat_ps5">0</div>
                                    <div class="v-lbl">Upper Floor (PS5)</div>
                                </div>
                                <div class="v-stat-card">
                                    <div class="v-num" id="v_stat_pc">0</div>
                                    <div class="v-lbl">Lower Floor (PC)</div>
                                </div>
                                <div class="v-stat-card">
                                    <div class="v-num" id="v_stat_topgame" style="font-size:1.1rem; color:#fff;">N/A</div>
                                    <div class="v-lbl">Top Game Played</div>
                                </div>
                            </div>

                            <div class="visitor-form-container">
                                <div class="visitor-form-title"><i class="fa-solid fa-plus-circle"></i> Log Daily Visitor Entry</div>
                                <div class="visitor-form-grid">
                                    <div class="v-form-group">
                                        <label>Visitor Name *</label>
                                        <input type="text" id="v_name" placeholder="e.g. John Doe">
                                    </div>
                                    <div class="v-form-group">
                                        <label>Phone Number (10 Digits)</label>
                                        <input type="text" id="v_phone" maxlength="10" placeholder="e.g. 9876543210" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                                    </div>
                                    <div class="v-form-group">
                                        <label>Gaming Zone / Floor *</label>
                                        <select id="v_zone">
                                            <option value="Ground Floor (PS5 Standard)">🎮 Ground Floor - PS5 Console Arena (2 PS5s - Rs. 99/hr)</option>
                                            <option value="Upper Floor (PS5 VIP Cloud Edition)">✨ Upper Floor - Special Edition VIP Lounge (1 PS5 + Cloud Lights - Rs. 120/hr)</option>
                                        </select>
                                    </div>
                                    <div class="v-form-group">
                                        <label>Hours Played *</label>
                                        <select id="v_hours">
                                            <option value="1">1 Hour</option>
                                            <option value="2">2 Hours</option>
                                            <option value="3">3 Hours</option>
                                            <option value="4">4 Hours</option>
                                            <option value="5">5 Hours</option>
                                        </select>
                                    </div>
                                    <div class="v-form-group">
                                        <label>Game Played *</label>
                                        <select id="v_game">
                                            <!-- Dynamically populated -->
                                        </select>
                                    </div>
                                    <div class="v-form-group">
                                        <label>Additional Food Item Purchased</label>
                                        <select id="v_food">
                                            <option value="None">None</option>
                                            <option value="Gourmet Burger & Soft Drink">🍔 Gourmet Burger & Soft Drink</option>
                                            <option value="Pizza Slice & Fries">🍕 Pizza Slice & Fries</option>
                                            <option value="Energy Drink (Red Bull)">⚡ Energy Drink (Red Bull)</option>
                                            <option value="Energy Drink (Monster)">⚡ Energy Drink (Monster)</option>
                                            <option value="Snacks & Chips">🍿 Snacks & Chips</option>
                                            <option value="Cold Coffee">☕ Cold Coffee</option>
                                            <option value="Combo Meal">🍱 Combo Meal</option>
                                        </select>
                                    </div>
                                    <div class="v-form-group">
                                        <label>Entry Date *</label>
                                        <input type="date" id="v_date">
                                    </div>
                                    <div class="v-form-group">
                                        <label>Total Calculated Amount</label>
                                        <div style="background: var(--bgcolor3); border: 1px solid var(--secondc); padding: 10px 14px; border-radius: 8px; font-size: 1.2rem; font-weight: bold; color: #51cf66;" id="v_total_amount_box">
                                            Rs. 120
                                        </div>
                                    </div>
                                </div>
                                <button class="save-visitor-btn" id="saveVisitorBtn"><i class="fa-solid fa-check"></i> Save Visitor Entry</button>
                                <div id="v_form_msg" style="margin-top:10px;"></div>
                            </div>

                            <table id="visitorTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Visitor Name</th>
                                        <th>Phone</th>
                                        <th>Date</th>
                                        <th>Hours Played</th>
                                        <th>Game Played</th>
                                        <th>Food Item</th>
                                        <th>Floor / Zone</th>
                                        <th>Amount Paid</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="messagesdata">
                        <div class="datatcontainer">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h3 style="color: var(--secondc); margin: 0;"><i class="fa-solid fa-envelope"></i> Visitor Inquiries & Messages</h3>
                            </div>
                            <table id="messagesTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Received At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Gaming Session & Auto-Log Visitor Modal -->
    <div id="endSessionModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: var(--bgcolor2); border: 1px solid var(--secondc); padding: 25px; border-radius: 12px; width: 90%; max-width: 440px; color: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: var(--secondc);"><i class="fa-solid fa-flag-checkered"></i> Complete & End Gaming Session</h3>
                <button id="closeEndSessionModal" style="background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <input type="hidden" id="end_session_id">
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="font-size: 0.95rem; background: var(--bgcolor3); padding: 12px; border-radius: 8px;">
                    <div><strong>Customer:</strong> <span id="end_sess_customer">--</span></div>
                    <div><strong>Station:</strong> <span id="end_sess_station">--</span></div>
                    <div><strong>Duration Played:</strong> <span id="end_sess_duration">--</span></div>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Food / Snack Item Purchased during Session:</label>
                    <select id="end_sess_food" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                        <option value="None">None</option>
                        <option value="Gourmet Burger & Soft Drink">🍔 Gourmet Burger & Soft Drink</option>
                        <option value="Pizza Slice & Fries">🍕 Pizza Slice & Fries</option>
                        <option value="Energy Drink (Red Bull)">⚡ Energy Drink (Red Bull)</option>
                        <option value="Energy Drink (Monster)">⚡ Energy Drink (Monster)</option>
                        <option value="Snacks & Chips">🍿 Snacks & Chips</option>
                        <option value="Cold Coffee">☕ Cold Coffee</option>
                        <option value="Combo Meal">🍱 Combo Meal</option>
                    </select>
                </div>
                <div style="font-size: 0.85rem; color: #51cf66; background: rgba(81, 207, 102, 0.1); padding: 8px; border-radius: 6px;">
                    <i class="fa-solid fa-circle-check"></i> Ending session will automatically log this entry into the Daily Visitor Register and generate an invoice.
                </div>
                <button id="btnConfirmEndSession" style="background: #ff6b6b; color: #fff; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 5px;">Complete & Save Entry</button>
                <div id="end_sess_msg" style="font-size: 0.9rem; text-align: center;"></div>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: var(--bgcolor2); border: 1px solid var(--secondc); padding: 25px; border-radius: 12px; width: 90%; max-width: 420px; color: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: var(--secondc);"><i class="fa-solid fa-cash-register"></i> Record Payment</h3>
                <button id="closePaymentModal" style="background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <input type="hidden" id="pay_invoice_id">
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="font-size: 0.95rem;">
                    <strong>Invoice #:</strong> <span id="pay_inv_num">--</span><br>
                    <strong>Total Amount:</strong> Rs.<span id="pay_total_amt">0</span><br>
                    <strong>Remaining Balance:</strong> <span style="color: #ff6b6b; font-weight: bold;">Rs.<span id="pay_rem_amt">0</span></span>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Payment Amount (Rs.) *:</label>
                    <input type="number" id="pay_amount" min="1" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Payment Method *:</label>
                    <select id="pay_method" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                        <option value="CASH">💵 CASH</option>
                        <option value="UPI">📱 UPI / QR</option>
                        <option value="CARD">💳 CARD</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Transaction Ref / Notes (Optional):</label>
                    <input type="text" id="pay_ref" placeholder="e.g. UPI Ref #987654" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                </div>
                <button id="btnSubmitPayment" style="background: #51cf66; color: #000; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px;">Confirm & Record Payment</button>
                <div id="pay_msg" style="font-size: 0.9rem; text-align: center;"></div>
            </div>
        </div>
    </div>

    <!-- Printable Invoice Receipt Modal -->
    <div id="printableInvoiceModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 10000; justify-content: center; align-items: center; overflow-y: auto; padding: 20px;">
        <div style="background: #fff; color: #000; padding: 30px; border-radius: 8px; width: 100%; max-width: 480px; font-family: 'Courier New', Courier, monospace;" id="printableReceiptArea">
            <div style="text-align: center; border-bottom: 2px dashed #000; padding-bottom: 15px; margin-bottom: 15px;">
                <h2 style="margin: 0;">PIXELVAULT</h2>
                <div style="font-size: 0.9rem;">PS5 Gaming Lounge & Arena</div>
                <div style="font-size: 0.8rem; color: #555;">Email: pixelvault1011@gmail.com | Tel: +91 9321495527</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 10px;">
                <span><strong>Invoice:</strong> <span id="rec_inv_num">--</span></span>
                <span><strong>Date:</strong> <span id="rec_date">--</span></span>
            </div>
            <div style="font-size: 0.85rem; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                <strong>Customer:</strong> <span id="rec_cust_name">--</span>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 15px;">
                <thead>
                    <tr style="border-bottom: 1px solid #000; text-align: left;">
                        <th style="padding: 5px 0;">Item Description</th>
                        <th style="padding: 5px 0; text-align: right;">Qty</th>
                        <th style="padding: 5px 0; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody id="rec_items_body">
                </tbody>
            </table>
            <div style="border-top: 1px solid #000; padding-top: 10px; font-size: 0.9rem; margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Subtotal:</span>
                    <span>Rs.<span id="rec_subtotal">0</span></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Discount:</span>
                    <span>Rs.<span id="rec_discount">0</span></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem; margin-top: 5px;">
                    <span>TOTAL:</span>
                    <span>Rs.<span id="rec_total">0</span></span>
                </div>
                <div style="display: flex; justify-content: space-between; color: #2b8a3e; margin-top: 5px;">
                    <span>Paid Amount:</span>
                    <span>Rs.<span id="rec_paid">0</span></span>
                </div>
            </div>
            <div style="text-align: center; border-top: 2px dashed #000; padding-top: 15px; font-size: 0.85rem;">
                <div>Payment Status: <strong id="rec_status">--</strong></div>
                <div style="margin-top: 5px; color: #555;">Thank you for gaming at PixelVault!</div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px; text-align: center;" class="no-print">
                <button onclick="window.print()" style="flex: 1; padding: 10px; background: #339af0; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">🖨 Print Invoice</button>
                <button id="closeReceiptModal" style="flex: 1; padding: 10px; background: #495057; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Close</button>
            </div>
        </div>
    </div>

    <!-- Restock & Stock Adjustment Modal -->
    <div id="restockModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: var(--bgcolor2); border: 1px solid var(--secondc); padding: 25px; border-radius: 12px; width: 90%; max-width: 420px; color: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: var(--secondc);"><i class="fa-solid fa-boxes-packing"></i> Restock / Add Stock</h3>
                <button id="closeRestockModal" style="background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Select Product *:</label>
                    <select id="restock_product_select" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Add Quantity *:</label>
                    <input type="number" id="restock_qty" min="1" value="10" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Reason / Notes:</label>
                    <input type="text" id="restock_reason" placeholder="e.g. Supplier Shipment #102" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                </div>
                <button id="btnSubmitRestock" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px;">Update Stock Now</button>
                <div id="restock_msg" style="font-size: 0.9rem; text-align: center;"></div>
            </div>
        </div>
    </div>
    <div class="loadercontainer">
        <div class="innercontainer">
            <div class="loader"></div>
        </div>
    </div>
</body>

</html>