<?php

// Incluindo o arquivo com a conexão com o banco de dados
include_once './conexao.php';

// Receber o id do usuário provindo de 'custom.js'
$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT );


// Verificar se o parâmetro user_id foi enviado
if(!empty($user_id)){

    // Query para recuperar eventos fazendo inner joins com a tabela de resolução tb_event_user e tabela tb_users
    $query_events = "SELECT evt.id, evt.title, evt.description, evt.color, evt.start, evt.end,
    usr.id as user_id, usr.nome as user_nome, usr.email as user_email
    FROM tb_events as evt
        INNER JOIN tb_event_user AS ev_us ON ev_us.id_event = evt.id
            INNER JOIN tb_users as usr ON usr.id = ev_us.id_user
                WHERE usr.id = :user_id";

    //PREPARA A QUERY
    $result_events = $conn->prepare($query_events);

    // Atribuir o valor do parâmetro para a query acima, utilizando o 'PDO::PARAM_INT' para aceitar somente parametro do tipo inteiro
    $result_events->bindParam(':user_id', $user_id, PDO::PARAM_INT);

} else {

    // Query para recuperar eventos fazendo inner joins com a tabela de resolução tb_event_user e tabela tb_users
    $query_events = "SELECT evt.id, evt.title, evt.description, evt.color, evt.start, evt.end,
    usr.id as user_id, usr.nome as user_nome, usr.email as user_email
    FROM tb_events as evt
        INNER JOIN tb_event_user AS ev_us ON ev_us.id_event = evt.id
            INNER JOIN tb_users as usr ON usr.id = ev_us.id_user";

    //PREPARA A QUERY
    $result_events = $conn->prepare($query_events);

}



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