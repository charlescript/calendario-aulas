<?php



// Incluir o arquivo com a conexão para o banco de dados
include_once './conexao.php';

// Receber o ID enviado pelo JavaScript
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Verifica se o ID não está vazio
if(!empty($id)) {
   try {
       // Iniciar transação
       $conn->beginTransaction();

       // Preparar e executar a query para excluir as associações do evento
       $query_apagar_associacao = "DELETE FROM tb_event_user WHERE id_event = :id";
       $apagar_associacao = $conn->prepare($query_apagar_associacao);
       $apagar_associacao->bindParam(':id', $id); 
       $apagar_associacao->execute();

       // Preparar e executar a query para excluir o evento
       $query_apagar_event = "DELETE FROM tb_events WHERE id = :id";
       $apagar_event = $conn->prepare($query_apagar_event);
       $apagar_event->bindParam(':id', $id);
       $apagar_event->execute();

       // Confirmar a transação
       $conn->commit();

       // Responder ao JavaScript com um JSON indicando sucesso
       $retorna = ['status' => true, 'msg' => 'Evento apagado com sucesso!'];
   } catch(PDOException $e) {
       // Se ocorrer algum erro, cancelar a transação e responder com um JSON indicando erro
       $conn->rollback();
       $retorna = ['status' => false, 'msg' => 'Erro: ' . $e->getMessage()];
   }
} else {
    // Se o ID estiver vazio, responder ao JavaScript com um JSON indicando erro
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário enviar o ID do evento!'];
}

// Converte o array em objeto JSON e retorna para o JavaScript
echo json_encode($retorna);




//CÓDIGO ABAIXO É UMA VERSÃO INICIAL, QUE NÃO MANTINHA A INTEGRIDADE REFERENCIAL DO BANCO DE DADOS.

// // Incluir o arquivo com a conexão para o banco de dados
// include_once './conexao.php';

// // Receber o ID enviado pelo JavasCript
// $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


// if(!empty($id)) {

//    $query_apagar_associacao = "DELETE FROM tb_event_user WHERE id_event = :id";
//    $apagar_associacao = $conn->prepare($query_apagar_associacao);
//    $apagar_associacao->bindParam(':id', $id); 

//    $query_apagar_event = "DELETE FROM tb_events WHERE id = :id";
//    $apagar_event = $conn->prepare($query_apagar_event);
//    $apagar_event->bindParam(':id', $id);
//    // Verifica se a query teve sucesso na deleção
//    if( $apagar_event->execute() && $apagar_associacao->execute() ){ 

//         $retorna = ['status' => true, 
//                     'msg' => 'Evento apagado com sucesso !' ];

//    } else { // Se não teve sucesso apresenta a mensagem abaixo

//         $retorna = [ 'status' => false, 
//                      'msg' => 'Erro: Evento não apagado !'];
//    }

// } else { // Acessa o ELSE quando o id está vazio

//     $retorna = [ 'status' => false, 
//                  'msg' => 'Erro: Necessário enviar o id do evento !' ];
// }

// // Converte o array em objeto json e retorna para o Javascript
// echo json_encode($retorna);

 