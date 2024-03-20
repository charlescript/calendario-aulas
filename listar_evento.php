<?php

// Incluindo o arquivo com a conexão com o banco de dados
include_once './conexao.php';

// Query para recuperar eventos
$query_events = "SELECT id, title, description, color, start, end from events";

//PREPARA A QUERY
$result_events = $conn->prepare($query_events);

// EXECUTA A QUERY
$result_events->execute();

// Criar o array que recebe os eventos
$eventos = [];

//PERCORRER A LISTA DE REGISTROS RETORNADO DO BANCO DE DADOS
while($row_events = $result_events->fetch(PDO::FETCH_ASSOC)){

    // Extrair o array
    extract($row_events);

    $eventos[] = [
        'id' => $id,
        'title' => $title,
        'description' => $description,
        'color' => $color,
        'start' => $start,
        'end' => $end,
    ];
}

echo json_encode($eventos);