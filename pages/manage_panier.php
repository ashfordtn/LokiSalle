<?php
session_start();
if (!isset($_SESSION['panier'])) {
  $_SESSION['panier'] = array();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productId = $_POST['productId'];

  foreach ($_SESSION['panier'] as $product) {
    if ($product == $productId) {
      $response = array(
        'success' => false,
        'message' => 'Produit déjà dans le panier.'
      );
      header('Content-Type: application/json');
      echo json_encode($response);
      exit();
    }
  }
  $_SESSION['panier'][] = $productId;
  $response = array(
    'success' => true,
    'message' => 'Produit ajouté au panier avec succès.'
  );
  header('Content-Type: application/json');
  echo json_encode($response);
  exit();
}

if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
  $removeID = $_GET['id'];

  if (($key = array_search($removeID, $_SESSION['panier'])) !== false) {
    unset($_SESSION['panier'][$key]);
    $response = array(
      'success' => true,
      'message' => 'Produit supprimé du panier avec succès.'
    );
  } else {
    $response = array(
      'success' => false,
      'message' => 'Échec de la suppression du produit du panier.'
    );
  }

  header('Content-Type: application/json');
  echo json_encode($response);
  exit();
}

if ($_GET['action'] === 'vider') {
  $_SESSION['panier'] = array();
  $response = array(
    'success' => true,
    'message' => 'Panier vidé avec succès.'
  );
  header('Content-Type: application/json');
  echo json_encode($response);
  exit();
}
?>