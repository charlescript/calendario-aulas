// Executar quando o documento html for completamente carregado
document.addEventListener('DOMContentLoaded', function () {

    var calendarEl = document.getElementById('calendar');

     //Receber o seletor da janela modal
     const cadastrarModal = new bootstrap.Modal(document.getElementById("cadastrarModal"));

    var calendar = new FullCalendar.Calendar(calendarEl, {

        // Incluindo o Bootstrap 5
        themeSystem: 'bootstrap5',

        // Cria o cabeçalho do calendário
        headerToolbar: {
            left: 'prev,next,today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth,dayGridYear'
        },

        // Definir o idioma usado no calendário
        locale: 'pt-br',

        initialDate: '2024-01-12',
        //initialDate: '2024-03-18',

        // Permitir clicar nos nomes dos dias da semanda
        navLinks: true,

        //Permite clicar e arrastar o mouse sobre um ou vários dias no calendario
        selectable: true,

        // Indicar visualmente a área que será selecionada antes que o usuário
        // solte o botão do mouse para confirmar a seleção
        selectMirror: true,

        //Permite arrastar e redimensionar os eventos diretamente no calendário.
        editable: true,

        // Número máximo de eventos em um determinado dia, se for true, o número de eventos será limitado à altura da célula do dia
        dayMaxEvents: true,

        // Chamar o arquivo PHP para recuperar os eventos
        events: 'listar_evento.php',

        // Identificar o clique do usuário sobre o evento
        eventClick: function (info) {

            //Receber o seletor da janela modal visualizar
            const visualizarModal = new bootstrap.Modal(document.getElementById("visualizarModal"));

            // Enviar para a janela modal os dados do evento
            document.getElementById("visualizar_id").innerText = info.event.id;
            document.getElementById("visualizar_title").innerText = info.event.description;
            document.getElementById("visualizar_description").innerText = info.event.title;
  
            // Verificar se info.event.start e info.event.end são válidos antes de chamar toLocaleString()
            if (info.event.start && info.event.end) {
                document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();
                document.getElementById("visualizar_end").innerText = info.event.end.toLocaleString();
            } else {
                // Tratar o caso em que start ou end são null
                // Por exemplo, fornecer uma mensagem de erro ou usar uma data padrão
                console.error("O objeto de evento não possui propriedades 'start' ou 'end' válidas.");
                document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();
                document.getElementById("visualizar_end").innerText = info.event.end !== null ? info.event.end.toLocaleString() : info.event.start.toLocaleString();
            }

            // Abrir a janela modal visualizar
            visualizarModal.show();
        },

        // Abrir uma janela modal cadastrar quando clicar sobre o dia no calendário
        select: function (info) {
           
            // Chamar a função para converter a data selecionada para ISO8601 e enviar para o formulário
            document.getElementById("cad_start").value = converterData(info.start);
            document.getElementById("cad_end").value = converterData(info.end-1);

            cadastrarModal.show();
        }

    });


    // Renderizar o calendário
    calendar.render();

    // Converter a data para o formato pt-br
    function converterData(data) {

        // Converter a string em um objeto Date
        const dataObj = new Date(data);

        //Extrair o ano da data
        const ano = dataObj.getFullYear();

        //Obter o mês, mês começa de 0, padStart adiciona zeros à esquerda para garantir que o mês tenha 2 dígitos 
        const mes = String(dataObj.getMonth() + 1).padStart(2,'0');

        // Obter o dia do mês, padStart adiciona zeros à esquerda para garantir que o dia tenha 2 dígitos
        const dia = String(dataObj.getDate()).padStart( 2,'0' );

        // Obter a hora, padStart adiciona zeros à esquerda para garantir que a hora tenha dois dígitos
        const hora = String(dataObj.getHours()).padStart( 2,'0' );

        // Obter minuto, padStart adiciona zeros à esquerda para garantir que o minuto tenha 2 digitos
        const minuto = String(dataObj.getMinutes()).padStart( 2,'0' );

        // Retornar a data
        return `${ano}-${mes}-${dia} ${hora}:${minuto}`;
    }
    // Fim da função converterData


    // Receber o SELETOR do formulário cadastrar evento
    const formCadEvento = document.getElementById("formCadEvento");

    // Receber o SELETOR da mensagem genérica
    const msg = document.getElementById("msg");

    // Receber o seletor da mensagem cadastrar evento
    const msgCadEvento = document.getElementById("msgCadEvento")

    // Receber o SELETOR do botão da janela modal cadastrar evento
    const btnCadEvento = document.getElementById("btnCadEvento");

    // Somente acessa o IF quando existir o SELETOR "formCadEvento"
    if(formCadEvento){

        //Aguardar o usuario clicar no botão cadastrar
        formCadEvento.addEventListener("submit", async (e) => {

            // Não permitir a atualização da página
            e.preventDefault();

            // Apresentar no botão o texto salvando
            btnCadEvento.value = "Salvando...";

            // Receber os dados do formulário
            const dadosForm = new FormData(formCadEvento);

            // Chamar o arquivo PHP responsável em salvar o evento
            const dados = await fetch("cadastrar_evento.php", {
                method: "POST",
                body: dadosForm
            });

            //Realizar a leitura dos dados retornados pelo PHP no arquivo cadastrar_eventos.php
            const resposta = await dados.json();
            

            // Acessa o IF quando não cadastrar com sucesso
            if(!resposta['status']){

                // Enviar a mensagem para o HTML
                msgCadEvento.innerHTML =`
                    <div class="alert alert-danger" role="alert">
                        ${resposta['msg']}
                    </div>`; 

            } else {

                // Enviar a mensagem para o HTML
                msg.innerHTML =`
                    <div class="alert alert-success" role="alert">
                         ${resposta['msg']},
                         ${resposta['title']}
                    </div>
                `;

                msgCadEvento.innerHTML = "";

                // Limpar o formulário
                formCadEvento.reset(); 

                //Criar o objeto com os dados do evento
                const novoEvento = {
                    id: resposta['id'],
                    title: resposta['title'],
                    color: resposta['color'],
                    start: resposta['start'],
                    end: resposta['end'],
                }

                // Adicionar o evento ao calendário
                calendar.addEvent(novoEvento);

                // Chamar a função para remover a mensagem após 3 segundos
                removeMsg();

                // Fechar a janela modal
                cadastrarModal.hide();
            }

            // Apresentar no botão o texto Cadastrar
            btnCadEvento.value = "Cadastrar";

        });
    }

    // Função para remover a mensagem após 3 sengundos
    function removeMsg() {
        setTimeout(() => {
            document.getElementById('msg').innerHTML = "";
        }, 3000)
    }


    // Receber o SELETOR ocultar detalhes editar evento e apresentar o formulario editar evento
    const btnViewEditEvento = document.getElementById("btnViewEditEvento");
    if(btnViewEditEvento){

        // Aguardar o usuário clicar no botão editar
        btnViewEditEvento.addEventListener("click", () => {

            // Ocultar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "none";
            document.getElementById("visualizarModalLabel").style.display = "none";

            // Apresentar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "block";
            document.getElementById("editarModalLabel").style.display = "block";
        
        });
    }



        // Receber o SELETOR ocultar formulário editar evento e apresentar o detalhe do evento
        const btnViewEvento = document.getElementById("btnViewEvento");
        if(btnViewEvento){
    
            // Aguardar o usuário clicar no botão editar
            btnViewEvento.addEventListener("click", () => {
                
                // Ocultar os detalhes do evento
                document.getElementById("visualizarEvento").style.display = "block";
                document.getElementById("visualizarModalLabel").style.display = "block";
    
                // Apresentar o formulário editar do evento
                document.getElementById("editarEvento").style.display = "none";
                document.getElementById("editarModalLabel").style.display = "none";
            
            });
        }
});