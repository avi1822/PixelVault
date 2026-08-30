<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PixelVault</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/login_style.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{asset('assets/js/login_page.js')}}"></script>
</head>

<body>
    <a href="/" style="position: absolute; top: 25px; left: 25px; display: inline-flex; align-items: center; gap: 8px; color: #fece59; text-decoration: none; font-family: sans-serif; font-size: 14px; font-weight: 600; padding: 10px 18px; background: rgba(254, 206, 89, 0.12); border: 1px solid #fece59; border-radius: 25px; transition: all 0.3s ease; z-index: 1000;" onmouseover="this.style.background='#fece59'; this.style.color='#111';" onmouseout="this.style.background='rgba(254, 206, 89, 0.12)'; this.style.color='#fece59';">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
    </a>
    <div class="loginarea">
        <div class="container">
            <!-- <div class="dec1 dec"></div>
            <div class="dec2 dec"></div>
            <div class="dec3 dec"></div> -->
            <form class="log" action="/registration/dologin" method="POST">
                <div class="cvdec cvdec1"></div>
                <div class="cvdec cvdec2"></div>
                <div class="chdec chdec1"></div>
                <div class="chdec chdec2"></div>
                @csrf
                <div class="caption">LOGIN</div>

                <div class="text">User Name</div>
                <input type="text" name="username" id="uname">
                <div class="error">
                    @if ($errors->has('username'))
                    @error("username")
                    {{$message}}
                    @enderror
                    @endif
                </div>
                <div class="text">Password</div>
                <input type="password" name="password" id="password">
                
                <div class="error">
                    @if ($errors->has('password'))
                    @error("password"){{$message}}@enderror
                    @endif
                </div>
                <input type="submit" value="Login" name="Submit">
                <div style="text-align: center; margin-top: 15px;">
                    <a href="/" style="color: #fece59; text-decoration: none; font-size: 13px; opacity: 0.9;"><i class="fa-solid fa-house"></i> Back to Home</a>
                </div>
                {{-- <button id="loginbtn">Login</button> --}}
            </form>
        </div>
    </div>
</body>

</html>