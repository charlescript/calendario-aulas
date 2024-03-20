<?php

// Incluir o arquivo com a conexão para o banco de dados
include_once './conexao.php';


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$query_cad_event = "INSERT INTO events (title, description, color, start, end) 
 VALUES (:title, :description, :color, :start, :end)";


$cad_event = $conn->prepare($query_cad_event);

$cad_event->bindParam(':title', $dados['cad_title']);
$cad_event->bindParam(':description', $dados['cad_description']);
$cad_event->bindParam(':color', $dados['cad_color']);
$cad_event->bindParam(':start', $dados['cad_start']);
$cad_event->bindParam(':end', $dados['cad_end']);

if($cad_event->execute()){
    $retorna = 
            ['status' => true,
                'msg' => 'Evento cadastrado com sucesso!',
                'id' => $conn->lastInsertId(),
                'title' => $dados['cad_title'],
                'description' => $dados['cad_description'],
                'color' => $dados['cad_color'],
                'start' => $dados['cad_start'],
                'end' => $dados['cad_end']
            ];
} else {

    $retorna = 
    [ 
        'status' => false,
        'msg' => 'Erro: Evento NÃO cadastrado no banco de dados !'
    ];

}

echo json_encode($retorna);