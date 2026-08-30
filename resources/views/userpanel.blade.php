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
    <link rel="stylesheet" href="/assets/css/userpanel_style.css">
    <link rel="stylesheet" href="/assets/css/pc_style.css">
    <link rel="stylesheet" href="/assets/css/pcbook_style.css">
    <link rel="stylesheet" href="/assets/css/loader_style.css">
    <link rel="stylesheet" href="/assets/css/calendar_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/user_page.js"></script>
    <script src="/assets/js/calendar.js"></script>
</head>

<body>
    <div class="adminarea">
        <div class="container">
            <nav>
                <div class="logo" id="mainlogo"><i class="fa-solid fa-bars"></i>
                    <div class="text"><span>P</span>IXELVAULT</div>
                </div>
                <div class="hiuser" style="opacity: 0.8;">Hi <span></span></div>
                <div class="logout"><a href="{{route("logout")}}" id="logoutbtn">Logout</a></div>
            </nav>
            <div class="admin">
                <div class="subcontainer" id="mainsubcontainer">
                    <div class="ul">
                        <input type="radio" name="slidmenu" value="Home" id="menu6" checked>
                        <label for="menu6">
                            <i class="fa-solid fa-house"></i>
                            <div class="text">Home</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Reservations" id="menu1">
                        <label for="menu1">
                            <i class="fa-solid fa-calendar-days"></i>
                            <div class="text">Reservations</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Computers" id="menu2">
                        <label for="menu2">
                            <i class="fa-solid fa-desktop"></i>
                            <div class="text">Gaming Stations</div>
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
                        <input type="radio" name="slidmenu" value="Snacks" id="menu9">
                        <label for="menu9">
                            <i class="fa-solid fa-burger"></i>
                            <div class="text">Snacks & Drinks</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Membership" id="menu8">
                        <label for="menu8">
                            <i class="fa-solid fa-crown"></i>
                            <div class="text">Membership</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Bills" id="menu7">
                        <label for="menu7">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <div class="text">My Bills</div>
                        </label>
                    </div>
                </div>
                <div class="subcontainer2" id="mainsubcontainer2">
                    <!-- <div class="caption">
                        <div class="text">Reservations</div>
                    </div> -->
                    <div class="homedata">
                        <div class="datatcontainer">
                            <div class="hdsubcontainer">
                                <!-- User Live Gaming Session Card -->
                                <div id="userActiveSessionCard" style="display: none; background: var(--bgcolor3); border: 2px solid var(--secondc); border-radius: 12px; padding: 18px; margin-bottom: 20px; color: #fff;">
                                </div>
                                <div class="dashboard" id="userdash">
                                    {{-- <div class="cvdec cvdec1"></div>
                                    <div class="cvdec cvdec2"></div>
                                    <div class="chdec chdec1"></div>
                                    <div class="chdec chdec2"></div> --}}
                                    <!-- <div class="part1"></div>
                                    <div class="part1"></div>
                                    <div class="part1"></div> -->
                                    <div class="medleContainer">
                                        <div class="medle" id="dashmedle">
                                        </div>
                                    </div>
                                    <!-- <div class="part2"></div>
                                    <div class="part2"></div>
                                    <div class="part2"></div> -->
                                    <div class="progress">
                                        <div class="memberType"></div>
                                        <div class="progressBar">
                                            <div class="progressBarline">
                                                <div class="progressBarinner"></div>
                                            </div>
                                            <div class="progressNum"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="expbtn">
                                    <div class="text">New Games</div>
                                    <div class="viewmore">View More</div>
                                </div>
                                <div class="gameselector homeselector">
                                    <div class="list ">
                                    </div>
                                </div>
                                <div class="expbtn">
                                    <div class="text">Most Populer Computers</div>
                                    <div class="viewmore">View More</div>
                                </div>
                                <div class="computerselector homeselector">
                                    <div class="list">
                                    </div>
                                </div>
                            </div>
                            <div class="hdsubcontainer2">
                                <div class="eventcalendar">
                                    <div class="datecontainer">
                                        <div class="icon">
                                            <i class="fa-solid fa-calendar-plus"></i>
                                        </div>
                                        <div class="details">
                                            <div class="today">Today</div>
                                            <div class="date">07-06-2022 </div>
                                        </div>
                                    </div>
                                    <div class="events">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <div class="computersdata">
                        <div class="datatcontainer">
                            <div class="floor-zone-bar" style="display: flex; gap: 15px; margin-bottom: 15px; justify-content: center; position: relative; z-index: 10;">
                                <button class="zone-btn active" id="btnZoneGround" style="background: var(--secondc); color: var(--bgcolor); border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: transform 0.2s;">
                                    🎮 Ground Floor - PS5 Console Arena (2 PS5s - Rs. 99/hr)
                                </button>
                                <button class="zone-btn" id="btnZoneUpper" style="background: var(--bgcolor3); color: #fff; border: 1px solid var(--secondc); padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: transform 0.2s;">
                                    ✨ Upper Floor - Ghost of Yōtei VIP Lounge (1 PS5 + Cloud Lights - Rs. 120/hr)
                                </button>
                            </div>
                            <div class="pc">
                                <div class="con">
                                    <div class="pcname" id="computername">🎮 Ground Floor PS5 #1</div>
                                    <div class="pcdetail">
                                        <div class="pcimg"></div>
                                        <div class="linecon">
                                            <div class="line"></div>
                                        </div>
                                        <div class="pcspec">
                                            <ul id="pcspecul">
                                            </ul>
                                            <div class="booknow"> <button id="booknowbtn">Book Now</button></div>
                                        </div>
                                    </div>
                                    <div class="dec"></div>
                                    <div class="pcselector">
                                        <div class="pclist">
                                            <div class="dumy"></div>
                                            <div class="dumy"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="con2">
                                    <div class="games">
                                        <div class="gamecaption">Games</div>
                                        <div class="gamelist">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gamesdata">
                        <div class="datatcontainer">
                            <div class="gameline">
                            </div>
                            <div class="btnline">
                                <div class="innerline">
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
                    <div class="snacksdata">
                        <div class="datatcontainer">
                            <h3 style="color: var(--secondc); margin-bottom: 15px;"><i class="fa-solid fa-burger"></i> F&B Snacks & Beverages Menu</h3>
                            <div id="userSnacksGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
                            </div>
                        </div>
                    </div>
                    <div class="membershipdata">
                        <div class="datatcontainer">
                            <!-- Active Membership Status Card -->
                            <div id="activeMembershipCard" style="background: var(--bgcolor3); border: 2px solid var(--secondc); border-radius: 12px; padding: 20px; margin-bottom: 25px; color: #fff;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h3 style="margin: 0; color: var(--secondc);"><i class="fa-solid fa-crown"></i> Active VIP Membership</h3>
                                    <span id="mem_badge_status" style="background: #51cf66; color: #000; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem;">ACTIVE</span>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                                    <div>
                                        <div style="font-size: 0.8rem; color: #aaa;">Current Plan</div>
                                        <div style="font-size: 1.3rem; font-weight: bold; color: #fff;" id="mem_plan_name">--</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; color: #aaa;">Gaming Hours Left</div>
                                        <div style="font-size: 1.3rem; font-weight: bold; color: #339af0;" id="mem_hours_left">--</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; color: #aaa;">Gaming Discount</div>
                                        <div style="font-size: 1.3rem; font-weight: bold; color: #51cf66;" id="mem_discount_pct">--</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; color: #aaa;">Valid Until</div>
                                        <div style="font-size: 1.1rem; font-weight: bold; color: #fcc419;" id="mem_expires_at">--</div>
                                    </div>
                                </div>
                            </div>

                            <h3 style="color: var(--secondc); margin-bottom: 15px;"><i class="fa-solid fa-gem"></i> Available VIP Membership Plans</h3>
                            <div id="membershipPlansGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
                            </div>
                        </div>
                    </div>
                    <div class="billsdata">
                        <div class="datatcontainer">
                            <h3 style="color: var(--secondc); margin-bottom: 15px;"><i class="fa-solid fa-file-invoice-dollar"></i> My Invoices & Payment History</h3>
                            <table id="userInvoiceTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="userInvoiceBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pcbookarea">
        <div class="container">
            <div class="close"><button id="closebtn"><i class="fa-solid fa-xmark"></i></button></div>
            <div class="book">
                <div class="capclose">
                    <div class="caption">Book Now</div>
                </div>
                <div class="detail">
                    <div class="dec dec1"></div>
                    <div class="dec dec2"></div>
                    <div class="pc">
                        <div class="pcimg"></div>
                        <div class="pcname">PC - 01</div>
                    </div>
                    <div class="pkg">
                        <div class="exp">Slect a Date<span> *</span></div>
                        <div class="date">
                            <input type="date" name="" id="date">
                        </div>
                        <div class="exp">Slect a pakage<span> *</span></div>
                        <div class="pkglist">
                        </div>
                    </div>
                    <div class="time">
                        <div class="exp">Slect a time slot<span> *</span></span></div>
                        <div class="timelist">
                        </div>
                        <div id="booking_msg" style="margin-top: 10px; font-size: 0.9rem; text-align: center;"></div>
                        <div class="paynow">
                            <button id="paynowbtn">Pay Now</button>
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