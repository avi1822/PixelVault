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
                            <table id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Computer</th>
                                        <th>Package</th>
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
                            <div class="con">
                                <div class="pcline"></div>
                                <div class="buttons">
                                    <button id="newpcbtn">New</button>
                                    <button id="updatepcbtn">Update</button>
                                    <button id="deletepcbtn">Delete</button>
                                </div>
                            </div>
                            <div class="con2">
                                <div class="pcname" id="computername">PC - 01</div>
                                <div class="exp">
                                    <div class="text">Select Games</div><i class="fa-solid fa-angle-down"></i>
                                </div>
                                <div class="pcgames"></div>
                                <div class="exp">
                                    <div class="text">Edit details</div><i class="fa-solid fa-angle-down"></i>
                                </div>
                                <div class="pcspecs">
                                    <input type="text" id="pcspec1" placeholder="Spec 1">
                                    <input type="text" id="pcspec2" placeholder="Spec 2">
                                    <input type="text" id="pcspec3" placeholder="Spec 3">
                                    <input type="text" id="pcspec4" placeholder="Spec 4">
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
    <div class="loadercontainer">
        <div class="innercontainer">
            <div class="loader"></div>
        </div>
    </div>
</body>

</html>