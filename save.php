<?php
// Permettre l'appel depuis le web (si nécessaire)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Lire le JSON envoyé
$input = file_get_contents('php://input');
if(!$input){
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'Aucun contenu reçu']);
  exit;
}

// Vérifier que le JSON est valide
$json = json_decode($input, true);
if($json === null && json_last_error() !== JSON_ERROR_NONE){
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'JSON invalide']);
  exit;
}

// Ecrire dans data.json (vérifie que le serveur a les droits d'écriture)
$file = __DIR__ . '/data.json';
$result = file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
if($result === false){
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Erreur écriture fichier']);
  exit;
}

// Réponse OK
echo json_encode(['status'=>'success']);
