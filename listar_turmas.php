<?php

// Incluindo o arquivo com a conexão com o banco de dados
include_once './conexao.php';



    // QUERY para recuperar os turmas
    $query_turmas = "SELECT id, nome, descricao from tb_turma 
                            ORDER BY nome ASC";

    $result_turmas = $conn->prepare($query_turmas);
    

// Executar a QUERY
$result_turmas->execute();

// Acessar o IF quando encontrar turma no banco de dados
if( ($result_turmas) and ($result_turmas->rowCount() != 0) ){

    // Ler os registros recuperados do banco  de dados
    $dados = $result_turmas->fetchAll(PDO::FETCH_ASSOC);

    // Criar o array com o status e os dados
    $retorna = [
        'status' => true,
        'dados' => $dados
    ];


} else {

    // Criar o array com o status e os dados
    $retorna = [
        'status' => false,
        'msg' => "Nenhuma turma encontrada"
    ];
}

// Converte array PHP para um ojeto JSON
echo json_encode($retorna);