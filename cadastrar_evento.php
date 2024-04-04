<?php


// Incluir o arquivo com a conexão para o banco de dados
include_once './conexao.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


// Recuperar os dados do usuário do banco de dados
$query_user = "SELECT id, nome, email FROM tb_users WHERE id = :id LIMIT 1";

// Prepara a QUERY
$result_user = $conn->prepare($query_user);

// Substituir o link pelo valor
$result_user->bindParam(':id', $dados['cad_user_id']);

// Executar a query
$result_user->execute();

// Ler os dados do usuário
$row_user = $result_user->fetch(PDO::FETCH_ASSOC);

//////////////////////////////////////////////////////////////////////////////////


// Recuperar os dados da turma do banco de dados
$query_turma = "SELECT id, nome, descricao FROM tb_turma WHERE id = :id LIMIT 1";

// Prepara a QUERY
$result_turma = $conn->prepare($query_turma);

// Substituir o link pelo valor
$result_turma->bindParam(':id', $dados['cad_turma_id']);

// Executar a query
$result_turma->execute();

// Ler os dados da turma
$row_turma = $result_turma->fetch(PDO::FETCH_ASSOC);


//////////////////////////////////////////////////////////////////////////////////

// Query para cadastrar o evento
$query_cad_event = "INSERT INTO tb_events (title, description, color, start, end) 
                    VALUES (:title, :description, :color, :start, :end)";

$cad_event = $conn->prepare($query_cad_event);

$cad_event->bindParam(':title', $dados['cad_title']);
$cad_event->bindParam(':description', $dados['cad_description']);
$cad_event->bindParam(':color', $dados['cad_color']);
$cad_event->bindParam(':start', $dados['cad_start']);
$cad_event->bindParam(':end', $dados['cad_end']);



if ($cad_event->execute()) {
    // Recuperar o ID do evento inserido
    $id_event = $conn->lastInsertId();

    // Query para cadastrar a associação entre o evento e o usuário na tabela tb_event_user
    $query_cad_association = "INSERT INTO tb_event_user (id_event, id_user) 
                              VALUES (:id_event, :id_user)";

    $cad_association = $conn->prepare($query_cad_association);

    // Bind dos parâmetros para a associação
    $cad_association->bindParam(':id_event', $id_event);
    $cad_association->bindParam(':id_user', $dados['cad_user_id']);



    // Query para cadastrar a associação entre o evento e a turma na tabela tb_turma_event
    $query_cad_association_turma = "INSERT INTO tb_turma_event (id_turma, id_event) 
    VALUES (:id_turma, :id_event)";

    $cad_association_turma = $conn->prepare($query_cad_association_turma);

    // Bind dos parâmetros para a associação
    $cad_association_turma->bindParam(':id_turma', $dados['cad_turma_id']);
    $cad_association_turma->bindParam(':id_event', $id_event);


    // Executar a inserção da associação
    if ($cad_association->execute() && $cad_association_turma->execute()) {
        $retorna = [
            'status' => true,
            'msg' => 'Evento cadastrado com sucesso!',
            'id' => $id_event,
            'title' => $dados['cad_title'],
            'description' => $dados['cad_description'],
            'color' => $dados['cad_color'],
            'start' => $dados['cad_start'],
            'end' => $dados['cad_end'],
            'user_id' => $row_user['id'],
            'user_nome' => $row_user['nome'],
            'user_email' => $row_user['email'],
            'turma_id' => $row_turma['id'],
            'turma_nome' => $row_turma['nome'],
            'turma_descricao' => $row_turma['descricao'],
        ];

    } else {
        // Se a inserção da associação falhar, você pode tratar o erro aqui
        $retorna = [
            'status' => false,
            'msg' => 'Erro: Associação entre o evento e o usuário não cadastrada!'
        ];
    }
} else {
    $retorna = [
        'status' => false,
        'msg' => 'Erro: Evento não cadastrado no banco de dados!'
    ];
}

echo json_encode($retorna);

