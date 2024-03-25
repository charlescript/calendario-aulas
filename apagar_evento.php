<?php

// Incluir o arquivo com a conexão para o banco de dados
include_once './conexao.php';

// Receber o ID enviado pelo JavasCript
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


if(!empty($id)) {

   $query_apagar_event = "DELETE FROM events WHERE id = :id";
   $apagar_event = $conn->prepare($query_apagar_event);
   $apagar_event->bindParam(':id', $id);
   // Verifica se a query teve sucesso na deleção
   if($apagar_event->execute()){ 

        $retorna = ['status' => true, 
                    'msg' => 'Evento apagado com sucesso !' ];

   } else { // Se não teve sucesso apresenta a mensagem abaixo

        $retorna = [ 'status' => false, 
                     'msg' => 'Erro: Evento não apagado !'];
   }

} else { // Acessa o ELSE quando o id está vazio

    $retorna = [ 'status' => false, 
                 'msg' => 'Erro: Necessário enviar o id do evento !' ];
}

// Converte o array em objeto json e retorna para o Javascript
echo json_encode($retorna);



 