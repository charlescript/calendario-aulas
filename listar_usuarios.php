<?php

// Incluindo o arquivo com a conexão com o banco de dados
include_once './conexao.php';

// QUERY para recuperar os usuários
$query_users = "SELECT id, nome from tb_users ORDER BY nome ASC";
//$query_users = "SELECT id, nome from tb_users WHERE id = 700 ORDER BY nome ASC";

$result_users = $conn->prepare($query_users);

$result_users->execute();

if( ($result_users) and ($result_users->rowCount() != 0) ){

    // Ler os registros recuperados do banco  de dados
    $dados = $result_users->fetchAll(PDO::FETCH_ASSOC);

    // Criar o array com o status e os dados
    $retorna = [
        'status' => true,
        'dados' => $dados
    ];


} else {

    // Criar o array com o status e os dados
    $retorna = [
        'status' => false,
        'msg' => "Nenhum usuário encontrado"
    ];
}

// Converte array PHP para um ojeto JSON
echo json_encode($retorna);