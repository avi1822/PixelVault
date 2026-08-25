var selectedpc;
var gameid;
var gamearray = [];

var currentStatusFilter = "";

function cratePc(statusFilter) {
    if (statusFilter !== undefined) currentStatusFilter = statusFilter;
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/computer/view",
        data: { "status": currentStatusFilter },
        success: function (response) {
            $(".adminarea .subcontainer2 .computersdata .con .pcline").html("");
            for (var i in response) {
                let st = response[i].status || 'AVAILABLE';
                let borderCol = '#51cf66';
                if (st === 'RESERVED') borderCol = '#cc5de8';
                else if (st === 'PLAYING') borderCol = '#339af0';
                else if (st === 'MAINTENANCE') borderCol = '#fcc419';
                else if (st === 'OFFLINE') borderCol = '#ff6b6b';

                $(".adminarea .subcontainer2 .computersdata .con .pcline").append(
                    `<input type="radio" name="computer" value="${response[i].cid}" id="pc${response[i].cid}">
                    <label for="pc${response[i].cid}" id="pcl${response[i].cid}" class="pc" style="border: 2px solid ${borderCol}; shadow: 0 0 5px ${borderCol};">${response[i].cid}</label>`
                );
            }
            $('.subcontainer2 .computersdata .pcline input[name="computer"]').on("change", function () {
                displayPcDetails($(this).val());
            });
            if (response.length > 0) {
                displayPcDetails(response[0].cid);
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
        url: "/computer/viewone",
        data: { "cid": cid },
        dataType: "json",
        success: function (response) {
            if (response.length > 0) {
                $("#pcspec1").val(response[0].spec1);
                $("#pcspec2").val(response[0].spec2);
                $("#pcspec3").val(response[0].spec3);
                $("#pcspec4").val(response[0].spec4);
                $("#pcspec5").val(response[0].spec5);
                $("#pcspec6").val(response[0].spec6);
                $("#pcspec7").val(response[0].spec7);
                let st = response[0].status || 'AVAILABLE';
                $("#stationStatusSelect").val(st);
                let labelText = (cid <= 5) ? `PS5 Lounge #${cid}` : `PC Arena #${cid}`;
                $("#computername").html(`${labelText} (${st})`);

                gamearray = [];
                $("input:checkbox[name='pcgame']").prop("checked", false);
                response[0].games.forEach(game => {
                    $(`#pcgame${game.id}`).prop("checked", true);
                    gamearray.push(game.id);
                });
            }
            $(".loadercontainer").hide();
        }
    });
}
function crategame() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/game/view",
        success: function (response) {
            for (var i in response) {
                $(".adminarea .subcontainer2 .computersdata .con2 .pcgames").append(`<input type="checkbox" name="pcgame" value="${response[i].id}" id="pcgame${response[i].id}"}>
         <label for="pcgame${response[i].id}" id="pcgamel${response[i].id}">${response[i].name}</label>`);
            }
            $(".loadercontainer").hide();
        }
    });
}
function checkPack() {
    $(document).on("change", '.adminarea .subcontainer2 .packagesdata .pkg .pkglist input[name="mainpkg"]', function () {
        $(".loadercontainer").show();
        console.log($(this).val());
        $.ajax({
            type: "get",
            url: "/package/viewone",
            data: { "packid": $(this).val() },
            dataType: "json",
            success: function (response) {
                console.log(response);
                $("#packname").val(response[0].package_name);
                $("#packtime").val(response[0].package_time);
                $("#packprice").val(response[0].package_price);
                $(".loadercontainer").hide();
            }
        });
    });
}
function createPackage() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "/package/viewall",
        success: function (response) {
            for (var i in response) {
                let pkgtime = response[i].package_time;
                $(".adminarea .container .admin .subcontainer2 .packagesdata .con .pkg .pkglist").append(`
                <input type="radio" name="mainpkg" value="${response[i].package_id}" id="pkg${response[i].package_id}">
                <label for="pkg${response[i].package_id}" class="pkgt">
                    <div class="pkgname">${response[i].package_name}</div>
                    <div class="details">${pkgtime}${(pkgtime == 1) ? "hour" : "hours"} - Rs${response[i].package_price}</div>
                </label>`);
            }
            checkPack();
            $(".loadercontainer").hide();
        }
    });
}
function createProPics() {
    $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").html("");
    for (var i = 1; i < 22; i++) {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").append(`
            <input type="radio" name="propic" value="${i}" id="pp${i}">
            <label for="pp${i}" id="ppl${i}" class="propiccard">
                <img src="/assets/img/propics/${i}.jpg"> 
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
            $("#fname").val(response[0].first_name);
            $("#lname").val(response[0].last_name);
            $("#uname").val(response[0].user_name);
            $("#pnumber").val(response[0].phone_number);
            $("#address").val(response[0].address);
            $("#email").val(response[0].email);
            $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("/assets/img/propics/${response[0].propic}.jpg")`);
            $(`#pp${response[0].propic}`).prop("checked", true);
            $(".loadercontainer").hide();
        }
    });
}
$(document).ready(function () {
    cratePc();
    crategame();
    createPackage();
    createProPics();
    updateStationCounts();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.st-filter-btn').on('click', function () {
        $('.st-filter-btn').css({ background: 'var(--bgcolor3)', color: '#fff', border: '1px solid var(--secondc)' });
        $(this).css({ background: 'var(--secondc)', color: 'var(--bgcolor)', border: 'none' });
        let st = $(this).data('status');
        cratePc(st);
    });

    $('#saveStatusBtn').on('click', function () {
        if (!selectedpc) {
            alert("Please select a Gaming Station first!");
            return;
        }
        let newStatus = $('#stationStatusSelect').val();
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/computer/status",
            data: { "cid": selectedpc, "status": newStatus },
            dataType: "json",
            success: function (response) {
                $(".loadercontainer").hide();
                if (response.success) {
                    alert(`Station #${selectedpc} status updated to ${newStatus}!`);
                    cratePc();
                } else if (response.warning) {
                    if (confirm(response.message)) {
                        $(".loadercontainer").show();
                        $.ajax({
                            type: "POST",
                            url: "/computer/status",
                            data: { "cid": selectedpc, "status": newStatus, "confirm": true },
                            dataType: "json",
                            success: function (res2) {
                                $(".loadercontainer").hide();
                                alert(`Station #${selectedpc} status updated to ${newStatus}!`);
                                cratePc();
                            }
                        });
                    }
                }
            },
            error: function (xhr) {
                $(".loadercontainer").hide();
                let msg = "Failed to update station status.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = typeof xhr.responseJSON.message === 'string' ? xhr.responseJSON.message : JSON.stringify(xhr.responseJSON.message);
                }
                alert(msg);
            }
        });
    });

    $('.subcontainer input[name="slidmenu"]').on("change", function () {
        var caption = $(this).val();
        $(".subcontainer2 .caption").html(caption);
        $(".subcontainer2 .reservationsdata").hide();
        $(".subcontainer2 .usersdata").hide();
        $(".subcontainer2 .computersdata").hide();
        $(".subcontainer2 .packagesdata").hide();
        $(".subcontainer2 .gamesdata").hide();
        $(".subcontainer2 .settingsdata").hide();
        $(".subcontainer2 .visitorsdata").hide();
        $(".subcontainer2 .sessionsdata").hide();
        $(`.subcontainer2 .${caption.toString().toLowerCase()}data`).show();
        if (caption === "Visitors") {
            populateVisitorGames();
            loadVisitorAnalytics();
        } else if (caption === "Sessions") {
            loadActiveSessions();
            if (typeof sessionHistoryTable !== 'undefined') sessionHistoryTable.draw();
        }
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
    $("#newgamebtn").on("click", function () {
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/game/store",
            data: { "name": $("#gamename").val(), "imagelink": $("#gameurl").val() },
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    let mg = response.message;
                    $("#gamenameerror").html("name" in mg ? mg.name[0] : "");
                    $("#gameurlerror").html("imagelink" in mg ? mg.imagelink[0] : "");
                }
                console.log(response);
                $(".loadercontainer").hide();
            }
        });
    });
    $("#updategamebtn").on("click", function () {
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/game/update",
            data: { "gameid": gameid, "name": $("#gamename").val(), "imagelink": $("#gameurl").val() },
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    let mg = response.message;
                    $("#gamenameerror").html("name" in mg ? mg.name[0] : "");
                    $("#gameurlerror").html("imagelink" in mg ? mg.imagelink[0] : "");
                }
                console.log(response);
                $(".loadercontainer").hide();
            }
        });
    });
    $("#updatepcbtn").on("click", function () {
        $(".loadercontainer").show();
        var gamearraytemp = [];
        $("input:checkbox[name='pcgame']:checked").each(function () {
            gamearraytemp.push(parseInt($(this).val()));
        });
        var newgamearray = gamearraytemp.filter(function (val) {
            return gamearray.indexOf(val) == -1;
        });
        var delgamearray = gamearray.filter(item => gamearraytemp.indexOf(item) == -1);
        gamearray.push(...newgamearray);

        $.ajax({
            type: "post",
            url: "/computer/update",
            data: {
                "cid": $('.subcontainer2 .computersdata .pcline input[name="computer"]:checked').val(),
                "spec1": $("#pcspec1").val(),
                "spec2": $("#pcspec2").val(),
                "spec3": $("#pcspec3").val(),
                "spec4": $("#pcspec4").val(),
                "spec5": $("#pcspec5").val(),
                "spec6": $("#pcspec6").val(),
                "spec7": $("#pcspec7").val(),
                "games": JSON.stringify(newgamearray),
                "delgames": JSON.stringify(delgamearray),
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (!response.success) {
                    var mg = response.message;
                    for (var i = 1; i < 8; i++) {
                        if (`spec${i}` in mg) {
                            $(`#pcspec${i}`).css("border", "1px solid rgba(150, 0, 0, 100)");
                        } else {
                            $(`#pcspec${i}`).css("border", "none");
                        }
                    }
                }
                $(".loadercontainer").hide();
            }
        });
    });
    $("#newpcbtn").on("click", function () {
        $(".loadercontainer").show();
        var newgamearray = [];
        $("input:checkbox[name='pcgame']:checked").each(function () {
            newgamearray.push($(this).val());
        });
        console.log("---", $("#pcspec1").val(), "---", "---")
        $.ajax({
            type: "post",
            url: "/computer/store",
            data: {
                "spec1": $("#pcspec1").val(),
                "spec2": $("#pcspec2").val(),
                "spec3": $("#pcspec3").val(),
                "spec4": $("#pcspec4").val(),
                "spec5": $("#pcspec5").val(),
                "spec6": $("#pcspec6").val(),
                "spec7": $("#pcspec7").val(),
                "games": JSON.stringify(newgamearray),
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(".adminarea .subcontainer2 .computersdata .con .pcline").append(`<input type="radio" name="computer" value="${response.cid}" id="pc${response.cid}">
                     <label for="pc${response.cid}" id="pcl${response.cid}" class="pc">${response.cid}</label>`);
                    $('.subcontainer2 .computersdata .pcline input[name="computer"]').on("change", function () {
                        displayPcDetails($(this).val());
                    });
                } else {
                    var mg = response.message;
                    for (var i = 1; i < 8; i++) {
                        if (`spec${i}` in mg) {
                            $(`#pcspec${i}`).css("border", "1px solid rgba(150, 0, 0, 100)");
                        } else {
                            $(`#pcspec${i}`).css("border", "none");
                        }
                    }
                }
                console.log(response);
                $(".loadercontainer").hide();
            }
        });

    });
    $("#deletepcbtn").on("click", function () {
        $(".loadercontainer").show();
        console.log(selectedpc);
        $.ajax({
            type: "get",
            url: "/computer/delete",
            data: { "cid": selectedpc },
            dataType: "json",
            success: function (response) {
                console.log(response.cid);
                $(`.adminarea .subcontainer2 .computersdata .con .pcline #pc${response.cid}`).remove()
                $(`.adminarea .subcontainer2 .computersdata .con .pcline #pcl${response.cid}`).remove();
                $(".loadercontainer").hide();
            }
        });
    });

    $("#gameurl").on("keydown", function search(e) {
        if (e.keyCode == 13) {
            $(".loadercontainer").show();
            $(".adminarea .admin .subcontainer2 .gamesdata .con2 .img").css("background-image", `url("${$("#gameurl").val()}")`);
            $(".loadercontainer").hide();
        }
    });


    $("#delgamebtn").on("click", function () {
        $(".loadercontainer").show();
        $.ajax({
            type: "post",
            url: "/game/delete",
            data: { "id": gameid },
            dataType: "json",
            success: function (response) {
                $(".loadercontainer").hide();
                console.log(response)
            }
        });
    });

    $("#delpackbtn").on("click", function () {
        $(".loadercontainer").show();
        var packid = $(".adminarea .subcontainer2 .packagesdata .pkg .pkglist input[name='mainpkg']:checked").val();
        $.ajax({
            type: "post",
            url: "/package/delete",
            data: { "packid": packid },
            dataType: "json",
            success: function (response) {
                $(`.pkglist #pkg${response.package_id}`).remove();
                $(`.pkglist label[for=pkg${response.package_id}]`).remove();
                $(".loadercontainer").hide();
                console.log(response);
            }
        });
    });
    $("#updatepackbtn").on("click", function () {
        $(".loadercontainer").show();
        var packid = $(".adminarea .subcontainer2 .packagesdata .pkg .pkglist input[name='mainpkg']:checked").val();
        var packname = $("#packname").val();
        var packtime = $("#packtime").val();
        var packprice = $("#packprice").val();
        $.ajax({
            type: "post",
            url: "/package/update",
            data: { "packid": packid, "packname": packname, "packtime": packtime, "packprice": packprice },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`.pkglist label[for=pkg${packid}] .pkgname`).html(packname);
                    $(`.pkglist label[for=pkg${packid}] .details`).html(`${packtime}${(packtime == 1) ? "hour" : "hours"} - Rs${packprice}`);
                } else {
                    let mg = response.message;
                    $("#packnameerror").html("packname" in mg ? mg.packname[0] : "");
                    $("#packtimeerror").html("packtime" in mg ? mg.packtime[0] : "");
                    $("#packpriceerror").html("packprice" in mg ? mg.packprice[0] : "");
                }
                $(".loadercontainer").hide();
                console.log(response);
            }
        });
    });
    $("#newpackbtn").on("click", function () {
        $(".loadercontainer").show();
        var packname = $("#packname").val();
        var packtime = $("#packtime").val();
        var packprice = $("#packprice").val();
        $.ajax({
            type: "post",
            url: "/package/store",
            data: { "packname": packname, "packtime": packtime, "packprice": packprice },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.success) {
                    let pkgtime = response.package_time;
                    $(".adminarea .container .admin .subcontainer2 .packagesdata .con .pkg .pkglist").append(`
                        <input type="radio" name="mainpkg" value="${response.package_id}" id="pkg${response.package_id}">
                        <label for="pkg${response.package_id}" class="pkgt">
                        <div class="pkgname">${response.package_name}</div>
                        <div class="details">${pkgtime}${(pkgtime == 1) ? "hour" : "hours"} - Rs${response.package_price}</div>
                    </label>`);
                } else {
                    let mg = response.message;
                    $("#packnameerror").html("packname" in mg ? mg.packname[0] : "");
                    $("#packtimeerror").html("packtime" in mg ? mg.packtime[0] : "");
                    $("#packpriceerror").html("packprice" in mg ? mg.packprice[0] : "");
                }
                $(".loadercontainer").hide();
            }
        });
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
                }else{
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
                }else{
                    $(".adminarea .subcontainer2 .settingsdata .passwordcontainer .error").html("");
                }
                $(".loadercontainer").hide();
            }
        });
    });
    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/reservation/anydata",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'date', name: 'date' },
            { data: 'time', name: 'time' },
            { data: 'computer_id', name: 'computer_id' },
            { data: 'package_id', name: 'package_id' },
            {
                data: 'id',
                render: function (data, type, row) {
                    return `<button class="btn-start-session" data-res-id="${data}" style="background:#51cf66; color:#000; border:none; padding:6px 12px; border-radius:4px; font-weight:bold; cursor:pointer;">▶ Start Session</button>`;
                }
            }
        ]
    });

    var sessionHistoryTable = $('#sessionHistoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/session/anydata",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'station_label', name: 'station_label' },
            { data: 'started_at', name: 'started_at' },
            { data: 'ended_at', name: 'ended_at' },
            { data: 'duration_minutes', name: 'duration_minutes' },
            {
                data: 'status',
                render: function (data) {
                    let col = (data === 'ACTIVE') ? '#339af0' : (data === 'COMPLETED' ? '#51cf66' : '#ff6b6b');
                    return `<span style="color:${col}; font-weight:bold;">${data}</span>`;
                }
            }
        ]
    });
    var userTable = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/registration/anydata",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'user_name', name: 'user_name' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'address', name: 'address' },
            { data: 'email', name: 'email' },
        ]
    });
    var gamedataTable = $('#gamedataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/game/anydata",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
        ],
        slect: true,
    }
    );
    $('#gamedataTable tbody').on('click', 'tr', function () {
        $(".loadercontainer").show();
        gameid = $(this).children("td").eq(0).html();
        var gamename = $(this).children("td").eq(1).html();
        $("#gamename").val(gamename);
        $.ajax({
            type: "get",
            url: "/game/viewone",
            data: { "id": gameid },
            dataType: "json",
            success: function (response) {
                console.log(response);
                $(".adminarea .admin .subcontainer2 .gamesdata .con2 .img").css("background-image", `url("${response[0].path}")`);
                $("#gameurl").val(`${response[0].path}`);
                $(".loadercontainer").hide();
            }
        });
    });

    if ($('#v_date').length) {
        $('#v_date').val(new Date().toISOString().split('T')[0]);
    }

    var visitorTable = $('#visitorTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/visitor/anydata",
            data: function (d) {
                d.filter_date = $('#filter_date').val();
                d.filter_month = $('#filter_month').val();
                d.filter_year = $('#filter_year').val();
                d.filter_zone = $('#filter_zone').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'visitor_name', name: 'visitor_name' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'entry_date', name: 'entry_date' },
            { data: 'hours_played', name: 'hours_played' },
            { data: 'game_played', name: 'game_played' },
            { data: 'food_item', name: 'food_item' },
            { data: 'zone_location', name: 'zone_location' },
            {
                data: 'id',
                render: function (data) {
                    return `<button class="del-visitor-btn" data-id="${data}" style="background:#d9534f; color:#fff; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Delete</button>`;
                }
            }
        ]
    });

    $('#filter_date, #filter_month, #filter_year, #filter_zone').on('change keyup', function () {
        visitorTable.draw();
        loadVisitorAnalytics();
    });

    $('#resetVisitorFiltersBtn').on('click', function () {
        $('#filter_date').val('');
        $('#filter_month').val('');
        $('#filter_year').val('');
        $('#filter_zone').val('');
        visitorTable.draw();
        loadVisitorAnalytics();
    });

    $('#saveVisitorBtn').on('click', function () {
        var vName = $('#v_name').val();
        var vDate = $('#v_date').val();
        if (!vName || !vDate) {
            $('#v_form_msg').html('<span style="color:#ff6b6b;">Please fill in Visitor Name and Date!</span>');
            return;
        }
        $(".loadercontainer").show();
        $.ajax({
            type: "post",
            url: "/visitor/store",
            data: {
                visitor_name: vName,
                phone_number: $('#v_phone').val(),
                zone_location: $('#v_zone').val(),
                hours_played: $('#v_hours').val(),
                game_played: $('#v_game').val(),
                food_item: $('#v_food').val(),
                entry_date: vDate
            },
            dataType: "json",
            success: function (res) {
                $(".loadercontainer").hide();
                if (res.success) {
                    $('#v_name').val('');
                    $('#v_phone').val('');
                    $('#v_form_msg').html('<span style="color:#51cf66;">Visitor entry logged successfully!</span>');
                    setTimeout(() => $('#v_form_msg').html(''), 3000);
                    visitorTable.draw();
                    loadVisitorAnalytics();
                } else {
                    $('#v_form_msg').html('<span style="color:#ff6b6b;">Error saving visitor entry.</span>');
                }
            }
        });
    });

    $('#visitorTable').on('click', '.del-visitor-btn', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to delete this visitor entry?")) {
            $(".loadercontainer").show();
            $.ajax({
                type: "post",
                url: "/visitor/delete",
                data: { id: id },
                dataType: "json",
                success: function () {
                    $(".loadercontainer").hide();
                    visitorTable.draw();
                    loadVisitorAnalytics();
                }
            });
        }
    });
});

