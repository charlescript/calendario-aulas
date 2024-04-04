<?php

// Incluindo o arquivo com a conexão com o banco de dados
include_once './conexao.php';

// Receber o identificador do USER   // Receber o profissional
$profissional = filter_input(INPUT_GET, 'profissional', FILTER_DEFAULT);

// Verificar se o parametro profissional foi enviado
if(!empty($profissional)){

    // QUERY para recuperar os usuários
    $query_users = "SELECT id, nome from tb_users 
                        WHERE nivel = :profissional 
                            ORDER BY nome ASC";

    $result_users = $conn->prepare($query_users);
    
    // Atribuir o valor do parâmetro
    $result_users->bindParam(":profissional", $profissional);

} else {

    // QUERY para recuperar os usuários
    $query_users = "SELECT id, nome from tb_users ORDER BY nome ASC";
    //$query_users = "SELECT id, nome from tb_users WHERE id = 700 ORDER BY nome ASC";
    
    $result_users = $conn->prepare($query_users);
    
}

// Executar a QUERY
$result_users->execute();

// Acessar o IF quando encontrar usuário no banco de dados
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