<?php

        require('goconnect.php');
        if (isset($_GET['op'])) {
        $sql = "SELECT * FROM employee where Role='" . $_GET['op'] . "'";
        $result = mysqli_query($gcon, $sql);
        }else {
        $sql = "SELECT * FROM employee";
        $result = mysqli_query($gcon, $sql);
        }

        $sql2 = "SELECT role From employee GROUP BY role";
        $result2 = mysqli_query($gcon, $sql2);



?>


 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="g_index.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Operation
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">

                            <?php if (isset($_GET['op'])) { ?>
                                <li><a class="dropdown-item" href="g_index.php">รวม</a></li>
                            <?php  } ?>

                            <?php while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                                <li><a class="dropdown-item" href="g_index.php?op=<?= $row2['role']  ?>"><?= $row2['role']  ?></a></li>
                            <?php } ?>
                            <!-- <li><a class="dropdown-item" href="g_index.php?op=Operation1">Operation 1</a></li>
                            <li><a class="dropdown-item" href="g_index.php?op=Operation2">Operation 2</a></li>
                            <li><a class="dropdown-item" href="g_index.php?op=Operation3">Operation 3</a></li>
                            <li><a class="dropdown-item" href="g_index.php?op=Operation4">Operation 4</a></li> -->
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>