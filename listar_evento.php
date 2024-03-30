<?php

// Incluindo o arquivo com a conexão com o banco de dados
include_once './conexao.php';

// Query para recuperar eventos
$query_events = "SELECT evt.id, evt.title, evt.description, evt.color, evt.start, evt.end,
                  usr.id as user_id, usr.nome as user_nome, usr.email as user_email
                    FROM tb_events as evt
                        INNER JOIN tb_event_user AS ev_us ON ev_us.id_event = evt.id
                            INNER JOIN tb_users as usr ON usr.id = ev_us.id_user";

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
        'user_id' => $user_id,
        'user_nome' => $user_nome,
        'user_email' => $user_email,
    ];
}

echo json_encode($eventos);