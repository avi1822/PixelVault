
var selectedpc;
var gamenextpage;
var gameprevpage;
function createHomePc() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/reservation/viewpopuler",
        success: function (response) {
            $(".adminarea .homedata .hdsubcontainer .computerselector .list").html("");
            for (var i in response) {
                $(".adminarea .homedata .hdsubcontainer .computerselector .list").append(`
                <div class="gamecard">
                    <img class="gameimg" src="/assets/img/pc.png">
                    <div class="name">pc - ${response[i].computer_id}</div>
                </div>
                `);
                console.log(i);
            }
            console.log(i);
            if (response.length < 6) {
                for (let ii = ++i; ii < 6; ii++) {
                    $(".adminarea .homedata .hdsubcontainer .computerselector .list").append(`
                        <div class="gamecard carddumy">
                        </div>
                    `);
                }
            }
            $(".loadercontainer").hide();
        }
    });
}
var currentZone = 'ground';
function cratePc(zone) {
    if (!zone) zone = currentZone;
    currentZone = zone;
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/computer/view",
        success: function (response) {
            $(".adminarea .computersdata .pcselector .pclist").html("");
            let filtered = [];
            if (zone === 'upper') {
                // Upper Floor Special Edition PS5 (Station #3)
                filtered = response.filter(item => item.cid == 3);
            } else {
                // Ground Floor PS5 Consoles (Station #1 & #2)
                filtered = response.filter(item => item.cid == 1 || item.cid == 2);
            }

            for (var i in filtered) {
                let cid = filtered[i].cid;
                let labelText = (cid == 3) 
                    ? `✨ Upper Floor VIP PS5 #3 (Ghost of Yōtei Edition + Cloud Lights)` 
                    : `🎮 Ground Floor PS5 #${cid}`;
                let imgUrl = (cid == 3) ? '/assets/img/ps5_ghost_yotei.jpg' : '/assets/img/ps5_ground.jpg';

                $(".adminarea .computersdata .pcselector .pclist").append(`
                <input type="radio" name="computer" value="${cid}" id="pc${cid}" ${(i == 0) ? 'checked' : ''}>
                <label for="pc${cid}" id="pcl${cid}" class="pccard" style="min-width: 220px; padding: 12px;">
                    <div class="pccardimg" style="background-image: url('${imgUrl}'); background-size: contain; background-repeat: no-repeat; background-position: center; height: 100px;"></div>
                    <div class="text" style="font-weight: bold; margin-top: 8px;">${labelText}</div>
                </label>
                `);
            }

            $('.adminarea .computersdata .pcselector .pclist input[name="computer"]').on("change", function () {
                displayPcDetails($(this).val());
            });
            if (filtered.length > 0) {
                displayPcDetails(filtered[0].cid);
            }
            $(".loadercontainer").hide();
        }
    });
}
function displayPcDetails(cid) {
    $(".loadercontainer").show();
    selectedpc = cid;
    $.ajax({
        type: "get",
        url: "/computer/viewoneimg",
        data: { "cid": cid },
        dataType: "json",
        success: function (response) {
            let heroImg = (cid == 3) ? '/assets/img/ps5_ghost_yotei.jpg' : '/assets/img/ps5_ground.jpg';
            $(".adminarea .subcontainer2 .computersdata .pc .pcimg").css({
                'background-image': `url('${heroImg}')`,
                'background-size': 'contain',
                'background-repeat': 'no-repeat',
                'background-position': 'center'
            });

            $("#pcspecul").html(`
            <li>🎮 Console: Sony PlayStation 5 (4K 120Hz HDR)</li>
            <li>⚡ Controllers: DualSense Wireless Controllers</li>
            <li>🎧 Headset: Tempest 3D Audio Surround Sound</li>
            <li>📺 Display: 55" 4K 120Hz OLED Gaming Screen</li>
            <li>✨ Environment: ${cid == 3 ? 'VIP Cloud Ambient Lighting Suite' : 'Ground Floor Gaming Arena'}</li>
            `);

            let titleStr = (cid == 3) ? `✨ Upper Floor Special Edition VIP PS5 #3` : `🎮 Ground Floor PS5 #${cid}`;
            $("#computername").html(titleStr);
            $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").html("");
            response[0].games.forEach(game => {
                $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").append(`<div class="game">
                <div class="gameimg">
                    <img src="${game.path}" alt="">
                </div>
                <div class="gamename">${game.name}</div>
            </div>`);
            });
            $(".loadercontainer").hide();
        }
    });
}
function crategames(pagenum) {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/game/partofdata",
        data: { "page": pagenum },
        dataType: "json",
        success: function (response) {
            $(".adminarea .subcontainer2 .gamesdata .datatcontainer .gameline").html("");
            for (var i in response.data) {
                $(".adminarea .subcontainer2 .gamesdata .datatcontainer .gameline").append(`
                <div class="gamecard">
                    <img loading="lazy" class="gameimg" src="${response.data[i].path}">
                    <div class="name">${response.data[i].name}</div>
                </div>
                `);
            }
            if (response.data.length < 18) {
                for (let ii = ++i; ii < 18; ii++) {
                    $(".adminarea .subcontainer2 .gamesdata .datatcontainer .gameline").append(`
                        <div class="gamecard carddumy">
                        </div>
                    `);
                }
            }
            var links = response.links.slice(1, -1)
            var innerline = $(".adminarea .subcontainer2 .gamesdata .datatcontainer .btnline .innerline");
            innerline.html("");
            innerline.append(`
                <button class="glbutton" id="gprevbtn" onclick="crategames(gameprevpage)">previus</button>
            `)
            for (var i in links) {
                innerline.append(`
                    <input type="radio" name="gamepage" id="gp${links[i].label}" value="${links[i].label}">
                    <label for="gp${links[i].label}">${links[i].label}</label>
                `)
            }
            innerline.append(`
                <button class="glbutton" id="gnextbtn" onclick="crategames(gamenextpage)">next</button>
            `)
            if (response.prev_page_url == null) {
                $("#gprevbtn").prop("disabled", true);
            } else {
                $("#gprevbtn").prop("disabled", false);
                gameprevpage = response.current_page - 1;
            }
            if (response.next_page_url == null) {
                $("#gnextbtn").prop("disabled", true);
            } else {
                $("#gnextbtn").prop("disabled", false);
                gamenextpage = response.current_page + 1;
            }
            $(".adminarea .subcontainer2 .gamesdata .innerline input[type='radio']").on("change", function () {
                crategames($(this).val());
            });
            $(`#gp${response.current_page}`).prop("checked", true);
            $(".loadercontainer").hide();
        }
    });
}
function creatLatestGames() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/game/getlatest",
        success: function (response) {
            $(".adminarea .homedata .hdsubcontainer .gameselector .list").html("");
            for (var i in response) {
                $(".adminarea .homedata .hdsubcontainer .gameselector .list").append(`
                <div class="gamecard">
                    <img class="gameimg" src="${response[i].path}">
                    <div class="name">${response[i].name}</div>
                </div>
                `);
            }
            if (response.length < 6) {
                for (let ii = ++i; ii < 6; ii++) {
                    $(".adminarea .homedata .hdsubcontainer .gameselector .list").append(`
                        <div class="gamecard carddumy">
                        </div>
                    `);
                }
            }
            $(".loadercontainer").hide();
        }
    });
}
function createPackage() {
    $.ajax({
        type: "get",
        url: "/package/viewall",
        success: function (response) {
            $(".pcbookarea .detail .pkg .pkglist").empty();
            let selectedCid = $("input[name='computer']:checked").val() || selectedpc || 1;
            let isUpper = (selectedCid == 3);

            for (var i in response) {
                let pkgtime = response[i].package_time;
                let gPrice = response[i].ground_floor_price || (pkgtime * 99);
                let uPrice = response[i].upper_floor_price || (pkgtime * 120);
                let displayPrice = isUpper ? uPrice : gPrice;
                let floorLabel = isUpper ? '✨ Upper Floor VIP' : '🎮 Ground Floor';

                $(".pcbookarea .detail .pkg .pkglist").append(`
                <input type="radio" name="mainpkg" value="${response[i].package_id}" id="pkg${response[i].package_id}">
                <label for="pkg${response[i].package_id}" class="pkgt">
                    <div class="pkgname">${response[i].package_name}</div>
                    <div class="details">${pkgtime} ${(pkgtime == 1) ? "hour" : "hours"} - <strong style="color: var(--secondc);">Rs. ${displayPrice}</strong> (${floorLabel})</div>
                </label>`);
            }
            $('.pcbookarea .detail .pkg .pkglist input[name="mainpkg"]').on("change", function () {
                getResPkgData(
                    $(this).val(),
                    $(".pcbookarea .pkg #date").val(),
                    $("input[name='computer']:checked").val()

                );
            });
        }
    });
}
function getResPkgData(pkg, date, pcid) {
    $(".loadercontainer").show();
    console.log(pkg, date, pcid);
    $.ajax({
        type: "get",
        url: "/reservation/respkgdata",
        data: { "packid": pkg, "date": date, "pcid": pcid },
        dataType: "json",
        success: function (response) {
            let pkgtime = response["package"][0].package_time;
            let availableTimes = response["availableTimes"];
            let isFullDayAvailable = response["isFullDayAvailable"];
            console.log(response);
            createTimeSolts(pkgtime, availableTimes, isFullDayAvailable);
            $(".loadercontainer").hide();
        }
    });
}
function createTimeSolts(pkgTime, availableTimes, isFullDayAvailable) {
    $(".pcbookarea .container .detail .time .timelist").html("");
    for (let i = 8; i < 20; i++) {
        if (i + pkgTime > 20) {
            break;
        }
        let isAvailable = isFullDayAvailable || availableTimes.includes(i);
        if (isAvailable) {
            $(".pcbookarea .container .detail .time .timelist").append(`
                <input type="radio" name="timeslot" value="${i}" id="tsid${i}">
                <label for="tsid${i}" class="timecard" style="border: 1px solid #51cf66; color: #51cf66; cursor: pointer;">🟢 ${i}:00 - ${i + pkgTime}:00</label>
            `);
        } else {
            $(".pcbookarea .container .detail .time .timelist").append(`
                <input type="radio" name="timeslot" value="${i}" id="tsid${i}" disabled style="display:none;">
                <label class="timecard booked-slot" style="border: 1px solid #5c2020; color: #888; background-color: rgba(255, 107, 107, 0.08); opacity: 0.5; cursor: not-allowed; text-decoration: line-through; pointer-events: none;">
                    🔴 BOOKED (${i}:00 - ${i + pkgTime}:00)
                </label>
            `);
        }
    }
}
function setEventCalender() {
    $.ajax({
        type: "get",
        url: "/reservation/geteventdetails",
        success: function (response) {
            resv = response.reservation
            $(".adminarea .homedata .datatcontainer .eventcalendar .events").html("");
            if (resv.length < 10) {
                let regdate = response.regdate.split("T")[0];
                let regtime = response.regdate.split("T")[1].split(".")[0];
                $(".adminarea .homedata .datatcontainer .eventcalendar .events").append(`
                <div class="eventcard">
                    <div class="eventstatus">
                        <div class="dot" id="eventreg"></div>
                    </div>
                    <div class="eventdetails">
                        <div class="eventname">Registered</div>
                        <div class="eventtime">${regdate} - ${regtime}</div>
                    </div>
                </div>
                `)
            }
            for (i in resv) {
                $(".adminarea .homedata .datatcontainer .eventcalendar .events").append(`
                <div class="eventcard">
                    <div class="eventstatus">
                        <div class="dot" id="event${resv[i].id}"></div>
                    </div>
                    <div class="eventdetails">
                        <div class="eventname">reservation</div>
                        <div class="eventtime">${resv[i].date} / ${resv[i].time} - pc${resv[i].computer_id}</div>
                    </div>
                </div>
                `)
                var today = new Date();
                var today = new Date(today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate());
                var resday = new Date(resv[i].date);
                console.log(today);
                if (resday > today) {
                    $(`#event${resv[i].id}`).css("background-color", "green");
                }
            }
            var d = $(".adminarea .homedata .datatcontainer .eventcalendar .events");
            d.scrollTop(d.prop("scrollHeight"));
        }
    });
}
function createProPics() {
    $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").html("");
    for (var i = 1; i < 22; i++) {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").append(`
            <input type="radio" name="propic" value="${i}" id="pp${i}">
            <label for="pp${i}" id="ppl${i}" class="propiccard">
                <img loading="lazy" src="/assets/img/propics/${i}.jpg"> 
            </label>       
        `);
    }
    setUserData();
}
function setUserData() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/user/viewone",
        success: function (response) {
            console.log(response);
            $("#fname").val(response[0].first_name);
            $("#lname").val(response[0].last_name);
            $("#uname").val(response[0].user_name);
            $("#pnumber").val(response[0].phone_number);
            $("#address").val(response[0].address);
            $("#email").val(response[0].email);
            $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("/assets/img/propics/${response[0].propic}.jpg")`);
            $(`#pp${response[0].propic}`).prop("checked", true);
            $("#dashmedle").css("background-image", `url("/assets/img/propics/${response[0].propic}.jpg")`);
            $(".adminarea .homedata .hdsubcontainer .dashboard .progress .memberType").html(`
                ${response[0].user_name} <span><i class="fa-solid fa-location-crosshairs" style="margin-right: 7px;"></i>${response.userType}</span>          
            `);
            console.log(response.reservations);
            $(".adminarea .homedata .hdsubcontainer .dashboard .progress .progressBarinner").css("width", `${response.reservations}%`);
            $(".adminarea .homedata .hdsubcontainer .dashboard .progress .progressNum").html(`${response.reservations}/100`);
            $(".adminarea .hiuser span").html(`${response[0].user_name}`);
            $(".loadercontainer").hide();
        }
    });
}
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    createProPics();
    createHomePc();
    setEventCalender();
    creatLatestGames();
    cratePc();
    crategames(1);
    createPackage();

    $(document).on('click', '#btnZoneGround', function (e) {
        e.preventDefault();
        $('.zone-btn').css({ background: 'var(--bgcolor3)', color: '#fff', border: '1px solid var(--secondc)' });
        $(this).css({ background: 'var(--secondc)', color: 'var(--bgcolor)', border: 'none' });
        cratePc('ground');
    });

    $(document).on('click', '#btnZoneUpper', function (e) {
        e.preventDefault();
        $('.zone-btn').css({ background: 'var(--bgcolor3)', color: '#fff', border: '1px solid var(--secondc)' });
        $(this).css({ background: 'var(--secondc)', color: 'var(--bgcolor)', border: 'none' });
        cratePc('upper');
    });
    $(".logo").on("click", function () {
        $("#mainsubcontainer").toggleClass("subcontainerwidth");
        $("#mainsubcontainer .ul .text").toggle(10);
        $("#mainlogo i").toggle(50);
        $("#mainlogo .text").toggle(50);
        if (!$("#mainsubcontainer").hasClass("subcontainerwidth")) {
            $("#mainlogo").animate({ width: "4%" }, 100);
            $("#mainsubcontainer").animate({ width: "4%" }, 100);
            $("#mainsubcontainer2").animate({ width: "96%" }, 100);
        } else {
            $("#mainlogo").animate({ width: "11.1%" }, 100);
            $("#mainsubcontainer").animate({ width: "12%" }, 100);
            $("#mainsubcontainer#").animate({ width: "88%" }, 100);
        };
    });
    $('.subcontainer input[name="slidmenu"]').on("change", function () {
        var caption = $(this).val();
        //$(".subcontainer2 .caption").html(caption);
        $(".subcontainer2 .homedata").hide();
        $(".subcontainer2 .reservationsdata").hide();
        $(".subcontainer2 .computersdata").hide();
        $(".subcontainer2 .packagesdata").hide();
        $(".subcontainer2 .gamesdata").hide();
        $(".subcontainer2 .settingsdata").hide();
        $(".subcontainer2 .billsdata").hide();
        $(".subcontainer2 .membershipdata").hide();
        $(".subcontainer2 .snacksdata").hide();
        $(`.subcontainer2 .${caption.toString().toLowerCase()}data`).show();
        if (caption.toString().toLowerCase() == "home") {
            $(".adminarea .container nav .hiuser").show();
        } else {
            $(".adminarea .container nav .hiuser").hide();
        }
        if (caption.toString().toLowerCase() == "bills") {
            loadUserInvoices();
        } else if (caption.toString().toLowerCase() == "membership") {
            loadUserMembership();
            loadMembershipPlans();
        } else if (caption.toString().toLowerCase() == "snacks") {
            loadUserSnacks();
        }
    });
    $(".settingsdata input[type='radio']").on("change", function () {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("/assets/img/propics/${$(this).val()}.jpg")`);
    });
    $(".computersdata .con2 .exp").on("click", function () {
        $(".computersdata .con2 .pcgames").toggleClass("maxpanel");
        if ($(".computersdata .con2 .pcgames").hasClass("maxpanel")) {
            $(".computersdata .con2 .pcgames").animate({ height: "0%" });
            $(".computersdata .con2 .pcspecs").animate({ height: "85%" });
            // $(this.lastChild).removeClass("fa-angle-down").addClass("fa-angle-up");

        } else {
            $(".computersdata .con2 .pcgames").animate({ height: "85%" });
            $(".computersdata .con2 .pcspecs").animate({ height: "0%" });
        }
    });
    $("#booknowbtn").on("click", function () {
        var cid = $("input[name='computer']:checked").val() || selectedpc || 1;
        var stName = (cid == 3) ? "✨ Upper Floor VIP PS5 #3 (Ghost of Yōtei Edition)" : `🎮 Ground Floor PS5 #${cid}`;
        var imgUrl = (cid == 3) ? '/assets/img/ps5_ghost_yotei.jpg' : '/assets/img/ps5_ground.jpg';

        createPackage();
        $(".pcbookarea").show();
        $(".pcbookarea .container .detail .pc .pcname").html(stName);
        $(".pcbookarea .container .detail .pc .pcimg").css({
            'background-image': `url('${imgUrl}')`,
            'background-size': 'contain',
            'background-repeat': 'no-repeat',
            'background-position': 'center'
        });
    });
    $("#closebtn").on("click", function () {
        $(".pcbookarea").hide();
    });
    $('.adminarea .subcontainer2 .settingsdata .propiclist input[type="radio"]').on("change", function () {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("/assets/img/propics/${$(this).val()}.jpg")`);
    });
    $("#updateProFileBtn").on("click", function () {
        $(".loadercontainer").show();
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var uname = $("#uname").val();
        var pnumber = $("#pnumber").val();
        var address = $("#address").val();
        var email = $("#email").val();
        var propic = $('.adminarea .subcontainer2 .settingsdata .propiclist input[type="radio"]:checked').val();
        $.ajax({
            type: "post",
            url: "/user/update",
            data: {
                "firstname": fname,
                "lastname": lname,
                "username": uname,
                "phonenumber": pnumber,
                "address": address,
                "email": email,
                "propic": propic,
            },
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    let mg = response.message;
                    $("#uufname").html("firstname" in mg ? mg.firstname[0] : "");
                    $("#uulname").html("lastname" in mg ? mg.lastname[0] : "");
                    $("#uuuname").html("username" in mg ? mg.username[0] : "");
                    $("#uupnumber").html("phonenumber" in mg ? mg.phonenumber[0] : "");
                    $("#uuaddress").html("address" in mg ? mg.address[0] : "");
                    $("#uuemail").html("email" in mg ? mg.email[0] : "");
                } else {
                    $(".adminarea .subcontainer2 .settingsdata .profiledata .error").html("");
                }
                $(".loadercontainer").hide();
            }
        });
    });
    $("#changePasswordBtn").on("click", function () {
        $(".loadercontainer").show();
        $.ajax({
            type: "post",
            url: "/user/updatep",
            data: {
                "oldpassword": $("#oldpassword").val(),
                "newpassword": $("#newpassword").val(),
                "newpassword_confirmation": $("#confirmPassword").val(),
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (!response.success) {
                    let mg = response.message;
                    $("#uuoldpass").html("oldpassword" in mg ? mg.oldpassword[0] : "");
                    $("#uunewpass").html("newpassword" in mg ? mg.newpassword[0] : "");
                    $("#uuconpass").html("newpassword_confirmation" in mg ? mg.newpassword_confirmation[0] : "");
                } else {
                    $(".adminarea .subcontainer2 .settingsdata .passwordcontainer .error").html("");
                }
                $(".loadercontainer").hide();
            }
        });
    });
    $("#dashmedle").on("click", function () {

    });

    $("#paynowbtn").on("click", function () {
        var time = $(".pcbookarea .time input[type='radio']:checked + label").html();
        var packid = $(".pcbookarea .pkg input[type='radio']:checked").val();
        var date = $(".pcbookarea #date").val();
        var pc = $("input[name='computer']:checked").val();
        var startime = $("input[name='timeslot']:checked").val();

        if (!date || !packid || !startime) {
            $("#booking_msg").html('<span style="color: #ff6b6b; font-weight: bold;"><i class="fa-solid fa-circle-exclamation"></i> Please select a Date, Package, and Available Time Slot!</span>');
            return;
        }

        $(".loadercontainer").show();
        $("#booking_msg").html("");

        $.ajax({
            type: "post",
            url: "/reservation/store",
            data: { 
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "time": time, 
                "date": date, 
                "packid": packid, 
                "pc": pc, 
                "start_time": startime 
            },
            dataType: "json",
            success: function (response) {
                $(".loadercontainer").hide();
                if (response.status === 'ok') {
                    $("#booking_msg").html('<span style="color: #51cf66; font-weight: bold;"><i class="fa-solid fa-circle-check"></i> Reservation Successful! Your station is booked.</span>');
                    setTimeout(() => {
                        $(".computersdata .pc").show();
                        $(".pcbookarea").hide();
                        $("#booking_msg").html("");
                        if (typeof dataTable !== 'undefined') dataTable.draw();
                        setEventCalender();
                    }, 2000);
                }
            },
            error: function (xhr) {
                $(".loadercontainer").hide();
                var msg = "Reservation failed. Please select another slot.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $("#booking_msg").html(`<span style="color: #ff6b6b; font-weight: bold;"><i class="fa-solid fa-circle-exclamation"></i> ${msg}</span>`);
            }
        });
    });
    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/reservation/userdata",
        columns: [
            { data: 'user_id', name: 'user_id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'date', name: 'date' },
            { data: 'time', name: 'time' },
            { data: 'computer_id', name: 'computer_id' },
            { data: 'package_id', name: 'package_id' },
        ]
    });
});

function loadUserInvoices() {
    $.ajax({
        type: "get",
        url: "/billing/userinvoices",
        dataType: "json",
        success: function (res) {
            $("#userInvoiceBody").empty();
            if (res.length === 0) {
                $("#userInvoiceBody").html('<tr><td colspan="6" style="text-align:center; color:#aaa;">No invoices found.</td></tr>');
                return;
            }
            res.forEach(inv => {
                let stColor = (inv.status === 'PAID') ? '#51cf66' : (inv.status === 'PARTIALLY_PAID' ? '#fcc419' : '#ff6b6b');
                $("#userInvoiceBody").append(`
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <td style="padding: 10px;"><strong>${inv.invoice_number}</strong></td>
                        <td style="padding: 10px;">${new Date(inv.issued_at).toLocaleDateString()}</td>
                        <td style="padding: 10px;">Rs.${inv.total}</td>
                        <td style="padding: 10px; color:#51cf66;">Rs.${inv.paid_amount}</td>
                        <td style="padding: 10px;"><span style="color:${stColor}; font-weight:bold;">${inv.status}</span></td>
                        <td style="padding: 10px;"><button onclick="viewInvoiceReceipt(${inv.id})" style="background:var(--secondc); color:var(--bgcolor); border:none; padding:5px 10px; border-radius:4px; font-weight:bold; cursor:pointer;">View Receipt</button></td>
                    </tr>
                `);
            });
        }
    });
}

function loadUserMembership() {
    $.ajax({
        type: "get",
        url: "/membership/mymembership",
        dataType: "json",
        success: function (res) {
            if (res.active) {
                let m = res.active;
                let hrs = Math.floor(m.gaming_minutes_remaining / 60);
                let mins = m.gaming_minutes_remaining % 60;

                $('#mem_plan_name').text(m.plan ? m.plan.name : 'VIP Member');
                $('#mem_hours_left').text(`${hrs}h ${mins}m`);
                $('#mem_discount_pct').text(`${m.discount_percent}% OFF`);
                $('#mem_expires_at').text(new Date(m.expires_at).toLocaleDateString());
                $('#mem_badge_status').text(m.status).css({ background: '#51cf66', color: '#000' });
            } else {
                $('#mem_plan_name').text('NO ACTIVE PLAN');
                $('#mem_hours_left').text('0h 0m');
                $('#mem_discount_pct').text('0%');
                $('#mem_expires_at').text('N/A');
                $('#mem_badge_status').text('NO MEMBERSHIP').css({ background: '#ff6b6b', color: '#fff' });
            }
        }
    });
}

function loadMembershipPlans() {
    $.ajax({
        type: "get",
        url: "/membership/plans",
        dataType: "json",
        success: function (res) {
            $('#membershipPlansGrid').empty();
            res.forEach(plan => {
                let borderCol = (plan.name === 'PLATINUM') ? '#e599f7' : ((plan.name === 'GOLD') ? '#fcc419' : '#adb5bd');
                $('#membershipPlansGrid').append(`
                    <div style="background: var(--bgcolor3); border: 1px solid ${borderCol}; border-radius: 10px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; text-align: center;">
                        <div>
                            <div style="font-size: 0.85rem; color: ${borderCol}; font-weight: bold; letter-spacing: 1px;">${plan.name} PLAN</div>
                            <div style="font-size: 2rem; font-weight: bold; color: #fff; margin: 10px 0;">Rs.${plan.price}</div>
                            <div style="font-size: 0.85rem; color: #aaa; margin-bottom: 15px;">${plan.description || ''}</div>
                            <ul style="list-style: none; padding: 0; margin: 0 0 20px 0; text-align: left; font-size: 0.9rem; color: #ddd; display: flex; flex-direction: column; gap: 8px;">
                                <li><i class="fa-solid fa-check" style="color:#51cf66;"></i> <strong>${plan.gaming_hours} Gaming Hours</strong></li>
                                <li><i class="fa-solid fa-check" style="color:#51cf66;"></i> <strong>${plan.gaming_discount_percent}% Discount</strong> on all gaming</li>
                                <li><i class="fa-solid fa-check" style="color:#51cf66;"></i> <strong>${plan.duration_days} Days</strong> Validity</li>
                            </ul>
                        </div>
                        <button class="btn-buy-plan" data-plan-id="${plan.id}" data-plan-name="${plan.name}" style="background: ${borderCol}; color: #000; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                            👑 Subscribe Now
                        </button>
                    </div>
                `);
            });
        }
    });
}

$(document).on('click', '.btn-buy-plan', function () {
    var planId = $(this).data('plan-id');
    var planName = $(this).data('plan-name');

    if (confirm(`Subscribe to ${planName} Membership Plan?`)) {
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/membership/purchase",
            data: { plan_id: planId },
            dataType: "json",
            success: function (res) {
                $(".loadercontainer").hide();
                alert(res.message);
                loadUserMembership();
                loadUserInvoices();
            },
            error: function (xhr) {
                $(".loadercontainer").hide();
                let msg = "Failed to purchase plan.";
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    }
});

function loadUserSnacks() {
    $.ajax({
        type: "get",
        url: "/inventory/products",
        dataType: "json",
        success: function (res) {
            $('#userSnacksGrid').empty();
            res.forEach(prod => {
                let isOut = (prod.stock_quantity <= 0 || prod.status === 'OUT_OF_STOCK');
                let catName = prod.category ? prod.category.name : 'Snacks';
                $('#userSnacksGrid').append(`
                    <div style="background: var(--bgcolor3); border: 1px solid var(--secondc); border-radius: 10px; padding: 15px; display: flex; flex-direction: column; justify-content: space-between; text-align: center;">
                        <div>
                            <div style="font-size: 0.75rem; color: var(--secondc); font-weight: bold; text-transform: uppercase;">${catName}</div>
                            <div style="font-size: 1.1rem; font-weight: bold; color: #fff; margin: 8px 0;">${prod.name}</div>
                            <div style="font-size: 1.3rem; font-weight: bold; color: #51cf66;">Rs.${prod.selling_price}</div>
                            <div style="font-size: 0.8rem; color: ${isOut ? '#ff6b6b' : '#aaa'}; margin: 5px 0;">
                                ${isOut ? '❌ OUT OF STOCK' : 'In Stock: ' + prod.stock_quantity}
                            </div>
                        </div>
                        <button class="btn-order-snack" data-id="${prod.id}" data-name="${prod.name}" ${isOut ? 'disabled' : ''} style="background: ${isOut ? '#495057' : 'var(--secondc)'}; color: ${isOut ? '#aaa' : 'var(--bgcolor)'}; border: none; padding: 8px; border-radius: 6px; font-weight: bold; cursor: ${isOut ? 'not-allowed' : 'pointer'}; margin-top: 10px;">
                            ${isOut ? 'Sold Out' : '🛒 Order Now'}
                        </button>
                    </div>
                `);
            });
        }
    });
}

$(document).on('click', '.btn-order-snack', function () {
    var prodId = $(this).data('id');
    var prodName = $(this).data('name');

    if (confirm(`Order ${prodName}? Invoice will be generated.`)) {
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/inventory/order",
            data: {
                product_id: prodId,
                quantity: 1
            },
            dataType: "json",
            success: function (res) {
                $(".loadercontainer").hide();
                alert(res.message);
                loadUserSnacks();
                loadUserInvoices();
            },
            error: function (xhr) {
                $(".loadercontainer").hide();
                let msg = "Failed to place F&B order.";
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    }
});

/* Live Session Status Tracker for Customer Dashboard */
function checkMyActiveSession() {
    $.ajax({
        type: "GET",
        url: "/session/my-active",
        dataType: "json",
        success: function (res) {
            var card = $('#userActiveSessionCard');
            if (!res.has_active_session) {
                card.hide().empty();
                return;
            }

            var remMins = Math.floor(res.remaining_seconds / 60);
            var remSecs = res.remaining_seconds % 60;
            var timeStr = `${remMins}m ${remSecs}s`;

            var isWarning = res.is_10mins_warning;
            var isExpired = res.is_expired;

            var borderCol = isExpired ? '#ff6b6b' : (isWarning ? '#ff922b' : 'var(--secondc)');
            var badgeText = isExpired ? '🔴 SESSION EXPIRED' : (isWarning ? '⚠️ 10 MINS REMAINING!' : '🟢 SESSION LIVE');
            var bgTint = isWarning ? 'rgba(255, 146, 43, 0.15)' : 'rgba(168, 85, 247, 0.1)';

            card.css({
                display: 'block',
                borderColor: borderCol,
                background: bgTint
            }).html(`
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="margin: 0; color: var(--secondc);"><i class="fa-solid fa-gamepad"></i> Your Active Gaming Session</h3>
                    <span style="background: ${borderCol}; color: #000; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem;">${badgeText}</span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; margin-top: 10px;">
                    <div>
                        <div style="font-size: 0.8rem; color: #aaa;">Station</div>
                        <div style="font-size: 1.2rem; font-weight: bold;">${res.station_name}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: #aaa;">Started At</div>
                        <div style="font-size: 1.1rem; color: #fff;">${new Date(res.started_at).toLocaleTimeString()}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: #aaa;">Time Remaining</div>
                        <div style="font-size: 1.4rem; font-weight: bold; color: ${isExpired ? '#ff6b6b' : (isWarning ? '#ff922b' : '#339af0')};">
                            ⏱ ${isExpired ? '00m 00s' : timeStr}
                        </div>
                    </div>
                </div>
                ${isWarning ? `<div style="margin-top: 12px; color: #ff922b; font-weight: bold; text-align: center; font-size: 0.95rem;">
                    ⚠️ Your gaming session is ending in less than 10 minutes! Please wrap up your game or request an extension from the admin.
                </div>` : ''}
            `);
        }
    });
}

$(document).ready(function () {
    checkMyActiveSession();
    setInterval(checkMyActiveSession, 5000);
});