function populateVisitorGames() {
    $.ajax({
        type: "get",
        url: "/game/view",
        success: function (response) {
            $("#v_game").empty();
            for (var i in response) {
                $("#v_game").append(`<option value="${response[i].name}">${response[i].name}</option>`);
            }
        }
    });
}

function loadVisitorAnalytics() {
    $.ajax({
        type: "get",
        url: "/visitor/analytics",
        data: {
            filter_date: $("#filter_date").val(),
            filter_month: $("#filter_month").val(),
            filter_year: $("#filter_year").val(),
            filter_zone: $("#filter_zone").val()
        },
        dataType: "json",
        success: function (res) {
            $("#v_stat_total").text(res.totalVisitors);
            $("#v_stat_hours").text(res.totalHours);
            $("#v_stat_ps5").text(res.upperFloor);
            $("#v_stat_pc").text(res.lowerFloor);
            $("#v_stat_topgame").text(res.topGame);
        }
    });
}

function updateStationCounts() {
    $.ajax({
        type: "get",
        url: "/computer/viewAll",
        dataType: "json",
        success: function (res) {
            let cntAvail = 0, cntRes = 0, cntPlay = 0, cntMaint = 0, cntOff = 0;
            res.forEach(item => {
                let st = item.status || 'AVAILABLE';
                if (st === 'AVAILABLE') cntAvail++;
                else if (st === 'RESERVED') cntRes++;
                else if (st === 'PLAYING') cntPlay++;
                else if (st === 'MAINTENANCE') cntMaint++;
                else if (st === 'OFFLINE') cntOff++;
            });
            $("#cntAvailable").text(cntAvail);
            $("#cntReserved").text(cntRes);
            $("#cntPlaying").text(cntPlay);
            $("#cntMaintenance").text(cntMaint);
            $("#cntOffline").text(cntOff);
        }
    });
}

