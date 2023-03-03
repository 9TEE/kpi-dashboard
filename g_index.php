<?php
require('goconnect.php');

$sql = "SELECT * FROM employee";
$result = mysqli_query($gcon, $sql);


?>



<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

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
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["fname"]; ?></td>
                        <td><?php echo $row["lname"]; ?></td>
                        <td>
                            <a href="samechart.php?id=<?php echo $row["id"] ?>" class="btn btn-primary">รายละเอียด</a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>

        <!-- <a href="insert_From.php" class="btn btn-dark">เพิ่มข้อมูลพนักงาน</a> -->
    </div>
</body>

</html>