<?php
error_reporting(0);
require('goconnect.php');

$id = $_GET["id"]; //ดึงเอาไอดีออกมาจาก หน้า g_index.php ระบุไอดี

$tt = @$_POST['st'];    //สร้างตัวแปร $tt ที่ได้มาจาก input  time   name
$ee = @$_POST['en'];    //สร้างตัวแปร $ee ที่ได้มาจาก input  time   name

if ($tt == '') {         //ถ้าค่าว่างให้แสดง ตามที่กำหนด คือ 8.30-12.00
    $tt = '08:30';
    $ee = '12:00';
} else {                  //ถ้ามีคนกรอกใน input time ให้แสดงตามที่กรอกในช่อง input
    $tt = $_POST['st'];
    $ee = $_POST['en'];
}

$gap = "SELECT employee.id , employee.fname,  employee.lname,                                
        edata.emp , edata.time , edata.pcall , edata.ticket , edata.aht
        FROM employee RIGHT JOIN edata ON employee.id=edata.emp
        WHERE edata.time BETWEEN '$tt' AND '$ee' AND id = $id ";    //ใช้ BETWEEN เพื่อให้เลือกข้อมูล ระหว่าง ข้อมูล เช่นเวลา 11.00 ถึง 13.00 
//ใช้ join ข้อมูลระหว่าง สองตาราง  , 


$result = mysqli_query($gcon, $gap);
$low = mysqli_fetch_assoc($result); //แสดงค่าออกมาตามที่เรากำหนดตัวแปร $low


$tal = "SELECT SUM(pcall) AS total1,              
               SUM(ticket) AS total2           
               FROM edata where emp = $id"; //แสดงผลรวม ของค่า call or ticket         

$rs = mysqli_query($gcon, $tal); //แสดงค่าออกมาตามที่เรากำหนดตัวแปร $tol 
$tol = mysqli_fetch_assoc($rs);


if ($tol['total2'] >= $tol['total1']) {        //สูตรคำนวณ % ระหว่าง ticket กับ call
    $percomplete = 100;
    $perfail = 0;
} else {
    $percomplete = ($tol['total2'] * 100) / $tol['total1'];
    $perfail = 100 - $percomplete;
}
// echo $percomplete.'<br>'; 
// echo $perfail;
?>


