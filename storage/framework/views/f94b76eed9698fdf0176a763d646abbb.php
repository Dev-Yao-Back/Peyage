<!DOCTYPE html>
<html>
<head>
    <title>Fiche Produit</title>
    <style>
        /* Styles pour le PDF */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1><?php echo e($produit->nom_produit); ?></h1>
    <p><strong>Description :</strong> <?php echo e($produit->description_produit); ?></p>
    <p><strong>Unité :</strong> <?php echo e($produit->unite); ?></p>
    <p><strong>Prix Unitaire :</strong> <?php echo e($produit->prix_unitaire); ?> €</p>
    <p><strong>Date de création :</strong> <?php echo e($produit->created_at->format('d/m/Y')); ?></p>
</body>
</html>
<?php /**PATH /home/yao/Documents/G-Peyage/resources/views/produit_pdf.blade.php ENDPATH**/ ?>