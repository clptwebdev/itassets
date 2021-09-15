<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

        body{
            font-size: 11px;
            font-family: 'Roboto', sans-serif;
        }

        #header{
            background-color: #454777;
            width: 100%;
            margin-bottom: 30px;
            color: #fff;
            font-size: 14px;
        }

        #logo{
            max-height: 100px;
        }

        #assetsTable{
            border: solid 1px #666;
            border-collapse: collapse;
        }

        #assetsTable th{
            padding: 5px;
            background-color: #454777;
            color: #FFF;
            border: solid 1px #666;
        }

        #assetsTable td{
            border: solid 1px #AAA;
            padding: 5px;
        }

        .page-break {
            page-break-after: always;
        }
        </style>
    @yield('css')
</head>
<body>
    <header id="header">
        <table width="100%"></i>
            <tr>
                <td align="left" style="padding-left:10px;" width="20%"><img id="logo" src="{{ asset('images/apollo-logo.jpg') }}" alt="Apollo Assets Manager"></td>
                <td align="left">Apollo Asset Management<br><small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small>
                    <br><strong>@yield('page')</strong>
                </td>
                <td align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y H:i')}}<br>Report by: @yield('user')
                </td>
            </tr>
        </table>
    </header>
    @yield('content')
</body>
</html>
