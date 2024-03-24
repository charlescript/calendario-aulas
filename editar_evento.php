<?php

// Incluir o arquivo com a conexão para o banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavasCript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


// Criar a QUERY editar evento no banco de dados
$query_edit_event = " UPDATE events SET 
                        title = :title,
                        description = :description,
                        color = :color,
                        start = :start, 
                        end = :end 
                    WHERE id = :id";

// Prepara a QUERY
$edit_event = $conn->prepare($query_edit_event);


// Substituir o link pelo valor
$edit_event->bindParam(':title', $dados['edit_title']);
$edit_event->bindParam(':description', $dados['edit_description']);
$edit_event->bindParam(':color', $dados['edit_color']);
$edit_event->bindParam(':start', $dados['edit_start']);
$edit_event->bindParam(':end', $dados['edit_end']);
$edit_event->bindParam(':id', $dados['edit_id']);


// Verifica se ocorreu a edição corretamente
if($edit_event->execute()){
    $retorna = 
            ['status' => true,
                'msg' => 'Evento editado com sucesso!',
                'id' => $dados['edit_id'],
                'title' => $dados['edit_title'],
                'description' => $dados['edit_description'],
                'color' => $dados['edit_color'],
                'start' => $dados['edit_start'],
                'end' => $dados['edit_end']
            ];
} else {

    $retorna = 
    [ 
        'status' => false,
        'msg' => 'Erro: Evento NÃO editado no banco de dados !'
    ];

}

echo json_encode($retorna);