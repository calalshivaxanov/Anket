<?php

require_once "system/config.php";

?>

<hmtl>
    <head>
        <title>Anket Yarat</title>
        <link rel="stylesheet" href="dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="dist/css/style.css">
    </head>
    <body>



    <div class="header">
        Anket Yarat
    </div>


<?php
$surveyid = 13;

if(isset($_POST['ankethazir']))
{
$correctNumber = 0;
$wrongNumber = 0;
$answers = $_POST['answers'];

foreach($answers as $question => $answer)
{
    //$question --> sualın id`si
    //$answer   -->  cavabın id`si
    $neticeSorgu = $baglanti->db->prepare("SELECT * FROM questions JOIN answers on questions.id = answers.questionid WHERE questions.id = ? and questions.surveyid = ? and answers.correct = 1");
    $neticeSorgu->execute(array($question,$surveyid));
    $netice = $neticeSorgu->fetch(PDO::FETCH_ASSOC);

    if($netice['id'] == $answer)
    {
        $correctNumber += 1;
    }
    else
    {
        $wrongNumber += 1;
    }

}
    echo 'Anket nəticənizdə '.$correctNumber.' Doğru, '.$wrongNumber.' yanlış cavab var...';

}

?>




    <div class="container">
        <div class="row">
            <div class="col-md-12">

    <form action="" method="POST">

    <?php


    $suallarSorgu = $baglanti->db->prepare("SELECT * FROM questions WHERE surveyid = ?");
    $suallarSorgu->execute(array($surveyid));
    $suallar = $suallarSorgu->fetchAll(PDO::FETCH_ASSOC);

    foreach ($suallar as $k => $v) //Suallar üçün
    {
        $questionsid = $v['id'];

        echo '<div class="list">'.$v['name'].'</div>';

        $cavablarSorgu = $baglanti->db->prepare("SELECT * FROM answers WHERE questionid = ?");
        $cavablarSorgu->execute(array($questionsid));
        $cavablar = $cavablarSorgu->fetchAll(PDO::FETCH_ASSOC);

        foreach($cevaplar as $k2 => $v2) //Cavablar üçün
        {
            echo '<div class="list--answers"><input required type="radio" value="'.$v2['id'].'" name="answers['.$questionsid.']">'.$v2['name'].'</div>';
        }

    }
    ?>


    <input style="margin-top: 10px;" type="submit" name="ankethazir" value="Hazır">


    </form>

            </div>
        </div>
    </div>

    </body>
</hmtl>