
<?php
require 'config/function.php';


if(isset($_SESSION['auth'])){
    if($_SESSION['loggedInUserRole']=='admin'){
        header('location:admin/index.php');
    }else{
        header('location:user/index.php');
    }
}
?>

<head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat|Quicksand');

        * {
            font-family: 'quicksand', Arial, Helvetica, sans-serif;
            box-sizing: border-box;
        }

        body {
            background: #fff;
        }

        .form-modal {
            position: relative;
            width: 450px;
            height: auto;
            margin-top: 4em;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 0 3px 20px 0px rgba(0, 0, 0, 0.1)
        }

        .form-modal button {
            cursor: pointer;
            position: relative;
            text-transform: capitalize;
            font-size: 1em;
            z-index: 2;
            outline: none;
            background: #fff;
            transition: 0.2s;
        }

        .form-modal .btn {
            border-radius: 20px;
            border: none;
            font-weight: bold;
            font-size: 1.2em;
            padding: 0.8em 1em 0.8em 1em !important;
            transition: 0.5s;
            border: 1px solid #ebebeb;
            margin-bottom: 0.5em;
            margin-top: 0.5em;
        }

        .form-modal .login,
        .form-modal .signup {
            background: #cb0c9f;
            color: #fff;
        }

        .form-modal .login:hover,
        .form-modal .signup:hover {
            background: #222;
        }

        .form-toggle {
            position: relative;
            width: 100%;
            height: auto;
        }

        .form-toggle button {
            width: 50%;
            float: left;
            padding: 1.5em;
            margin-bottom: 1.5em;
            border: none;
            transition: 0.2s;
            font-size: 1.1em;
            font-weight: bold;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
        }

        .form-toggle button:nth-child(1) {
            border-bottom-right-radius: 20px;
        }

        .form-toggle button:nth-child(2) {
            border-bottom-left-radius: 20px;
        }

        #login-toggle {
            background: #cb0c9f;
            color: #ffff;
        }

        .form-modal form {
            position: relative;
            width: 90%;
            height: auto;
            left: 50%;
            transform: translateX(-50%);
        }

        #login-form,
        #signup-form {
            position: relative;
            width: 100%;
            height: auto;
            padding-bottom: 1em;
        }

        #signup-form {
            display: none;
        }


        #login-form button,
        #signup-form button {
            width: 100%;
            margin-top: 0.5em;
            padding: 0.6em;
        }

        .form-modal input {
            position: relative;
            width: 100%;
            font-size: 1em;
            padding: 1.2em 1.7em 1.2em 1.7em;
            margin-top: 0.6em;
            margin-bottom: 0.6em;
            border-radius: 20px;
            border: none;
            background: #ebebeb;
            outline: none;
            font-weight: bold;
            transition: 0.4s;
        }

        .form-modal input:focus,
        .form-modal input:active {
            transform: scaleX(1.02);
        }

        .form-modal input::-webkit-input-placeholder {
            color: #222;
        }


        .form-modal p {
            font-size: 16px;
            font-weight: bold;
        }

        .form-modal p a {
            color: #cb0c9f;
            text-decoration: none;
            transition: 0.2s;
        }

        .form-modal p a:hover {
            color: #222;
        }


        .form-modal i {
            position: absolute;
            left: 10%;
            top: 50%;
            transform: translateX(-10%) translateY(-50%);
        }

        .fa-google {
            color: #dd4b39;
        }

        .fa-linkedin {
            color: #3b5998;
        }

        .fa-windows {
            color: #0072c6;
        }

        .-box-sd-effect:hover {
            box-shadow: 0 4px 8px hsla(210, 2%, 84%, .2);
        }

        @media only screen and (max-width:500px) {
            .form-modal {
                width: 100%;
            }
        }

        @media only screen and (max-width:400px) {
            i {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="form-modal">

        <div class="form-toggle">
            <button id="login-toggle" onclick="toggleLogin()">log in</button>
            <button id="signup-toggle" onclick="toggleSignup()">sign up</button>
        </div>

        <div id="login-form">
            <form method="POST" action="login-code.php">
                <input type="text" placeholder="Enter email" name="email" />
                <input type="password" placeholder="Enter password" name="password" />
                <input type="submit" class="btn login" value="login" name="login">
                <p style="color:RED;"><?= alertMessage(); ?></P>
            </form>
        </div>

        <div id="signup-form">
            <form method="POST" action="signup-code.php" onsubmit="submitHandler(event)" autocomplete="on">
                <!-- <input type="text" placeholder="Choose username" name="username" required />
                <input type="text" placeholder="Enter your full name" id="name" name="name" required />
                <input type="email" placeholder="Enter your email" name="email" required />
                <input type="number" id="phone" placeholder="Enter your phone number" name="phone" required />
                <input type="password" id="pass" placeholder="Create password" name="password" required />
                <input type="password" id="passRe" placeholder="Confirm password" name="passRe" required />
                <input type="submit" class="btn signup" name="signup" value="create account" > -->
                <!-- <p>Clicking <strong>create account</strong> means that you are agree to our <a href="javascript:void(0)">terms of services</a>.</p> -->
                <input type="text" placeholder="Choose username" id="username" name="username" required />
                <span id="username-error" class="error-message"></span>
                <input type="text" placeholder="Enter your full name" id="name" name="name" required />
                <span id="name-error" class="error-message"></span>
                <input type="email" placeholder="Enter your email" name="email" required />
                <span id="email-error" class="error-message"></span>
                <input type="number" id="phone" placeholder="Enter your phone number" name="phone" required />
                <span id="phone-error" class="error-message"></span>
                <input type="password" id="pass" placeholder="Create password" name="password" required />
                <span id="pass-error" class="error-message"></span>
                <input type="password" id="rpass" placeholder="Confirm password" name="passRe" required />
                <span id="rpass-error" class="error-message"></span>
                <input type="submit" class="btn signup" name="signup" value="create account">
                <hr />
            </form>
        </div>
    </div>

    <script>
        window.onload=function(){
            if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        }
        
       
        function toggleSignup() {
            document.getElementById("login-toggle").style.backgroundColor = "#fff";
            document.getElementById("login-toggle").style.color = "#222";
            document.getElementById("signup-toggle").style.backgroundColor = "#cb0c9f";
            document.getElementById("signup-toggle").style.color = "#000";
            document.getElementById("login-form").style.display = "none";
            document.getElementById("signup-form").style.display = "block";
        }

        function toggleLogin() {
            document.getElementById("login-toggle").style.backgroundColor = "#cb0c9f";
            document.getElementById("login-toggle").style.color = "#fff";
            document.getElementById("signup-toggle").style.backgroundColor = "#fff";
            document.getElementById("signup-toggle").style.color = "#222";
            document.getElementById("signup-form").style.display = "none";
            document.getElementById("login-form").style.display = "block";
        }

        function submitHandler(event) {
            //event.preventDefault();
            let ok = 1;

            // Clear previous error messages
            document.getElementById("username-error").textContent = "";
            document.getElementById("name-error").textContent = "";
            document.getElementById("phone-error").textContent = "";
            document.getElementById("pass-error").textContent = "";
            document.getElementById("rpass-error").textContent = "";

            // Username validation
            let username = document.getElementById("username");
            let usernameVal = username.value;
            let usernameRegex = /^[a-zA-Z][a-zA-Z0-9._]{3,8}[a-zA-Z0-9]$/
            if (!usernameRegex.test(usernameVal)) {
                ok = 0;
                username.style.border = "solid 1px #ff0000";
                username.focus();
                document.getElementById("username-error").textContent = "Username must be alphanumeric, start with an alphabet, and can include '.' or '/'.";
            } else {
                username.style.border = "0px";
            }

            // Full name validation
            let name = document.getElementById("name");
            let nameVal = name.value;
            let nameRegex  = /^([A-Za-z]{2,}\.?)(\s[A-Za-z]{2,}\.?)+$/;
            if (!nameRegex.test(nameVal)) {
                ok = 0;
                name.style.border = "solid 1px #ff0000";
                name.focus();
                document.getElementById("name-error").textContent = "Full name must start with a capital letter and contain at least two words.";
            } else {
                name.style.border = "0px";
            }

            // Phone validation
            let phone = document.getElementById("phone");
            let phoneVal = phone.value;
            let phoneRegex = /^9[87][0-9]{8}$/;
            if (!phoneRegex.test(phoneVal)) {
                ok = 0;
                phone.style.border = "solid 1px #ff0000";
                phone.focus();
                document.getElementById("phone-error").textContent = "Phone number must start with 9, followed by 8 or 7, and be exactly 10 digits long.";
            } else {
                phone.style.border = "0px";
            }

            // Password validation
            let pass = document.getElementById("pass");
            let passVal = pass.value;
            let rpass = document.getElementById("rpass");
            let rpassVal = rpass.value;
            if (passVal.length < 6 && passVal.length<16) {
                ok = 0;
                pass.style.border = "solid 1px #ff0000";
                rpass.style.border = "solid 1px #ff0000";
                pass.focus();
                document.getElementById("pass-error").textContent = "Password must be at least 6 characters long.";
            } else {
                pass.style.border = "0px";
                rpass.style.border = "0px";
            }
            if (passVal !== rpassVal) {
                ok = 0;
                rpass.focus();
                pass.style.border = "solid 1px #ff0000";
                rpass.style.border = "solid 1px #ff0000";
                document.getElementById("rpass-error").textContent = "Passwords do not match.";
            } else {
                pass.style.border = "0px";
                rpass.style.border = "0px";
            }
            console.log("abc",ok);

            if (ok === 0) {
                event.preventDefault();
            }
            return ok;
        }

    </script>
</body>