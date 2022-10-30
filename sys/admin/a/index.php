<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOL</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
            font-family: Inter, sans-serif;
            outline: none;
        }
    </style>
</head>
<body>
    <b>SALE! <span id="countdown"></span> seconds</b>
    <span id="show"></span>
<script type="text/javascript">

    var seconds = 5;
    
    function countdown() {
        seconds = seconds - 1;
        if (seconds < 0) {
            showContent();
        } else {
            document.getElementById("countdown").innerHTML = seconds;
            window.setTimeout("countdown()", 1000);
        }
    }

    function showContent(){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(){
            document.getElementById("show").innerHTML = this.responseText;
        }
        xhttp.open("GET", "finish.php");
        xhttp.send();
    }
    
    // Run countdown function
    countdown();
</script>
</body>
</html>
