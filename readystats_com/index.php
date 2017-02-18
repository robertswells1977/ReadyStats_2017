<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 5/16/14
 * Time: 10:41 AM
 */
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.0.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
    <!-- JavaScript -->
    <script src="js/bootstrap.js"></script>
    <script src="/js/cookies.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <!-- Add custom CSS here -->
    <style>
        body {
            /*margin-top: 60px;*/
        }
        hr{
            color: #223322;
            background-color: #223322;
            height: 2px;    }
        span.bywho{
            font-size: 8pt;
        }
        span.alert{
            background-color: #ffff00;
            color:#d2322d;
        }
    </style>

    <script language="JavaScript">

    $( document ).ready(function() {
        //trace("page is loaded.");
        $("#alerts").hide();
        $("#content_row").hide();
        $("#bottom_row").hide();
        $("#nav_bar").hide();
    });

    //
    //
    //
    function login(){

        $("#content_login").hide();
        $("#content_row").toggle("slide");
        $("#bottom_row").toggle("slide");
        $("#nav_bar").toggle("slide");

    }//login
    </script>
    <title>ReadyStats.com - Coaching for Performance.</title>
</head>
<body>

<div class="container">
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="page-header">
            <img src="img/readystats_logo_combined.png" class="img-responsive">
        </div>
        <nav id="nav_bar" class="navbar navbar-default navbar-inverse" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only"> Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="#">Coaches Notes</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li >
                        <a href="#">Team Stats</a>
                    </li>
                    <li >
                        <a href="#">Videos</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Players<strong class="caret"></strong></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#">#12 James Wells</a>
                            </li>
                            <li>
                                <a href="#">#7 Thomas Wells</a>
                            </li>
                            <li>
                                <a href="#">#64 Andrew Wells</a>
                            </li>
                            <li class="divider">
                            </li>
                            <li>
                                <a href="#">Show All Player Stats</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Games<strong class="caret"></strong></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#">April 10 at Noblesville vs FC Pride Red</a>
                            </li>
                            <li>
                                <a href="#">April 10 at Noblesville vs FC Pride White</a>
                            </li>
                            <li>
                                <a href="#">April 11 at Noblesville vs Noblesville</a>
                            </li>
                            <li class="divider">
                            </li>
                            <li>
                                <a href="#">Show All Game Stats</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">League Stats</a>
                    </li>
                </ul>
                <form class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" />
                    </div> <button type="submit" class="btn btn-default">Search</button>
                </form>
            </div>
        </nav>
        <div id="alerts" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>
                Alert!
            </h4> <strong>Warning!</strong> Best check yo self, you're not looking too good. <a href="#" class="alert-link">alert link</a>
        </div>
    </div>
</div>
<div id="content_login" class="row clearfix">
    <div class="col-md-offset-1 col-sm-3 column">
        <form role="form" action="#" onsubmit="javascript:login(); return false;">
            <div class="form-group">
                <label for="exampleInputEmail1">Player</label><input type="text" class="form-control" id="player_id" placeholder="Enter Player's Name"/>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="player_pass" placeholder="Password"/>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" /> Remember me</label>
            </div> <button type="submit" class="btn btn-lg">Show My Stats</button>
        </form>
    </div>
</div>
<div class="row clearfix" id="content_row">
    <div class="col-md-2 column">
        <ul>
            <li>
                Lorem ipsum dolor sit amet
            </li>
            <li>
                Consectetur adipiscing elit
            </li>
            <li>
                Integer molestie lorem at massa
            </li>
            <li>
                Facilisis in pretium nisl aliquet
            </li>
            <li>
                Nulla volutpat aliquam velit
            </li>
            <li>
                Faucibus porta lacus fringilla vel
            </li>
            <li>
                Aenean sit amet erat nunc
            </li>
            <li>
                Eget porttitor lorem
            </li>
        </ul>
    </div>
    <div class="col-md-6 column">
        <p>
            Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>. Aliquam eget sapien sapien. Curabitur in metus urna. In hac habitasse platea dictumst. Phasellus eu sem sapien, sed vestibulum velit. Nam purus nibh, lacinia non faucibus et, pharetra in dolor. Sed iaculis posuere diam ut cursus. <em>Morbi commodo sodales nisi id sodales. Proin consectetur, nisi id commodo imperdiet, metus nunc consequat lectus, id bibendum diam velit et dui.</em> Proin massa magna, vulputate nec bibendum nec, posuere nec lacus. <small>Aliquam mi erat, aliquam vel luctus eu, pharetra quis elit. Nulla euismod ultrices massa, et feugiat ipsum consequat eu.</small>
        </p>
    </div>
    <div class="col-md-4 column">
        <table class="table">
            <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Product
                </th>
                <th>
                    Payment Taken
                </th>
                <th>
                    Status
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    1
                </td>
                <td>
                    TB - Monthly
                </td>
                <td>
                    01/04/2012
                </td>
                <td>
                    Default
                </td>
            </tr>
            <tr class="active">
                <td>
                    1
                </td>
                <td>
                    TB - Monthly
                </td>
                <td>
                    01/04/2012
                </td>
                <td>
                    Approved
                </td>
            </tr>
            <tr class="success">
                <td>
                    2
                </td>
                <td>
                    TB - Monthly
                </td>
                <td>
                    02/04/2012
                </td>
                <td>
                    Declined
                </td>
            </tr>
            <tr class="warning">
                <td>
                    3
                </td>
                <td>
                    TB - Monthly
                </td>
                <td>
                    03/04/2012
                </td>
                <td>
                    Pending
                </td>
            </tr>
            <tr class="danger">
                <td>
                    4
                </td>
                <td>
                    TB - Monthly
                </td>
                <td>
                    04/04/2012
                </td>
                <td>
                    Call in to confirm
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row clearfix"  id="bottom_row">
    <div class="col-md-4 column">
        <h2>
            Heading
        </h2>
        <p>
            Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
        </p>
        <p>
            <a class="btn" href="#">View details »</a>
        </p>
    </div>
    <div class="col-md-4 column">
        <h2>
            Heading
        </h2>
        <p>
            Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
        </p>
        <p>
            <a class="btn" href="#">View details »</a>
        </p>
    </div>
    <div class="col-md-4 column">
        <h2>
            Heading
        </h2>
        <p>
            Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
        </p>
        <p>
            <a class="btn" href="#">View details »</a>
        </p>
    </div>
</div>
</div>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-52677013-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>