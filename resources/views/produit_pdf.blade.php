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
    <h1>{{ $produit->nom_produit }}</h1>
    <p><strong>Description :</strong> {{ $produit->description_produit }}</p>
    <p><strong>Unité :</strong> {{ $produit->unite }}</p>
    <p><strong>Prix Unitaire :</strong> {{ $produit->prix_unitaire }} €</p>
    <p><strong>Date de création :</strong> {{ $produit->created_at->format('d/m/Y') }}</p>
</body>
</html>
