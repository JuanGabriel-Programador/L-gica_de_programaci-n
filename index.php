<?php
include("./modelo/ConexionBD.php"); ?>
<?php
$txt_Id=(isset($_POST['txt_id']))?$_POST['txt_id']:"";
$txt_Titulo=(isset($_POST['Titulo']))?$_POST['Titulo']:"";
$txt_Desc=(isset($_POST['Descripcion']))?$_POST['Descripcion']:"";
$txt_Precio=(isset($_POST['Precio']))?$_POST['Precio']:"";
$txt_categoria=(isset($_POST['Opcion']))?$_POST['Opcion']:"";

$Img=(isset($_FILES['Imagen']['name']))?$_FILES['Imagen']['name']:"";
$Video=(isset($_POST['Video']))?$_POST['Video']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
switch($accion){
    case 'Agregar':
        $SQL_C=$conexion->prepare("INSERT INTO `cursos`(`Id`, `Titulo`, `Descripcion`, `Imagen_promocional`, `Video`, `Precio`,`AreaCuerpo`) VALUES (NULL,'$txt_Titulo','$txt_Desc',:imagen,'$Video','$txt_Precio','$txt_categoria')");
        
        $numero_aleatorio=rand(1,100);
        $archivoImagen=($Img!="")?$numero_aleatorio."_".$_FILES["Imagen"]["name"]:"Imagen.png";
        $tmpImagen=$_FILES["Imagen"]["tmp_name"];
        echo $tmpImagen;
        if ($tmpImagen!="") {
            move_uploaded_file($tmpImagen,"./Img/".$archivoImagen);
        }
        $SQL_C->bindParam(':imagen',$archivoImagen);
        $SQL_C->execute();
        break;
    case 'Modificar':
        echo $txt_Id;

        $SQL_C=$conexion->prepare("UPDATE `cursos` SET `Titulo` = '$txt_Titulo',`Descripcion`='$txt_Desc', `Precio`='$txt_Precio' WHERE `cursos`.`Id` = $txt_Id;
        ");
        $SQL_C->execute();

        if ($Img!="") {
            $SQL_C=$conexion->prepare("UPDATE `cursos` SET `Imagen_promocional` = '$Img' WHERE `cursos`.`Id` = $txt_Id;
            ");
            $SQL_C->execute();
        }
        if ($Video!="") {
            $SQL_C=$conexion->prepare("UPDATE `cursos` SET `Video` = '$Video' WHERE `cursos`.`Id` = $txt_Id;
            ");
            $SQL_C->execute();
        }
        echo "Presiono 2";
        
        break;    
    case 'Cancelar':
       echo "Presiono 3";
    break;
    case 'Cambiar':
        $SQL_C=$conexion->prepare("SELECT * FROM cursos WHERE Id=$txt_Id;");
        $SQL_C->execute();
        $cursos=$SQL_C->fetch(PDO::FETCH_LAZY);
        $txt_Titulo=$cursos['Titulo'];
        $txt_Desc=$cursos['Descripcion'];
        $Img=$cursos['Imagen_promocional'];
        $Video=$cursos['Video'];
        $txt_Precio=$cursos['Precio'];
     break; 
     case 'Eliminar':
        $SQL_C=$conexion->prepare("DELETE FROM `cursos` WHERE `cursos`.`Id` = $txt_Id;
        ");
        $SQL_C->execute();
        echo "Presiono 5".$txt_Id;
     break; 
        
}

$sentenciaSQL=$conexion->prepare("SELECT * FROM cursos");
$sentenciaSQL->execute();
$todos_los_cursos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
$sentenciaCategoria=$conexion->prepare("SELECT * FROM categorias");
$sentenciaCategoria->execute();
$cat=$sentenciaCategoria->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Crud-php</title>
</head>
<body>
    <h1 class="text-center p-4">Crud PHP</h1>
    <div class="container-fluid row b-4 Modificar">
        <div class="card col-12 colm-md-8 col-lg-4">
            <div class="card-header">
                Datos de los Cursos
            </div>
            <div class="card-body">
            <form  action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                    <input type="hidden" class="form-control" name="txt_id" value="<?php echo $txt_Id;?>" >
            </div>
            <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Titulo del video</label>
                    <input type="text" class="form-control" name="Titulo" id="text_titulo" value="<?php echo $txt_Titulo;?>" required>
            </div>

            <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion</label>
                    <textarea name="Descripcion" class="form-control"  cols="30" rows="10" placeholder="Escriba la descripcion" ><?php echo $txt_Desc;?></textarea>
                </div>

            <div class="mb-3">
                    <label for="imagen" class="form-label">Subir Imagen Promocional :<?php echo $Img;?></label><br>
                    <div class="FileImagen_oculta">
                    <input type="file" id="fileImagen" accept="image/jpeg, image/png , image/jpg" class="form-control" name="Imagen" />

                    </div>
            </div>

            <div class="mb-3">
                    <label for="video" class="form-label">Subir URL del video :<?php echo $Video;?></label><br>
                    <input type="text" class="form-control" name="Video" required/>
            </div>
            <div class="mb-3">
                    <label for="Area" class="form-label">Categoria:<?php echo $Video;?></label><br>
                    <select class="form-select" aria-label="Default select example" >
                        <option selected>Elije categoria</option>
                        <?php foreach ($cat as $categoria) {?>
                        <option value="<?= $categoria['Id']?>" name="Opcion"><?= $categoria['Categorias']?></option>
                        <?php }?>
                    </select>
            </div>
            <div class="mb-3">
                    <label for="Precio" class="form-label">Precio</label><br>
                    <input type="number" class="form-control" name="Precio" placeholder="Ejemplo: 3000 ARS" value="<?php echo $txt_Precio;?>" required/>
            </div>
            <div class="btn-group" role="group">
            <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
            <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button>
            <button type="submit" name="accion" value="Cancelar" class="btn btn-danger">Cancelar</button>
            </div>
            
        </form>
            </div>
        </div>
        
        
    </div>
    <div class="Modificar">
    <div class="col-12 col-lg-10  ">
    <div class="container-cursos">
    <h2 class="main-title">Mis cursos</h2>
    <section class="container-products">
    <?php foreach($todos_los_cursos as $cursos){?>
    <div class="product">
        <div class="contenedor_imagen">
        <img src="Img/<?= $cursos['Imagen_promocional'];?>" alt="" class="product__img">
        </div>
        <div class="contedor_toda_descripcion">

        <div class="product__description">
          <h4 class="product__title"><?= $cursos['Titulo'];?></h4>
          <span class="product__price">$<?= number_format($cursos['Precio'],2,'.',',');?></</span>
        </div>
        <div class="product__agregar">
            <form action="" method="post">
                    <input type="hidden" name="txt_id" id="txt_id" value="<?= $cursos['Id'];?>">
                    <input type="submit" class="btn btn-info pr" value="Cambiar" name="accion">
                    <input type="submit" class="btn btn-danger pr" value="Eliminar" name="accion">
            </form>
            <form action="Preview.php" method="post">
                    <input type="hidden" name="txt_id" id="txt_id" value="<?= $cursos['Id'];?>">
                    <input type="submit" name="accion" value="Ver" class="btn btn-success pr" >
            </form>
        </div>
        </div>
      </div>
      <?php }?>
    </section>
    </div>
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script> 
    var vid = document.getElementById ("video_1");
    function playVid () { vid.play (); } 
    function pauseVid () { vid.pause (); } 
    </script>

</html>