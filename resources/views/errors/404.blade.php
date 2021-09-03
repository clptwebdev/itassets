Oooops<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Raleway:500,800" rel="stylesheet">
  <title>Document</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
      * {
  margin:0;
  padding: 0;
}
body{
  background: #474775;
  
}
.whistle{
  width: 20%;
  fill: #f95959;
  margin: 100px 40%;
  text-align: left;
  transform: translate(-50%, -50%);
  transform: rotate(0);
  transform-origin: 80% 30%;
  animation: wiggle .2s infinite;
}

@keyframes wiggle {
  0%{
    transform: rotate(3deg);
  }
  50%{
    transform: rotate(0deg);
  }
  100%{
    transform: rotate(3deg);
  }
}
h1{
  margin-top: -100px;
  margin-bottom: 20px;
  color: #b087bc;
  text-align: center;
  font-family: 'Raleway';
  font-size: 60px;
  font-weight: 800;
}
h2{
  color: #eeeeee ;
  text-align: center;
  font-family: 'Raleway';
  font-size: 30px;
  text-transform: uppercase;
}

.btn-main{
  margin: auto;
  text-align: center;
  background-color: #b087bc;
  font-size: 20px;
  margin-top: 30px;
  font-family: 'Raleway';
  text-transform: capitalize;
}
  </style>
  
</head>
<body>
  <use>
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 500 500" enable-background="new 0 0 1000 1000" xml:space="preserve" class="whistle"><defs><style>.cls-1{fill:#f95959;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M460.08,105.08,422.86,67.85a27.49,27.49,0,0,0-19.45-8H271.61V46.05A13.74,13.74,0,0,0,257.86,32.3h-27.5a13.74,13.74,0,0,0-13.75,13.75V59.8H72.23A20.63,20.63,0,0,0,51.61,80.42v68.75A20.63,20.63,0,0,0,72.23,169.8H403.41a27.52,27.52,0,0,0,19.45-8.06l37.22-37.21A13.75,13.75,0,0,0,460.08,105.08ZM216.61,458.55a13.75,13.75,0,0,0,13.75,13.75h27.5a13.75,13.75,0,0,0,13.75-13.75V362.3h-55ZM416,224.8H271.61V197.3h-55v27.5H84.81a27.49,27.49,0,0,0-19.45,8L28.14,270.08a13.75,13.75,0,0,0,0,19.45l37.22,37.21a27.5,27.5,0,0,0,19.45,8.06H416a20.63,20.63,0,0,0,20.63-20.63V245.42A20.63,20.63,0,0,0,416,224.8Z"/><rect class="cls-1" x="30.08" y="0.47" width="11" height="55" transform="translate(-9.22 21.54) rotate(-30)"/><rect class="cls-1" x="55.76" y="0.43" width="8.74" height="43.71" transform="translate(-3.3 12.82) rotate(-11.85)"/><rect class="cls-1" x="15.96" y="30.48" width="8.74" height="43.71" transform="translate(-34.08 38.49) rotate(-54.49)"/></g></g></svg>
</use>
<div style="padding-left: 15%; padding-right: 15%;">
<h1>404 - Oooops there was a problem</h1>
<h2>
It looks like the page you required has been moved or is no longer available. Please check that you have the correct URL.</h2>
<div class="container text-center mt-4"><a href="/dashboard" class="btn btn-main p-2 text-center">Return to Button</a></div>
</div>
</body>
</html>
