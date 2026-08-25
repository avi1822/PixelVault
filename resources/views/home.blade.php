<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelVault</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{url('assets/css/home_style.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{url('assets/js/jqFuwatto.umd.js')}}" defer></script>
    <script src="{{url('assets/js/home_page.js')}}"></script>
</head>

<body>
    <div class="homearea">
        <!-- <div class="backdec">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="680.813" height="752.828" viewBox="0 0 680.813 752.828">
                <defs>
                  <radialGradient id="radial-gradient" cx="0.5" cy="0.5" r="0.433" gradientTransform="matrix(0.613, -0.79, 0.831, 0.644, -0.222, 0.573)" gradientUnits="objectBoundingBox">
                    <stop offset="0" stop-color="#fece59"/>
                    <stop offset="1" stop-color="gray"/>
                  </radialGradient>
                </defs>
                <path id="Path_1" data-name="Path 1" d="M485.561,144.9c52.69-76.309,394.263-23.619,570.5,96.295s-76.309,243.462-56.323,327.038S1219.58,769.9,1112.384,829.86,578.222,873.465,507.363,686.326s39.971-259.814,10.9-327.038S432.871,221.205,485.561,144.9Z" transform="translate(-463.474 -108.673)" stroke="#707070" stroke-width="1" fill="url(#radial-gradient)"/>
              </svg>
        </div> -->
        <div class="upper">
            <nav>
                <div class="logo"><span>P</span>IXELVAULT</div>
                <div class="navlist">
                    <input type="radio" name="navLinks" value="home" id="navhome" checked><label
                        onclick="$('#navhome').prop('checked', true)" for="navhome"><a href="#">Home</a></label>
                    <input type="radio" name="navLinks" value="stations" id="navstations"><label
                        onclick="$('#navstations').prop('checked', true)" for="navstations"><a
                            href="#stations">Gaming Stations</a></label>
                    <input type="radio" name="navLinks" value="services" id="navservices"><label
                        onclick="$('#navservices').prop('checked', true)" for="navservices"><a
                            href="#services">Services</a></label>
                    <input type="radio" name="navLinks" value="packages" id="navpackages"><label
                        onclick="$('#navpackages').prop('checked', true)" for="navpackages"><a
                            href="#packages">Packages</a></label>
                    <input type="radio" name="navLinks" value="about" id="navabout"><label
                        onclick="$('#navabout').prop('checked', true)" for="navabout"><a
                            href="#aboutus">About</a></label>
                    <input type="radio" name="navLinks" value="contacts" id="navcontacts"><label
                        onclick="$('#navcontacts').prop('checked', true)" for="navcontacts"><a
                            href="#contacts">Contact</a></label>
                </div>
                <div class="rightnav">
                    <button id="regbtn" onclick="bulletBtn();">Register</button>
                    <div class="hline"></div>
                    <button id="logbtn" onclick="loginBtn();">Login</button>
                </div>
            </nav>
        </div>
        <section class="land">
            <div class="wall">
                <div class="upperlayer"></div>
                <div class="wallimg"></div>
                <div class="lineart" style="display: none !important;">
                    <div class="line1"></div>
                </div>
                <div class="container">
                    <div class="subcontainer">
                        <div class="text">
                            <div class="caption">LEVEL UP YOUR GAME.</div>
                            <div class="para">
                                Welcome to PixelVault, the premier PS5 Gaming Lounge experience! Immerse yourself in 4K HDR Next-Gen console setups, ultra-low latency fiber networking, ergonomic pro lounges, and top AAA multiplayer games.
                            </div>
                            <div class="buttons">
                                <div class="facebook"><i class="fa-brands fa-facebook-f"></i></div>
                                <div class="instagram">
                                    <a href="https://www.instagram.com/pixel_vault1011" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none; width: 100%; height: 100%; display: grid; place-items: center;">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                </div>
                                <div class="youtube"><i class="fa-brands fa-youtube"></i></div>
                                @auth
                                    <div class="contact"><a href="{{ url('/user') }}">Book Session</a></div>
                                @else
                                    <div class="contact"><a href="{{ url('/login') }}">Book Session</a></div>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="subcontainer2">
                        <div class="target" id="target">
                            <div class="circle"></div>
                        </div>
                        <div class="img"></div>
                        <div class="back">
                            <div class="box1 box"></div>
                            <div class="box2 box"></div>
                            <div class="box3 box"></div>
                            <div class="box4 box"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="dashboard" id="stations">
            <div class="caption">
                Gaming Lounge Overview
            </div>
            <div class="dashcontainer">
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-desktop"></i></div>
                    <div class="count" id="dashcomputer">30</div>
                    <div class="desc">Gaming Stations</div>
                </div>
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-gamepad"></i></div>
                    <div class="count" id="dashgame">50</div>
                    <div class="desc">Games</div>
                </div>
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-users"></i></div>
                    <div class="count" id="dashuser">100</div>
                    <div class="desc">Users</div>
                </div>
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="count" id="dashres">150</div>
                    <div class="desc">Reservations</div>
                </div>
            </div>
        </section>
        <section class="service" id="services">
            <div class="caption">
                Our Services
            </div>
            <div class="servicelist">
                <div class="card">
                    <i class="fa-solid fa-headset"></i>
                    <div class="title">
                        Play games
                    </div>
                    <div class="para">
                        Experience the ultimate gaming arena with top-tier PCs, high refresh rate displays, mechanical peripherals, and a vast library of competitive AAA titles.
                    </div>
                    {{-- <div class="button">
                        <button id="discoverbtn">Discover More</button>
                    </div> --}}
                </div>
                <div class="card">
                    <i class="fa-regular fa-credit-card"></i>
                    <div class="title">
                        Online Payment
                    </div>
                    <div class="para">
                        Seamless and secure booking. Book your gaming PC, reserve package slots, and pay online effortlessly with instant confirmation.
                    </div>
                    {{-- <div class="button">
                        <button id="discoverbtn">Discover More</button>
                    </div> --}}
                </div>
                <div class="card">
                    <i class="fa-solid fa-burger"></i>
                    <div class="title">
                        Foods
                    </div>
                    <div class="para">
                        Refuel while you game! Enjoy delicious hot snacks, burgers, energy drinks, and beverages delivered straight to your gaming station.
                    </div>
                    {{-- <div class="button">
                        <button id="discoverbtn">Discover More</button>
                    </div> --}}
                </div>
            </div>
        </section>
        <section class="packages" id="packages">
            <div class="caption">Our Packages</div>
            <div class="packageslist">
                <!-- <div class="packcard">
                    <div class="packprice"><span>Rs</span>150</div>
                    <div class="packname">Pack 1</div>
                    <div class="packdetails"></div>
                </div>
                <div class="packcard">
                    <div class="packname"></div>
                    <div class="packdetails"></div>
                </div>
                <div class="packcard">
                    <div class="packname"></div>
                    <div class="packdetails"></div>
                </div> -->
                <div class="packcard card1">
                    <div class="pricing">
                        <div class="price">
                            <sup>Rs</sup>
                            <span>150</span>
                        </div>
                        <p>Hourly</p>
                        <span class="type">1 Hour</span>
                    </div>
                    <div class="cardbody">
                        <div class="topshape"></div>
                        <div class="cardcontent">
                            <ul>
                                <li class="point active">1 Hour <i class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">Weekdays & Weekend <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Incress time limit <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Rs.125 for an extra hour <i
                                        class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">play any game <i class="fa-regular fa-circle-check"></i></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="packcard card2">
                    <div class="pricing">
                        <div class="price">
                            <sup>Rs</sup>
                            <span>150</span>
                        </div>
                        <p>Hourly</p>
                        <span class="type">1 Hour</span>
                    </div>
                    <div class="cardbody">
                        <div class="topshape"></div>
                        <div class="cardcontent">
                            <ul>
                                <li class="point active">1 Hour <i class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">Weekdays & Weekend <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Incress time limit <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Rs.125 for an extra hour <i
                                        class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">play any game <i class="fa-regular fa-circle-check"></i></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="packcard card3">
                    <div class="pricing">
                        <div class="price">
                            <sup>Rs</sup>
                            <span>150</span>
                        </div>
                        <p>Hourly</p>
                        <span class="type">1 Hour</span>
                    </div>
                    <div class="cardbody">
                        <div class="topshape"></div>
                        <div class="cardcontent">
                            <ul>
                                <li class="point active">1 Hour <i class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">Weekdays & Weekend <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Incress time limit <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Rs.125 for an extra hour <i
                                        class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">play any game <i class="fa-regular fa-circle-check"></i></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="aboutus" id="aboutus">
            <div class="caption">About PixelVault</div>
            <div class="about-desc">
                PixelVault is a next-generation gaming hub built for passion, performance, and community. We offer high-end gaming stations equipped with powerful hardware, ultra-fast fiber internet, and premium peripherals to give you the ultimate gaming edge. Whether you're practicing for esports tournaments or chilling with friends, PixelVault is your home.
            </div>
            <div class="about-grid">
                <div class="about-card">
                    <i class="fa-solid fa-microchip"></i>
                    <h3>High Performance Rigs</h3>
                    <p>Powered by high-spec Intel & RTX graphics paired with low-latency 240Hz gaming monitors.</p>
                </div>
                <div class="about-card">
                    <i class="fa-solid fa-wifi"></i>
                    <h3>Ultra-Fast Network</h3>
                    <p>Dedicated fiber-optic gigabit connection ensuring zero-lag multiplayer gaming.</p>
                </div>
                <div class="about-card">
                    <i class="fa-solid fa-couch"></i>
                    <h3>Comfort & RGB Vibe</h3>
                    <p>Ergonomic gaming chairs, clean setups, and immersive ambient lighting for long sessions.</p>
                </div>
                <div class="about-card">
                    <i class="fa-solid fa-trophy"></i>
                    <h3>Tournaments & Events</h3>
                    <p>Regular competitive gaming tournaments, community LAN battles, and leaderboards.</p>
                </div>
            </div>
        </section>
        <section class="contacts" id="contacts">
            <div class="caption">Contact</div>
            <div class="conoptions">
                <div class="concard">
                    <div class="address conop"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="text"><a href="https://maps.app.goo.gl/nVbSY5Pe4ovj8JNWA" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline;">View Shop on Google Maps</a></div>
                </div>
                <div class="concard">
                    <div class="num conop"><i class="fa-solid fa-phone"></i></div>
                    <div class="text">+91 9321495527</div>
                </div>
                <div class="concard">
                    <div class="email conop"><i class="fa-solid fa-envelope"></i></div>
                    <div class="text"><a href="mailto:pixelvault1011@gmail.com" style="color: inherit; text-decoration: underline;">pixelvault1011@gmail.com</a></div>
                </div>
            </div>
            <div class="condetails">
                <div class="conform">
                    <div class="row">
                        <input type="text" placeholder="Name">
                        <input type="text" placeholder="Email Address">
                    </div>
                    <input type="text" placeholder="Subject">
                    <textarea name="" id="" placeholder="Message"></textarea>
                    <button class="submit">Send Message
                        <!-- <i class="material-icons">send</i> -->
                    </button>
                </div>
                <div class="map" style="position: relative;">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://maps.google.com/maps?q=18.990737,73.124983&hl=en&z=15&output=embed"
                        frameborder="0" style="width:100%; height:100%; border:0;" allowfullscreen=""
                        aria-hidden="false" tabindex="0"></iframe>
                </div>
            </div>
        </section>

        <div class="footer">
            <!-- <div class="upperlayer"></div> -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1921.848" height="100" viewBox="0 0 1921.848 100">
                <path id="Path_1" data-name="Path 1"
                    d="M0,75.609s552.531,35.724,844.5,12.234S1127.238,50.651,1459.865,43.8,1921.848-4.158,1921.848-4.158H0Z"
                    transform="translate(0 4.158)" fill="var(--bgcolor)" />
            </svg>
            <div class="row">
                <div class="details">
                    <div class="title">
                        Address
                    </div>
                    C/5/1, kandy road,<br>
                    Gampaha.
                </div>
                <div class="details">
                    <div class="title">
                        About Us
                    </div>
                    <a href="#">Home</a><br>
                    <a href="#services">Services</a><br>
                    <a href="#packages">Packages</a><br>
                    <a href="#aboutus">About</a><br>
                    <a href="#contacts">Contact</a>
                </div>
                <div class="details">
                    <div class="title">
                        Services
                    </div>
                    Play games <br>
                    Online payments <br>
                    Foods
                </div>
                <div class="details">
                    <div class="title">
                        Services
                    </div>
                    C/5/1, kandy road,<br>
                    Gampaha.
                </div>
                <div class="details">
                    <div class="title">
                        Services
                    </div>
                    Play games<br>
                    Online Payment<br>
                    Foods <br>
                </div>
            </div>
            <div class="copyright">
                copyright © 2026 PixelVault
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