function loadActiveSessions() {
    $.ajax({
        type: "get",
        url: "/session/active",
        dataType: "json",
        success: function (res) {
            $("#activeSessionsGrid").empty();
            if (res.length === 0) {
                $("#activeSessionsGrid").html('<div style="color: #aaa; grid-column: 1/-1;">No active gaming sessions running right now.</div>');
                return;
            }
            res.forEach(sess => {
                let stName = (sess.station_id <= 5) ? `🎮 PS5 Lounge #${sess.station_id}` : `💻 PC Arena #${sess.station_id}`;
                let custName = sess.user ? (sess.user.first_name + ' ' + sess.user.last_name) : (sess.notes || 'Guest');
                let remMins = Math.floor(sess.remaining_seconds / 60);
                let remSecs = sess.remaining_seconds % 60;
                let timeStr = `${remMins}m ${remSecs}s`;
                let isExpired = sess.is_expired;

                $("#activeSessionsGrid").append(`
                    <div style="background: var(--bgcolor3); border: 1px solid ${isExpired ? '#ff6b6b' : 'var(--secondc)'}; border-radius: 8px; padding: 15px; display: flex; flex-direction: column; gap: 8px;">
                        <div style="font-weight: bold; font-size: 1.1rem; color: var(--secondc); display: flex; justify-content: space-between;">
                            <span>${stName}</span>
                            <span style="color: ${isExpired ? '#ff6b6b' : '#51cf66'}; font-size: 0.85rem;">● ${isExpired ? 'EXPIRED' : 'ACTIVE'}</span>
                        </div>
                        <div style="font-size: 0.95rem; color: #fff;"><strong>Customer:</strong> ${custName}</div>
                        <div style="font-size: 0.85rem; color: #aaa;"><strong>Started:</strong> ${new Date(sess.started_at).toLocaleTimeString()}</div>
                        <div style="font-size: 0.85rem; color: #aaa;"><strong>Expected End:</strong> ${new Date(sess.expected_end_at).toLocaleTimeString()}</div>
                        <div style="font-size: 1.1rem; font-weight: bold; color: ${isExpired ? '#ff6b6b' : '#339af0'}; margin: 5px 0;">
                            ⏱ ${isExpired ? 'TIME EXPIRED' : timeStr + ' REMAINING'}
                        </div>
                        <button class="btn-end-session" data-session-id="${sess.id}" style="background: #ff6b6b; color: #fff; border: none; padding: 8px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 5px;">
                            ⏹ End Gaming Session
                        </button>
                    </div>
                `);
            });
        }
    });
}

