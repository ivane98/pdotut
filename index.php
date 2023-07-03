<?php 

$newcity = filter_input(INPUT_POST, 'newcity', FILTER_SANITIZE_STRING);
$countrycode = filter_input(INPUT_POST, 'countrycode', FILTER_SANITIZE_STRING);
$district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_STRING);
$population = filter_input(INPUT_POST, 'population', FILTER_SANITIZE_STRING);

$city = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_STRING);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <main>
        <header>PDO Crud</header>
        <?php 
        if(isset($deleted)){
            echo 'deleted'.'<br/><br/>';
        }else if(isset($updated)){
            echo 'updated'.'<br/><br/>';
        }
        
        ?>
        <?php if(!$city && !$newcity) { ?>
            <section>
                <h1>get the city name</h1>

                <form action="." method="GET">

                    <label for="city">City Name:</label>
                    <input type="text" name="city" id="city" required>
                    <button>Submit</button>

                </form>
            </section>

            <section>
                <h1>Insert or update the city</h1>

                <form action="." method="POST">
            
                    <label for="newcity">City Name:</label>
                    <input type="text" name="newcity" id="newcity" required>
                    <label for="countrycode">Country Code:</label>
                    <input type="text" name="countrycode" id="countrycode" maxlength="3" required>
                    <label for="district">District:</label>
                    <input type="text" name="district" id="district" required>
                    <label for="population">Population:</label>
                    <input type="text" name="population" id="population" required>
                    <button>Submit</button>
            
            </form>
            </section>
        <?php } else { ?>
            <?php require('database.php') ?>

            <?php

                if($newcity){
                    $query = 'INSERT INTO city (Name, CountryCode, District, Population) VALUES (:newcity, :countrycode, :district, :population)';
                    $statement = $db->prepare($query);
                    $statement->bindValue(':newcity', $newcity);
                    $statement->bindValue(':countrycode', $countrycode);
                    $statement->bindValue(':district', $district);
                    $statement->bindValue(':population', $population);

                    $statement->execute();
                    $statement->closeCursor();
                }


                if($city || $newcity){
                    $query = 'SELECT * FROM city WHERE NAME = :city ORDER BY Population DESC';
                    $statement = $db->prepare($query);
                    if($city){
                        $statement->bindValue(':city', $city);
                    }else {
                        $statement->bindValue(':city', $newcity);
                    }
                    $statement->execute();
                    $results = $statement->fetchAll();
                    $statement->closeCursor();

                }
            ?>

            <?php if(!empty($results)) { ?>
                <section>
                    <h2>update or delete city</h2>
                    <?php foreach($results as $result){
                        $id = $result['ID'];
                        $city = $result['Name'];
                        $countrycode = $result['CountryCode'];
                        $district = $result['District'];
                        $population = $result['Population'];

                    
                    ?>

                    <form class="update" action="update_record.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id ?>">

                        <label for="city-<?php echo $id ?>">City Name:</label>
                        <input type="text" id="city-<?php echo $id ?>" name="city" value="<?php echo $city ?>">

                        <label for="countrycode-<?php echo $id ?>">Country Code:</label>
                        <input type="text" id="countrycode-<?php echo $id ?>" name="countrycode" value="<?php echo $countrycode ?>">

                        <label for="district-<?php echo $id ?>">Disctrict:</label>
                        <input type="text" id="district-<?php echo $id ?>" name="district" value="<?php echo $district ?>">

                        <label for="population-<?php echo $id ?>">Population:</label>
                        <input type="text" id="population-<?php echo $id ?>" name="population" value="<?php echo $population ?>">

                        <button>Update</button>

                    </form>

                    <form calss='delete' action="delete_record.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <button class="red">Delete</button>
                    </form>
                    <?php  }?>

                </section>
            
            <?php } else { ?>
                <p>Sorry, no results</p>
            <?php } ?>
                <a href=".">go to dashboard</a>
        <?php } ?>
    </main>
</body>

</html>