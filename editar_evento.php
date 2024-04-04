<?php

// Incluir o arquivo com a conexão para o banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


// Recuperar os dados do usuário do banco de dados
$query_user = "SELECT id, nome, email FROM tb_users WHERE id = :id LIMIT 1";

// Prepara a QUERY
$result_user = $conn->prepare($query_user);

// Substituir o link pelo valor
$result_user->bindParam(':id', $dados['edit_user_id']);

// Executar a query
$result_user->execute();

// Ler os dados do usuário
$row_user = $result_user->fetch(PDO::FETCH_ASSOC);

//////////////////////////////////////////////////////////////////////////////

// Recuperar os dados da turma do banco de dados
$query_turma = "SELECT id, nome, descricao FROM tb_turma WHERE id = :id LIMIT 1";

// Prepara a QUERY
$result_turma = $conn->prepare($query_turma);

// Substituir o link pelo valor
$result_turma->bindParam(':id', $dados['edit_turma_id']);

// Executar a query
$result_turma->execute();

// Ler os dados do usuário
$row_turma = $result_turma->fetch(PDO::FETCH_ASSOC);

//////////////////////////////////////////////////////////////////////////////


// Criar a QUERY para editar o evento na tabela tb_events
$query_edit_event = "UPDATE tb_events SET 
                        title = :title,
                        description = :description,
                        color = :color,
                        start = :start, 
                        end = :end 
                    WHERE id = :id";

// Prepara a QUERY
$edit_event = $conn->prepare($query_edit_event);

// Substituir os parâmetros pelos valores
$edit_event->bindParam(':title', $dados['edit_title']);
$edit_event->bindParam(':description', $dados['edit_description']);
$edit_event->bindParam(':color', $dados['edit_color']);
$edit_event->bindParam(':start', $dados['edit_start']);
$edit_event->bindParam(':end', $dados['edit_end']);
$edit_event->bindParam(':id', $dados['edit_id']);

// Verificar se ocorreu a edição corretamente
if ($edit_event->execute()) {
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Criar a QUERY para editar a associação na tabela tb_event_user
    $query_update_association = "UPDATE tb_event_user SET id_user = :id_user
                                  WHERE id_event = :id_event";

    // Preparar a QUERY
    $update_association = $conn->prepare($query_update_association);

    // Substituir os parâmetros pelos valores
    $update_association->bindParam(':id_user', $dados['edit_user_id']);
    $update_association->bindParam(':id_event', $dados['edit_id']);

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Criar a QUERY para editar a associação na tabela tb_turma_event
    $query_update_association_turma_event = "UPDATE tb_turma_event SET id_turma = :id_turma, id_event = :id_event
                                  WHERE id_event = :id_event";

    // Preparar a QUERY
    $update_association_turma = $conn->prepare($query_update_association_turma_event);

    // Substituir os parâmetros pelos valores
    $update_association_turma->bindParam(':id_turma', $dados['edit_turma_id']);
    $update_association_turma->bindParam(':id_event', $dados['edit_id']);

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Executar a atualização da associação
    if ( $update_association->execute() AND $update_association_turma->execute() ) {

        $retorna = [
            'status' => true,
            'msg' => 'Evento e associação atualizados com sucesso!',
            'id' => $dados['edit_id'],
            'title' => $dados['edit_title'],
            'description' => $dados['edit_description'],
            'color' => $dados['edit_color'],
            'start' => $dados['edit_start'],
            'end' => $dados['edit_end'],
            'user_id' => $row_user['id'],
            'user_nome' => $row_user['nome'],
            'user_email' => $row_user['email'],
            'turma_id' => $row_turma['id'],
            'turma_nome' => $row_turma['nome'],
            'turma_descricao' => $row_turma['descricao'],

        ];
    } else {
        $retorna = [
            'status' => false,
            'msg' => 'Erro: Associação entre o evento e o usuário não atualizada!'
        ];
    }

} else {
    $retorna = [
        'status' => false,
        'msg' => 'Erro: Evento não atualizado no banco de dados!'
    ];
}

echo json_encode($retorna);





// // Incluir o arquivo com a conexão para o banco de dados
// include_once './conexao.php';

// // Receber os dados enviados pelo JavasCript
// $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


// // Criar a QUERY editar evento no banco de dados
// $query_edit_event = " UPDATE tb_events SET 
//                         title = :title,
//                         description = :description,
//                         color = :color,
//                         start = :start, 
//                         end = :end 
//                     WHERE id = :id";

// // Prepara a QUERY
// $edit_event = $conn->prepare($query_edit_event);


// // Substituir o link pelo valor
// $edit_event->bindParam(':title', $dados['edit_title']);
// $edit_event->bindParam(':description', $dados['edit_description']);
// $edit_event->bindParam(':color', $dados['edit_color']);
// $edit_event->bindParam(':start', $dados['edit_start']);
// $edit_event->bindParam(':end', $dados['edit_end']);
// $edit_event->bindParam(':id', $dados['edit_id']);


// // Verifica se ocorreu a edição corretamente
// if($edit_event->execute()){


//     // Recupera o ID do ultimo evento atualizado
//     $id_event = $conn->lastInsertId();

//     // Query para cadastras associação entre o evento e o usuário na tabela tb_event_user
//     $query_update_association = "UPDATE tb_event_user SET :id_user 
//         WHERE id_event = :id_event AND id_user = :id_user";
    
//     $update_association = $conn->prepare($query_update_association);

//     // Bind dos parâmetros para associação
//     $update_association->bindParam(':id_event', $id_event);
//     $update_association->bindParam(':id_user', $dados['edit_user_id']);


//     // Executar a atualização de associação
//     if($update_association->execute()) {


//         $retorna = 
//                 ['status' => true,
//                     'msg' => 'Evento editado com sucesso!',
//                     'id' => $dados['edit_id'],
//                     'title' => $dados['edit_title'],
//                     'description' => $dados['edit_description'],
//                     'color' => $dados['edit_color'],
//                     'start' => $dados['edit_start'],
//                     'end' => $dados['edit_end']
//                 ];
//     } else {
//         // Se a inserção da associação falhar, você pode tratar o erro aqui
//         $retorna = [
//             'status' => false,
//             'msg' => 'Erro: Associação entre o evento e o usuário não cadastrada!'
//         ];
//     }

// } else {

//     $retorna = 
//     [ 
//         'status' => false,
//         'msg' => 'Erro: Evento NÃO editado no banco de dados !'
//     ];

// }

// echo json_encode($retorna);