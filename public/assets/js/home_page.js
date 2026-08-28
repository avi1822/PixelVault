var targetCanMove = false;

function resetButtons() {
  $("#logbtn, #regbtn").css("display", "").removeClass("bullet-fire");
  $(".homearea .land").removeClass("shake shake2");
}

$(window).on("pageshow focus", function () {
  resetButtons();
});

function loginBtn() {
  var logbtn = $("#logbtn");
  var landarea = $(".homearea .land");

  logbtn.removeClass("bullet-fire");
  landarea.removeClass("shake shake2");

  logbtn.addClass("bullet-fire");
  landarea.addClass('shake');

  setTimeout(function () {
    window.location.href = '/login';
  }, 2000);
}

function bulletBtn() {
  var regbtn = $("#regbtn");
  var landarea = $(".homearea .land");

  regbtn.removeClass("bullet-fire");
  landarea.removeClass("shake shake2");

  regbtn.addClass("bullet-fire");
  landarea.addClass('shake');

  setTimeout(function () {
    window.location.href = '/register';
  }, 2000);
}

function shakeWindow() {
  var landarea = $(".homearea .land");
  landarea.removeClass("shake");
  landarea.removeClass("shake2");
  window.requestAnimationFrame(function () {
    landarea.addClass('shake2');
  });
}
function dashConuter(element, end) {
  element.each(function () {
    $(this).prop('Counter', 0).animate({
      Counter: end
    }, {
      duration: 4000,
      easing: 'swing',
      step: function (now) {
        $(this).text(Math.ceil(now));
      }
    });
  });
}

function setDashData() {
  $.ajax({
    type: "get",
    url: "/home/view",
    success: function (response) {
      dashConuter($("#dashcomputer"), response[0].computers)
      dashConuter($("#dashgame"), response[0].games)
      dashConuter($("#dashuser"), response[0].users)
      dashConuter($("#dashres"), response[0].reservations)
    }
  });
}

function targetMove(e) {
  // mouseX = e.pageX;
  // mouseY = e.pageY;
  // var back = $(".homearea .land .wall .subcontainer2 .back");
  // $(".target").css({"top": (mouseY - back.top - $(".target").getBoundingClientRect().width / 2) + "px", "left": (mouseX - back.left - $(".target").getBoundingClientRect().width / 2) + "px"});

}

$(document).ready(function () {
  resetButtons();
  setDashData();

  var targetBack = $(".homearea .land .wall .subcontainer2");
  var targetImg = $(".homearea .land .wall .subcontainer2 .target .circle");
  var target = $(".target");

  targetBack.on("click", function () {
    if(targetCanMove){
    shakeWindow();
    }else{
      targetCanMove = true;
      targetImg.css("background-image", 'url("/assets/img/train3.png")');
      targetBack.css("cursor","none");
    }
  });
  targetBack.on("mousemove", function (e) {
    if (targetCanMove) {
      var mouseX = e.offsetX;
      var mouseY = e.offsetY;
      target.css({
        "top": (mouseY - target.width() / 2) + "px",
        "left": (mouseX - target.width() / 2) + "px",
      });
      targetImg.css("background-position", `${-(mouseX - targetImg.width() / 2)}px ${-(mouseY - targetImg.height() / 2)}px`);
    }
  });
  targetBack.on("mouseout", function () {
    var pos = targetBack.width() / 2 - target.width() / 2;
    target.animate({
      top: `${pos}px`,
      left: `${pos}px`,
    });
    targetImg.css({"background-image": 'none', "background-position" : "center"});
    targetBack.css("cursor","default");
    targetCanMove = false;
  });

  $("section").fuwatto({
    duration: 3000,
  });
});
// document.addEventListener("mousemove", function() {
//   targetMove(event);
// });


// function targetMove(e) {
//   var cursor = document.getElementsByClassName("target");
//   mouseX = e.clientX;
//   mouseY = e.clientY;
//   console.log(cursor);
//   cursor.style.left = (mouseX - 55) + "px";
//   cursor.style.top = (mouseY - 55) + "px";


// }

// function targetMove(e) {
//   var cursor = document.getElementsByClassName("target")[0];
//   var back = document.getElementsByClassName("back")[0].getBoundingClientRect();
//   var cursorImg = document.querySelector(".target .circle");
//   var mouseX = e.clientX;
//   var mouseY = e.clientY;
//   if (cursor) {
//     cursor.style.left = (mouseX - back.left - cursor.getBoundingClientRect().width / 2) + "px";
//     cursor.style.top = (mouseY - back.top - cursor.getBoundingClientRect().width / 2) + "px";
//     cursorImg.style.backgroundPosition = "-" + mouseX / 2 + "px -" + mouseY / 2 + "px";
//   }
// }

// window.onload = function () {
//   var back = document.querySelector(".homearea .land .wall .subcontainer2 .back");
//   if (back) {
//     back.addEventListener("mousemove", targetMove);
//     back.addEventListener("click", shakeWindow);
//   }
// }

$(document).ready(function () {
    $("#btnSubmitContact").on("click", function (e) {
        e.preventDefault();

        var name = $("#contact_name").val().trim();
        var email = $("#contact_email").val().trim();
        var subject = $("#contact_subject").val().trim();
        var message = $("#contact_message").val().trim();

        if (!name || !email || !message) {
            $("#contact_msg_response").html('<span style="color:#ff6b6b;">Please fill in your Name, Email, and Message!</span>');
            return;
        }

        $("#btnSubmitContact").prop("disabled", true).text("Sending...");
        $("#contact_msg_response").html("");

        $.ajax({
            type: "POST",
            url: "/contact/store",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                name: name,
                email: email,
                subject: subject,
                message: message
            },
            dataType: "json",
            success: function (res) {
                $("#btnSubmitContact").prop("disabled", false).text("Send Message");
                if (res.success) {
                    $("#contact_msg_response").html(`<span style="color:#51cf66;">${res.message}</span>`);
                    $("#contact_name").val("");
                    $("#contact_email").val("");
                    $("#contact_subject").val("");
                    $("#contact_message").val("");
                }
            },
            error: function (xhr) {
                $("#btnSubmitContact").prop("disabled", false).text("Send Message");
                let msg = "Failed to send message. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $("#contact_msg_response").html(`<span style="color:#ff6b6b;">${msg}</span>`);
            }
        });
    });
});