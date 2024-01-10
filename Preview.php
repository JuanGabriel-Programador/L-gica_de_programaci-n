<?php
include('./modelo/ConexionBD.php');

if (isset($_POST['txt_id'])) {
    $txt_Id=$_POST['txt_id'];
    echo ("Lo hemos hecho");
}else{
    echo "<script type='text/javascript'>
alert('Se ha producido un error. Por favor, inténtalo de nuevo.');
window.history.back();
</script>";
exit();
}

$sqlSentencia=$conexion->prepare("SELECT * FROM cursos WHERE Id=$txt_Id");
$sqlSentencia->execute();
$cursosm=$sqlSentencia->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview</title>
</head>
<body>
    <?php foreach($cursosm as $curso){?>
<iframe width="700" height="500" src="https://www.youtube.com/embed/<?= $curso['Video'];?>" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
<?php } ?>
</body>
</html>