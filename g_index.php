<?php
require('goconnect.php');

$sql = "SELECT * FROM employee";
$result = mysqli_query($gcon, $sql);

$tal = "SELECT SUM(pcall) AS total1,              
               SUM(ticket) AS total2           
               FROM edata where 1"; //แสดงผลรวม ของค่า call or ticket         

$rs = mysqli_query($gcon, $tal); //แสดงค่าออกมาตามที่เรากำหนดตัวแปร $tol 
$tol = mysqli_fetch_assoc($rs);
// echo $tol['total1'] ;
?>



<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

    <!--คำสั่งติดตั้ง Jquery -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="//code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>


    <title>Dashbord</title>
</head>

<body>
    <script>
        $(document).ready(function() {
            $('#data').DataTable();
        });
    </script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container">
        <h1 class="text-center">ข้อมูล</h1>
        <!-- <form action="insdata.php" class="form-group" method="POST">
            <label for="">ค้นหาพนักงาน</label>
            <input type="text" placeholder="ป้อนชื่อพนักงาน" class="form-control" name="ename" value="">
            <input type="submit" value="Search" class="btn btn-info my-2">
        </form> -->
        <table class="table" id="data">
            <thead class="table-danger">
                <tr>
                    <th class="text-center" style="width: 6rem;">ลำดับที่</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th class="text-center">ตำแหน่ง</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $order = 1;
                while ($row = mysqli_fetch_assoc($result)) {

                    $tal = "SELECT SUM(pcall) AS total1,              
               SUM(ticket) AS total2           
               FROM edata where emp = '" . $row["id"] . "'"; //แสดงผลรวม ของค่า call or ticket         

                    $rs = mysqli_query($gcon, $tal); //แสดงค่าออกมาตามที่เรากำหนดตัวแปร $tol 
                    $tol = mysqli_fetch_assoc($rs);
                    // echo $tol['total1']; 
                    // 
                ?>

                    <tr>
                        <td class="text-end"><?php echo $order++; ?></td>
                        <td class=""><?php echo $row["fname"]; ?></td>
                        <td><?php echo $row["lname"]; ?></td>
                        <td class="text-center"><span class="badge text-bg-success" style="width: 7rem; height:2rem;"><?php echo $row["role"]; ?></span></td>
                        <td class="text-center">
                            <?php if ($tol['total2'] >= $tol['total1']) { ?>

                                <span class="badge text-bg-success" style="width: 2rem; height:2rem;">
                                    <h6><span class="lnr lnr-smile "></span>
                                </span></h6>
                            <?php } else { ?>
                                <span class="badge text-bg-danger" style="width: 2rem; height:2rem;">
                                    <h6><span class="lnr lnr-sad"></span>
                                </span></h6>

                            <?php    } ?>
                        </td>
                        <td class="text-center">
                            <?php //if ($tol['total2'] >= $tol['total1']) { 
                            ?>
                            <a href="samechart.php?id=<?= $row["id"] ?>" class="btn btn-info"><span class="lnr lnr-list"></span> รายละเอียด</a>

                            <?php //} else { 
                            ?>

                            <!-- <a href="samechart.php?id=<?= $row["id"] ?>" class="btn btn-danger"><span class="lnr lnr-list"></span> รายละเอียด</a> -->

                            <?php   // } 
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>

        <!-- <a href="insert_From.php" class="btn btn-dark">เพิ่มข้อมูลพนักงาน</a> -->
    </div>
</body>

</html>