$(document).on('click', '.btn-start-session', function () {
    var resId = $(this).data('res-id');
    if (confirm("Start Gaming Session for Reservation #" + resId + "?")) {
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/session/start-reservation",
            data: { reservation_id: resId },
            dataType: "json",
            success: function (res) {
                $(".loadercontainer").hide();
                alert(res.message);
                updateStationCounts();
                if (typeof dataTable !== 'undefined') dataTable.draw();
                loadActiveSessions();
            },
            error: function (xhr) {
                $(".loadercontainer").hide();
                let msg = "Failed to start session.";
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    }
});

$(document).on('click', '.btn-end-session', function () {
    var sessId = $(this).data('session-id');
    if (confirm("Are you sure you want to END Gaming Session #" + sessId + "?")) {
        $(".loadercontainer").show();
        $.ajax({
            type: "POST",
            url: "/session/end",
            data: { session_id: sessId },
            dataType: "json",
            success: function (res) {
                $(".loadercontainer").hide();
                alert(res.message);
                updateStationCounts();
                loadActiveSessions();
                if (typeof sessionHistoryTable !== 'undefined') sessionHistoryTable.draw();
            },
            error: function (xhr) {
                $(".loadercontainer").hide();
                let msg = "Failed to end session.";
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    }
});

$('#btnStartWalkInModal').on('click', function () {
    $('#walkin_guest_name').val('');
    $('#walkin_msg').html('');
    // Populate stations
    $.ajax({
        type: "get",
        url: "/computer/viewAll",
        dataType: "json",
        success: function (res) {
            $('#walkin_station_select').empty();
            res.forEach(st => {
                if (st.status === 'AVAILABLE') {
                    let labelText = (st.cid <= 5) ? `PS5 Lounge #${st.cid}` : `PC Arena #${st.cid}`;
                    $('#walkin_station_select').append(`<option value="${st.cid}">${labelText}</option>`);
                }
            });
            if ($('#walkin_station_select option').length === 0) {
                $('#walkin_station_select').append('<option value="">No AVAILABLE stations</option>');
            }
        }
    });
    // Populate packages
    $.ajax({
        type: "get",
        url: "/package/viewall",
        dataType: "json",
        success: function (res) {
            $('#walkin_package_select').empty();
            res.forEach(pkg => {
                $('#walkin_package_select').append(`<option value="${pkg.package_id}">${pkg.package_name} (${pkg.package_time}hr - Rs.${pkg.package_price})</option>`);
            });
        }
    });
    $('#walkInModal').css('display', 'flex');
});

$('#closeWalkInModal').on('click', function () {
    $('#walkInModal').hide();
});

$('#btnSubmitWalkIn').on('click', function () {
    var guestName = $('#walkin_guest_name').val();
    var stationId = $('#walkin_station_select').val();
    var packageId = $('#walkin_package_select').val();

    if (!stationId || !packageId) {
        $('#walkin_msg').html('<span style="color:#ff6b6b;">Please select an available station and package!</span>');
        return;
    }

    $(".loadercontainer").show();
    $.ajax({
        type: "POST",
        url: "/session/start-walkin",
        data: {
            customer_name: guestName,
            station_id: stationId,
            package_id: packageId
        },
        dataType: "json",
        success: function (res) {
            $(".loadercontainer").hide();
            if (res.success) {
                $('#walkin_msg').html('<span style="color:#51cf66;">Walk-in Session started!</span>');
                setTimeout(() => {
                    $('#walkInModal').hide();
                    updateStationCounts();
                    loadActiveSessions();
                    if (typeof sessionHistoryTable !== 'undefined') sessionHistoryTable.draw();
                }, 1200);
            }
        },
        error: function (xhr) {
            $(".loadercontainer").hide();
            let msg = "Failed to start walk-in session.";
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            $('#walkin_msg').html(`<span style="color:#ff6b6b;">${msg}</span>`);
        }
    });
});