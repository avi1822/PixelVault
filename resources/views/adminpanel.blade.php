<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PixelVault</title>
    <link rel="stylesheet" href="{{url('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="{{url('assets/css/adminpanel_style.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/loader_style.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="{{url('assets/js/admin_page.js')}}"></script>
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
                        <input type="radio" name="slidmenu" value="Reservations" id="menu1" checked>
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
                    </div>
                </div>
                <div class="subcontainer2">
                    {{-- <div class="caption">
                        <div class="text">Reservations</div>
                    </div> --}}
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
                                <div class="exp">Package Price (Rs)</div>
                                <input type="text" id="packprice">
                                <div class="error" id="packpriceerror"></div>
                                {{-- <div class="exp">Package Description</div>
                                <input type="text"> --}}
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
                                        <label>Phone Number</label>
                                        <input type="text" id="v_phone" placeholder="e.g. 9876543210">
                                    </div>
                                    <div class="v-form-group">
                                        <label>Gaming Zone / Floor *</label>
                                        <select id="v_zone">
                                            <option value="Upper Floor (PS5 Lounge)">🎮 Upper Floor - PS5 Gaming Lounge</option>
                                            <option value="Lower Floor (PC Arena)">💻 Lower Floor - PC Gaming Arena</option>
                                        </select>
                                    </div>
                                    <div class="v-form-group">
                                        <label>Hours Played *</label>
                                        <select id="v_hours">
                                            <option value="1">1 Hour</option>
                                            <option value="2">2 Hours</option>
                                            <option value="3">3 Hours</option>
                                            <option value="4">4 Hours</option>
                                            <option value="5">5+ Hours</option>
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

    <!-- Walk-in Session Modal -->
    <div id="walkInModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: var(--bgcolor2); border: 1px solid var(--secondc); padding: 25px; border-radius: 12px; width: 90%; max-width: 420px; color: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: var(--secondc);"><i class="fa-solid fa-person-walking"></i> Start Walk-in Session</h3>
                <button id="closeWalkInModal" style="background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Guest / Customer Name:</label>
                    <input type="text" id="walkin_guest_name" placeholder="e.g. Rahul Sharma" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Select Station *:</label>
                    <select id="walkin_station_select" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Select Package *:</label>
                    <select id="walkin_package_select" style="width: 100%; padding: 8px 12px; background: var(--bgcolor3); border: 1px solid var(--secondc); color: #fff; border-radius: 6px;">
                    </select>
                </div>
                <button id="btnSubmitWalkIn" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px;">Start Session Now</button>
                <div id="walkin_msg" style="font-size: 0.9rem; text-align: center;"></div>
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