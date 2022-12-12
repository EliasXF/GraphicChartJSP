<?php

$username = "root";
$password = "";
$database = "biblioteca";

try {
    $pdo = new PDO("mysql:host=localhost; database=$database", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR!" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating DB connection</title>
    <style type="text/css">
        .chartBox{
            width: 700px;
        }
    </style>
</head>
<body>

    <?php
    try{
        $sql = 
        "SELECT * FROM biblioteca.descriptionlabels
        INNER JOIN biblioteca.datapoints
        ON descriptionlabels.id = datapoints.descriptionlabelid
        ";


        $result = $pdo->query($sql);
        if($result->rowCount() > 0) {
            $revenue = array();
            $labelaxis = array();
            while($row = $result->fetch()){
                //echo json_encode($row['COL 6']);
                $revenue[] = $row["datapoint"];
                $labelaxis[] = ucwords($row["labelaxis"]);
                $descriptionlabel = ucwords($row["descriptionlabel"]);
                $bgcolor = $row["bgcolor"];
                $bordercolor = $row["bordercolor"];
            }
        unset($result);
        } else {
            echo "Registros no encontrados";
        }
    } catch(PDOException $e){
        die("ERROR!" . $e->getMessage());
    }
    unset($pdo);
    ?>


    
    <!-- variable para mostrar los graficos -->
    <div class="chartBox">
     <canvas id="myChart"></canvas>
    </div>

    <div class="buttonBox">
        <button onclick="showData(5)">Mostrar 5 elementos</button>
        <button onclick="showData(7)">Mostrar 7 elementos </button>
        <button onclick="showData()">Restaurar data</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

    //Setup block 
    const revenue =<?php echo json_encode($revenue); ?>;
    const labelaxis =<?php echo json_encode($labelaxis); ?>;
    const descriptionlabel =<?php echo json_encode($descriptionlabel); ?>;
    const bgcolor =<?php echo json_encode($bgcolor); ?>;
    const bordercolor =<?php echo json_encode($bordercolor); ?>;
    const data = {
        labels: labelaxis,
        datasets: [{
            label: descriptionlabel,
            data: revenue,
            background: bgcolor,
            borderColor: bordercolor,
            borderWidth: 1
        }]
    };

    //Configuration block
    const config = {
        type: 'bar',
        data,
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        }
    }

    //Render block
    const myChart = new Chart(
        document.getElementById('myChart'),
        config 
    );

    function showData(num) {
        const revenueSliced = revenue.slice(0, num);
        const labelaxisSliced = labelaxis.slice(0, num);
        myChart.data.datasets[0].data = revenueSliced;
        myChart.data.labels = labelaxisSliced;
        myChart.update();
    };

    function resetData() {
        myChart.data.datasets[0].data = revenueSliced;
        myChart.data.labels = labelaxisSliced;
        myChart.update();
    };

    </script>

</body>
</html>