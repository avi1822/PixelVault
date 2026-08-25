
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
var currentZone = 'pc';
function cratePc(zone) {
    if (!zone) zone = currentZone;
    currentZone = zone;
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/computer/view",
        success: function (response) {
            $(".adminarea .computersdata .pcselector .pclist").html("");
            let filtered = response;
            if (zone === 'ps5') {
                filtered = response.slice(0, 5);
            }
            for (var i in filtered) {
                let labelText = (zone === 'ps5') ? `PS5 - 0${parseInt(i)+1}` : `PC - ${filtered[i].cid}`;
                $(".adminarea .computersdata .pcselector .pclist").append(`
                <input type="radio" name="computer" value="${filtered[i].cid}" id="pc${filtered[i].cid}" ${(i == 0) ? 'checked' : ''}>
                <label for="pc${filtered[i].cid}" id="pcl${filtered[i].cid}" class="pccard">
                                <div class="pccardimg"></div>
                                <div class="text">${labelText}</div>
                </label>
                `);
            }
            for (var i = 0; i < 2; i++) {
                $(".adminarea .computersdata .pcselector .pclist").append('<div class="dumy"></div>');
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
            $("#pcspecul").html(`
            <li>${response[0].spec1}</li>
            <li>${response[0].spec2}</li>
            <li>${response[0].spec3}</li>
            <li>${response[0].spec4}</li>
            <li>${response[0].spec5}</li>
            <li>${response[0].spec6}</li>
            <li>${response[0].spec7}</li>
            `);
            $("#computername").html(`PC - ${cid}`);
            $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").html("");
            response[0].games.forEach(game => {
                $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").append(`<div class="game">
                <div class="gameimg">
                    <img src="${game.path}"
                        alt="">
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
            for (var i in response) {
                let pkgtime = response[i].package_time
                $(".pcbookarea .detail .pkg .pkglist").append(`
                <input type="radio" name="mainpkg" value="${response[i].package_id}" id="pkg${response[i].package_id}">
                <label for="pkg${response[i].package_id}" class="pkgt">
                    <div class="pkgname">${response[i].package_name}</div>
                    <div class="details">${pkgtime}${(pkgtime == 1) ? "hour" : "hours"} - Rs${response[i].package_price}</div>
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
                <label for="tsid${i}" class="timecard" style="border: 1px solid #51cf66; color: #51cf66;">🟢 ${i}:00 - ${i + pkgTime}:00</label>
            `);
        } else {
            $(".pcbookarea .container .detail .time .timelist").append(`
                <input type="radio" name="timeslot" value="${i}" id="tsid${i}" disabled>
                <label for="tsid${i}" class="timecard" style="border: 1px solid #ff6b6b; color: #ff6b6b; opacity: 0.6; cursor: not-allowed;">🔴 BOOKED (${i}:00 - ${i + pkgTime}:00)</label>
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

    $('#btnZonePC').on('click', function () {
        $('.zone-btn').css({ background: 'var(--bgcolor3)', color: '#fff', border: '1px solid var(--secondc)' });
        $(this).css({ background: 'var(--secondc)', color: 'var(--bgcolor)', border: 'none' });
        cratePc('pc');
    });

    $('#btnZonePS5').on('click', function () {
        $('.zone-btn').css({ background: 'var(--bgcolor3)', color: '#fff', border: '1px solid var(--secondc)' });
        $(this).css({ background: 'var(--secondc)', color: 'var(--bgcolor)', border: 'none' });
        cratePc('ps5');
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
        $(`.subcontainer2 .${caption.toString().toLowerCase()}data`).show();
        if (caption.toString().toLowerCase() == "home") {
            $(".adminarea .container nav .hiuser").show();
        } else {
            $(".adminarea .container nav .hiuser").hide();
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
        $(".computersdata .pc").hide();
        $(".pcbookarea").show();
        $(".pcbookarea .container .detail .pc .pcname").html(`pc - ${$("input[name='computer']:checked").val()}`);
    });
    $("#closebtn").on("click", function () {
        $(".computersdata .pc").show();
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