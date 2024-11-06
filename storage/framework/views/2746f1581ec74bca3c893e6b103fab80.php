<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>

<style>

*{

}
body{
background-color:#ffffff;
padding:0;
margin:0;
color:black;
}
.containerdata{
    background-color:rgb(255, 251, 251);
    padding:5%;
    margin:5%;
    width:80%;
    height:85%;
    /* box-shadow: 10px 5px 5px rgba(0, 0, 0, 0.5); */
    border-radius:2px;

}
h1,h3{
    text-align: left;
    /* Letter-spacing: 2px; */
    color: darkgreen;
    font-size: 10px;
}
h4{
    text-align: left;
    /* Letter-spacing: 2px; */
    color: rgb(12, 11, 34);
    font-size: 13px;
}
h5{
    text-align: right;

    color: darkgreen;
}
.code1{
    background-color:blue;
    width:10px;
    height:30px;
}

  /** tableau */




        /** terme de confidentialité */
p{

    position: fixed;
  bottom: 40px;
  margin-right:1px;
  margin-left: 1px;

}



.pesage-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.pesage-item {
    display: flex;
    align-items: baseline;
    gap: 10px;
     font-size: 10px;
}

.pesage-value {
    color: red;
    font-size: 10px;
    font-weight: bold;
}

.pesage-time {
    font-size: 14px;
    color: gray;
}

h4 {
    margin: 0;
    font-weight: normal;
}

.qr-code {
    position:absolute;
    bottom:228px;
    right: 180px;
}

.montant-total{
    position: absolute;
    bottom: 110px;
   left: 100px;
   color:black;
}

.operation{
    font-size: 11px;
    color:black;
}
</style>
</head>
<body>


<div class="containerdata">

<h1>Entrepise : G-Payage</h1>
<h3>Telephone : +(225) 0708221712</h3>
<h3>Mail : gpayage@gmail.com</h3>

<hr style="border:3px solid #faf1ea">

<!--
<h5>agro-ponds</h5> -->


<h4 > CODE : <?php echo e($operation->code); ?>   </h4>
<h4> DATE : 23 octobre,2024 </h4>
<h4> TYPE D'OPERATION : <i><?php echo e($operation->type_operation); ?> </i></h4>
<hr style="border:3px solid #faf1ea">


<div class="table">

<div class="container">
    <div class="operation">
        <h1>OPERATIONS</h1>
        <ul>
            <li><strong>Véhicule:</strong> <?php echo e($operation->numero_vehcule); ?></li>
            <li><strong>Conducteur:</strong> <?php echo e($operation->transporteur->nom_transporteur); ?></li>
            <li><strong>Fournisseur:</strong> <?php echo e($operation->fournisseur->nom_fournisseur); ?></li>
            <li><strong>Produit:</strong> <?php echo e($operation->produit->nom_produit); ?></li>
            <li><strong>Provenance:</strong> <?php echo e($operation->provenance->nom_provenance); ?></li>

        </ul>
    </div>

        </div>

        <h1>PESAGE</h1>
<div class="pesage-container">
    <div class="pesage-item">
        <h6>Poids 1:</h6>

        <span class="pesage-value"> <?php echo e($operation->poids1); ?>  KG   </span>
        

        <h6>Poids 2:</h6>
        <span class="pesage-value"><?php echo e($operation->poids2); ?>   KG   </span>
        

        <h6>Poids-NET:</h6>
        <span class="pesage-value"><?php echo e($operation-> poidsnet); ?> / <?php echo e($operation-> poidsnet); ?>FCFA </span>


    </div>



</div>




<div class="qr-code">
    <?php
        // Générer le QR code avec le numéro de véhicule
        $qrCodeImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(50)->generate($operation->code);
    ?>
  <h3>
    <br></h3>  <img src="data:image/png;base64, <?php echo e(base64_encode($qrCodeImage)); ?>" alt="QR Code">
   <i> Scan Me</i>
</div>


        <p style="font-size:13px">
        

    </p>



















    <hr style="border: 1px dotted #000000;">


<h1>EXPERT COUPON</h1>



<!--
<h5>agro-ponds</h5> -->


<h4 > FACTURE  : <?php echo e($operation->code); ?>   </h4>
<h4> Le, 23 octobre,2024 </h4>
<h4> siège social situé à Abidjan-Marcory Zone 4, Angle Rue Pierre Marie Curie,</h4>



</div>
</div>
<footer>

</footer>
</div>



</body>

</html>
<?php /**PATH /home/yao/Documents/G-Peyage/resources/views/operation_pdf.blade.php ENDPATH**/ ?>