<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="script.js">

</head>
<body>


<div class="container">

    <!--aside section start-->
    <aside>

        <div class="top">

            <div class="logo">
                <h2><span class="danger">Vaccination Management System<span class="danger"></span></h2>
</div>
</div>
<div class="close">
    <span class="material-symbols-sharp"> close</span>
</div>
</div>
<!--end top-->

<div class="sidebar">
    <a href="#">
        <span class="material-symbols-sharp">grid_view</span>
        <h3>Dashboard</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">person_outline</span>
        <h3>customers</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">insights</span>
        <h3>Analytics</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">mail_outline</span>
        <h3>Messages</h3>
        <span class="msg_count">14</span>
</a>
<a href="#">
        <span class="material-symbols-sharp">receipt_long</span>
        <h3>Products</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">report_gmailerrorred</span>
        <h3>Reports</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">settings</span>
        <h3>settings</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">add</span>
        <h3>Add Products</h3>
</a>
<a href="#">
        <span class="material-symbols-sharp">logout</span>
        <h3>logout</h3>
</a>

</div>


    </aside>
    <!--aside section end-->

    <!--main section start-->
    <main>
    <h1>Dashboard</h1>

    <div class="date">
        <input type="date">
</div>

<div class="insight">
<!-- start selling -->
 <div class="sales">
        <span class="material-symbols-sharp">Trending_up</span>
        <div class="middle">
        <div class="left">
                <h3>Total Sales</h3>
                <h1>25,000</h1>
        </div>
        <div class="progress">
                <svg>
                        <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number">80%</div>
        </div>
        </div>
        <small>last 24 hrs</small>
 </div>
 <!-- end selling -->



 <!-- start expenses -->
 <div class="expenses">
        <span class="material-symbols-sharp">local_mall</span>
        <div class="middle">
        <div class="left">
                <h3>Expenses</h3>
                <h1>25,000</h1>
        </div>
        <div class="progress">
                <svg>
                        <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number">80%</div>
        </div>
        </div>
        <small>last 24 hrs</small>
 </div>
 <!-- end selling -->
</div>
<!-- start expenses -->
<div class="expenses">
        <span class="material-symbols-sharp">stached_line_chart</span>
        <div class="middle">
        <div class="left">
                <h3>Income</h3>
                <h1>25,000</h1>
        </div>
        <div class="progress">
                <svg>
                        <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number">80%</div>
        </div>
        </div>
        <small>last 24 hrs</small>
 </div>
 <!-- end selling -->
</div>
<!-- end insight -->
 <!-- start recent order -->
  <div class="recent_order">
        <h2>recent order</h2>
        <table>
                <thead>
                        <tr>
                                <th>product name</th>
                                <th>product number</th>
                                <th>payment</th>
                                <th>status</th>
                        </tr>
                </thead>
                <tbody>
                        <tr>
                                <td>mini usb</td>
                                <td>456</td>
                                <td>due</td>
                                <td>pending</td>
                                <td class="warning">pending</td>
                                <td class="primary">details</td>
                        </tr>
                        <tr>
                                <td>mini usb</td>
                                <td>456</td>
                                <td>due</td>
                                <td>pending</td>
                                <td class="warning">pending</td>
                                <td class="primary">details</td>
                        </tr>
                        <tr>
                                <td>mini usb</td>
                                <td>456</td>
                                <td>due</td>
                                <td>pending</td>
                                <td class="warning">pending</td>
                                <td class="primary">details</td>
                        </tr>
                </tbody>
        </table>
  </div>
  <!-- end recent order -->
    </main>
    <!--main section end-->

    <!--right section start-->
    <div class="right">
    <h1>right</h1>
    </div>
    <!--end right section-->

</div>



    <script src="script.js"></script>
    
</body>
</html>