<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script type="text/javascript" src="loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        // คำสั่งของ google ที่เรียกใช้ กราฟ ต่างๆ  
        google.charts.setOnLoadCallback(drawVisualization);
        google.charts.setOnLoadCallback(draw);
        google.charts.setOnLoadCallback(drawbar);
        google.charts.setOnLoadCallback(drawp);

        function drawVisualization() {
            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable([

                ['DAY', 'Call', 'Ticket', ], //ชื่อบอกตำแหน่ง คอลัมกราฟ
                //การโทร       //จำนวนเรื่อง
                <?php

                $re = mysqli_query($gcon, $gap);
                while ($row = mysqli_fetch_assoc($re)) {      //แสดงข้อมูลแบบวนลูป while

                ?>

                    ['<?= date('H:i', strtotime($row['time'])); ?>', <?= $row['pcall']; ?>, <?= $row['ticket']; ?>, ],
                    //แสดงผลข้อมูลกราฟแท่ง จากฐานข้อมูล

                <?php } ?>

            ]);

            //view แสดงตัวเลขบน คอลลัมกราฟ
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2,
                {
                    calc: "stringify",
                    sourceColumn: 2,
                    type: "string",
                    role: "annotation"
                },
            ]);

            //ปรับแต่งกราฟ
            var options = {
                title: 'กราฟแสดงเวลาตั้งแต่ "<?php echo $tt ?> - <?php echo $ee ?>"',
                animation: {
                    duration: 1000
                },
                hover: {
                    "fill": {
                        "value": "red"
                    }
                },
                legend: {
                    // position: '',
                    textStyle: {
                        color: 'dark',
                        fontSize: 18
                    }

                },
                // width : 1450,
                // hight : 2000, 
                colors: ['#BB0035', '#3C0011', '#0af568'],
                vAxis: {
                    textStyle: {
                        fontSize: 10, // or the number you want
                        color: '#C1C1C1',
                        // bold: true,
                    },
                },
                hAxis: {
                    textStyle: {
                        fontSize: 10, // or the number you want
                        color: '#000000',
                        bold: true,
                    },
                    //ชื่อบอก แกน Y
                    title: 'Time',
                    titleTextStyle: {
                        titlePosition: 'out',
                        // color: '#27f702',
                        fontSize: 18, // 12, 18 whatever you want (don't specify px)
                        bold: true, // true or false
                        italic: true, // true of false
                    },
                },
                // seriesType: 'bars',
                // series: {2: {type: 'area'}}
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(view, options);
        }

        //กราฟ Pie
        function draw() {
            // Some raw data (not necessarily accurate)
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Call');
            data.addColumn('number', 'ticket');
            data.addRows([
                ['COMPLETE', <?= $percomplete; ?>],
                ['FAIL', <?= $perfail; ?>],

            ]);
            var options = {
                title: 'กราฟแสดงผลรวมทั้งหมดของ CALL AND TICKET',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                titleTextStyle: {
                    titlePosition: 'out',
                    color: 'dark',
                    fontSize: 15, // 12, 18 whatever you want (don't specify px)
                    bold: true, // true or false
                    italic: true, // true of false
                },
                legend: {
                    position: 'bottom',
                    textStyle: {
                        color: 'dark',
                        fontSize: 18
                    }

                },
                // chartArea:{left:20,top:50,width:'50%',height:'50%'},
                colors: ['#02f71f', '#f70f0f', ],
                vAxis: {
                    title: 'number'
                    // is3D: true
                },
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
        //กราฟ AreaChart
        function drawbar() {
            // Some raw data (not necessarily accurate)
            var data = new google.visualization.arrayToDataTable([

                ["Element", "AHT", "CALL"],
                <?php
                $ra = mysqli_query($gcon, $gap);
                while ($ar = mysqli_fetch_assoc($ra)) {
                ?>

                    ['<?= date('H:i', strtotime($ar['time'])); ?>', <?= $ar['aht']; ?>, <?= $ar['pcall']; ?>],

                <?php } ?>

            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                }
            ]);

            var options = {
                title: 'กราฟแสดงค่าเฉลี่ย AHT ตามช่วงเวลา "<?php echo $tt ?> - <?php echo $ee ?>"',
                curveType: 'function',
                colors: ['#BB0035', '#3C0011'],
                pointSize: 6, //เป็นการใส่จุดใน กราฟเส้น
                //สัญลักษณ์ ที่เรากำหนดเป็นอย่างอื่น ตามที่ได้ commentไว้
                series: {
                    0: {
                        pointShape: 'circle'
                    },
                    colors: '#000000'
                    // 1: {
                    //     pointShape: 'triangle'
                    // },
                    // 2: {
                    //     pointShape: 'square'
                    // },
                    // 3: {
                    //     pointShape: 'diamond'
                    // },
                    // 4: {
                    //     pointShape: 'star'
                    // },
                    // 5: {
                    //     pointShape: 'polygon'
                    // }
                },
                // series: {1: {type: 'area'}}
                vAxis: {
                    textStyle: {
                        fontSize: 10, // or the number you want
                        color: '#C1C1C1',
                        // bold: true,
                        // minValue: 0,
                    },
                },
                hAxis: {
                    textStyle: {
                        fontSize: 10, // or the number you want
                        color: '#000000',
                        bold: true,
                    },
                },
                seriesType: 'bars',
                series: {
                    1: {
                        type: 'line'
                    }
                }
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chartbar'));
            chart.draw(data, options);
        }
    </script>
    <title>Dashbord</title>
</head>

<body>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <!-- <nav class="navbar navbar-expand-lg bg-dark navbar-dark  border border-5"  >
<div class="container-fluid">;
<a class="navbar-brand"  href="g_index.php">Home</a>
</div>
</div>
   </nav> -->
    <div class="shadow-sm p-3 mb-5  rounded mx-100" style="background-color: #770300;">
        <h2 class="text-center text-light" id="dep"><i class="bi bi-person-fill"></i> รายละเอียดพนักงาน
            "<?= $low['fname'] ?> <?= $low['lname'] ?>"</h2>
    </div>
    <div class="container">

        <div class=" text-center">
            <div class="row my-2">
                <!-- <div class="col">  
    วันที่ : 
      <input type="date" class="btn btn-outline-primary" name="date" disabled>
      </div> -->

                <div class="row">
                    <div class="col-2">
                        <!-- <a href="#chart" class="btn btn-danger">Pie Chart<i class="bi bi-chevron-bar-down"></i></a> -->
                        <a href="g_index.php" class="btn" style="font-size: 1rem; color:aliceblue; background-color:#000000; "><i class="bi bi-arrow-left-circle-fill"></i>
                            กลับหน้าแรก</a>
                    </div>
                    <!-- หน้ากรอกข้อมูลเวลา -->
                    <div class="col-8">
                        <form action="<?= $_SERVER['PHP_SELF']; ?>?id=<?= $id; ?>" method="post">
                            <input type="hidden" value="<?= $id ?>" name="id">
                            <input type="time" class="time btn btn-outline-primary" name="st" value="<?= $_POST['st']; ?>"> ถึง
                            <input type="time" class="time btn btn-outline-danger" name="en" value="<?= $_POST['en']; ?>">
                            <button type="submit" class="btn btn-info"><i class="bi bi-clock-fill"></i>
                                ค้นหา</button>
                        </form>
                    </div>
                </div>
                <!-- <hr class="my-2" style="background-color:#000000;"> -->


                <div class="">



                    <table class="container">
                        <!-- style="border: 0.200rem solid " -->
                        <tr>

                            <td style="border: 0.200rem #0d1ce0">

                                <div class="card">
                                    <div class="card-header  text-light" style="background-color: #A30400;">
                                        <h2 class="text-center"><i class="bi bi-bar-chart-line-fill" style="font-size: 2rem; "></i> กราฟแสดงจำนวน Call กับ
                                            Ticket</h2>
                                    </div>
                                    <div class="card-body">

                                        <!-- ColumnChart    -->
                                        <div class="m-2" id="chart_div" style="width: 100%; height: 31.25rem;"></div>

                                        <!-- <p class="card-text">
                                        <h5><i class="bi bi-info-circle-fill" style="font-size: 1rem; color: red;"></i>
                                            : comment</h5>
                                        </p> -->
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 0.200rem green">
                                <div class="card">
                                    <div class="card-header text-light" style="background-color:#A30400;">
                                        <h2 class="text-center"><i class="bi bi-pie-chart-fill" style="font-size: 2rem;"></i> PIE CHART แสดงข้อมูลผลรวม</h2>
                                    </div>
                                    <div class="card-body">

                                        <!-- PieChart -->
                                        <div class="" id="chart" style="width: 100%; height: 500px;"></div>


                                        <ul class="list-group list-group-flush" style="width: 16rem;">
                                            <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">
                                                <i class="bi bi-info-circle-fill" style="font-size: 2rem; color: red;"></i>
                                                <h5>ข้อมูลการทำงานโดยรวม</h5>
                                            </li>

                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                TOTAL CALL :<span class="" style="font-size: 1rem;"><?= $tol['total1'];  ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                TOTAL TICKET :<span class="" style="font-size: 1rem;"><?= $tol['total2'];  ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="card">
                                    <div class="card-header text-light" style="background-color: #A30400;">
                                        <h2 class="text-center"><i class="bi bi-bar-chart-steps" style="font-size: 2rem;"></i> CHART AREA แสดง AHT</h2>
                                    </div>
                                    <div class="card-body">

                                        <!-- AreaChart -->
                                        <div id="chartbar" style="width: 100%; height: 500px;"></div>

                                        <p class="card-text">
                                        <h5><i class="bi bi-info-circle-fill" style="font-size: 1rem; color: red;"></i>
                                            : แสดงค่าเฉลี่ย AHT</h5>
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <a href="#dep" class="btn m-2" style="font-size: 1rem; color:aliceblue; background-color:#000000"><i class="bi bi-arrow-up"></i> กลับขึ้นข้างบน</a>
                </div>
            </div>

</body>

</html>