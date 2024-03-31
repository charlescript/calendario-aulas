// Executar quando o documento html for completamente carregado
document.addEventListener('DOMContentLoaded', function () {



    //Receber o seletor da janela modal cadastrar
    const cadastrarModal = new bootstrap.Modal(document.getElementById("cadastrarModal"));

    //Receber o seletor da janela modal visualizar
    const visualizarModal = new bootstrap.Modal(document.getElementById("visualizarModal"));

    // Receber o SELETOR "msgViewEvento"
    const msgViewEvento = document.getElementById('msgViewEvento');

    function carregarEventos() {


        // Receber o SELETOR calendar do atributo id
        var calendarEl = document.getElementById('calendar');

        // Receber o id do usuário do campo Select
        var user_id = document.getElementById('user_id').value;

        // Instanciar FullCalendar.Calendar e atribuir a variável calendar
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
            events: 'listar_evento.php?user_id=' + user_id,

            // Identificar o clique do usuário sobre o evento
            eventClick: function (info) {

                // Apresentar os detalhes do evento
                document.getElementById("visualizarEvento").style.display = "block";
                document.getElementById("visualizarModalLabel").style.display = "block";

                // Ocultar o formulário editar do evento
                document.getElementById("editarEvento").style.display = "none";
                document.getElementById("editarModalLabel").style.display = "none";

                // Enviar para a janela modal os dados do evento
                console.log(info.event.extendedProps);
                document.getElementById("visualizar_id").innerText = info.event.id;
                document.getElementById("visualizar_title").innerText = info.event.title;
                document.getElementById("visualizar_description").innerText = info.event.extendedProps.description;
                document.getElementById("visualizar_user_id").innerText = info.event.extendedProps.user_id;
                document.getElementById("visualizar_user_nome").innerText = info.event.extendedProps.user_nome;
                document.getElementById("visualizar_user_email").innerText = info.event.extendedProps.user_email;


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


                // Enviar os dados do evento para o formulário editar
                document.getElementById("edit_id").value = info.event.id;
                document.getElementById("edit_title").value = info.event.title;
                document.getElementById("edit_description").value = info.event.extendedProps.description;
                document.getElementById("edit_start").value = converterData(info.event.start);
                document.getElementById("edit_end").value = info.event.end !== null ? converterData(info.event.end) : converterData(info.event.start);
                document.getElementById("edit_color").value = info.event.backgroundColor;


                // Abrir a janela modal visualizar
                visualizarModal.show();
            },

            // Abrir uma janela modal cadastrar quando clicar sobre o dia no calendário
            select: async function (info) {

                // Receber o seletor do campo usuário do formulário cadastrar
                var cadUserId = document.getElementById('cad_user_id');

                // Chamar o arquivo PHP responsável por recuperar os usuários do banco de dados
                const dados = await fetch('listar_usuarios.php');

                // Ler dados retornados de listar_usuarios.php
                const resposta = await dados.json();
                //console.log(resposta);

                if (resposta['status']) {

                    // Criar a opção selecione para o campo select usuário
                    var opcoes = '<option value=""> Selecione </option>';

                    // Percorrer a lista de usuários
                    for (var i = 0; i < resposta.dados.length; i++) {

                        //Criar a lista de opções para o campo select usuários
                        opcoes += `<option value="${resposta.dados[i]['id']}"> 
                                    ${resposta.dados[i]['nome']} 
                              </option>`;
                    }

                    // Enviar opções para o campo select no HTML
                    cadUserId.innerHTML = opcoes;

                } else {

                    // Enviar a opção vazia para o campo select no HTML
                    cadUserId.innerHTML = `<option value="">${resposta['msg']}</option>`;
                }

                // Chamar a função para converter a data selecionada para ISO8601 e enviar para o formulário
                document.getElementById("cad_start").value = converterData(info.start);
                document.getElementById("cad_end").value = converterData(info.end - 1);

                cadastrarModal.show();
            }

        });


        // Renderizar o calendário
        //calendar.render();

        /// Retornar os dados do calendario
        return calendar;

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////


    // Chamar a função carregar eventos
    var calendar = carregarEventos();

    // Renderizar o calendário
    calendar.render();

    // Receber o seletor user_id do campo select
    var userId = document.getElementById('user_id');

    // Aguardar o usuário selecionar valor no campo selecionar usuário
    userId.addEventListener('change', function () {
        //console.log("Recuperar os eventos de usuário!" + userId.value);

        // Chamar a função carregar eventos
        calendar = carregarEventos();

        // Renderizar o calendário
        calendar.render();
    });


    ////////////////////////////////////////////////////////////////////////////////////////////////


    // Converter a data para o formato pt-br
    function converterData(data) {

        // Converter a string em um objeto Date
        const dataObj = new Date(data);

        //Extrair o ano da data
        const ano = dataObj.getFullYear();

        //Obter o mês, mês começa de 0, padStart adiciona zeros à esquerda para garantir que o mês tenha 2 dígitos 
        const mes = String(dataObj.getMonth() + 1).padStart(2, '0');

        // Obter o dia do mês, padStart adiciona zeros à esquerda para garantir que o dia tenha 2 dígitos
        const dia = String(dataObj.getDate()).padStart(2, '0');

        // Obter a hora, padStart adiciona zeros à esquerda para garantir que a hora tenha dois dígitos
        const hora = String(dataObj.getHours()).padStart(2, '0');

        // Obter minuto, padStart adiciona zeros à esquerda para garantir que o minuto tenha 2 digitos
        const minuto = String(dataObj.getMinutes()).padStart(2, '0');

        // Retornar a data
        return `${ano}-${mes}-${dia} ${hora}:${minuto}`;
    }
    // Fim da função converterData


    ////////////////////////////////////////////////////////////////////////////////////////////////


    // Receber o SELETOR do formulário cadastrar evento
    const formCadEvento = document.getElementById("formCadEvento");

    // Receber o SELETOR da mensagem genérica
    const msg = document.getElementById("msg");

    // Receber o seletor da mensagem cadastrar evento
    const msgCadEvento = document.getElementById("msgCadEvento")

    // Receber o SELETOR do botão da janela modal cadastrar evento
    const btnCadEvento = document.getElementById("btnCadEvento");

    // Somente acessa o IF quando existir o SELETOR "formCadEvento"
    if (formCadEvento) {

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
            if (!resposta['status']) {

                // Enviar a mensagem para o HTML
                msgCadEvento.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        ${resposta['msg']}
                    </div>`;

            } else {

                // Enviar a mensagem para o HTML
                msg.innerHTML = `
                    <div class="alert alert-success" role="alert">
                         ${resposta['msg']},
                         ${resposta['title']}
                    </div>
                `;

                msgCadEvento.innerHTML = "";

                // Limpar o formulário
                formCadEvento.reset();

                //Receber o id do usuário do campo SELECT
                var user_id = document.getElementById('user_id').value;

                // Verificar se existe a pesquisa pelo usuário, se o cadastro for para o mesmo usuário pesquisado
                // acrescenta no FUllCalendar
                if (user_id == "" || resposta['user_id'] == user_id) {

                    //Criar o objeto com os dados do evento
                    const novoEvento = {
                        id: resposta['id'],
                        title: resposta['title'],
                        description: resposta['description'],
                        color: resposta['color'],
                        start: resposta['start'],
                        end: resposta['end'],
                        user_id: resposta['user_id'],
                        name: resposta['user_nome'],
                        email: resposta['user_email'],
                    }

                    // Adicionar o evento ao calendário
                    calendar.addEvent(novoEvento);
                }

                // Chamar a função para remover a mensagem após 3 segundos
                removeMsg();

                // Fechar a janela modal
                cadastrarModal.hide();
            }

            // Apresentar no botão o texto Cadastrar
            btnCadEvento.value = "Cadastrar";

        });
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////


    // Função para remover a mensagem após 3 sengundos
    function removeMsg() {
        setTimeout(() => {
            document.getElementById('msg').innerHTML = "";
        }, 3000)
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////


    // Receber o SELETOR ocultar detalhes editar evento e apresentar o formulario editar evento
    const btnViewEditEvento = document.getElementById("btnViewEditEvento");
    if (btnViewEditEvento) {

        // Aguardar o usuário clicar no botão editar
        btnViewEditEvento.addEventListener("click", async () => {

            // Ocultar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "none";
            document.getElementById("visualizarModalLabel").style.display = "none";

            // Apresentar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "block";
            document.getElementById("editarModalLabel").style.display = "block";


            // Receber o id so usuário participante do evento
            var userId = document.getElementById("visualizar_user_id").innerText;

            // Receber o seletor do campo usuário do formulário editar
            var editUserId = document.getElementById('edit_user_id');

            // Chamar o arquivo PHP responsável por recuperar os usuários do banco de dados
            const dados = await fetch('listar_usuarios.php');

            // Ler dados retornados de listar_usuarios.php
            const resposta = await dados.json();
            //console.log(resposta);

            if (resposta['status']) {

                // Criar a opção selecione para o campo select usuário
                var opcoes = '<option value=""> Selecione </option>';

                // Percorrer a lista de usuários
                for (var i = 0; i < resposta.dados.length; i++) {

                    //Criar a lista de opções para o campo select usuários
                    opcoes += `<option value="${resposta.dados[i]['id']}" ${ userId === resposta.dados[i]['id'] ? 'selected' :  ""  }> 
                                     ${resposta.dados[i]['nome']} 
                               </option>`;
                }

                // Enviar opções para o campo select no HTML
                editUserId.innerHTML = opcoes;

            } else {

                // Enviar a opção vazia para o campo select no HTML
                editUserId.innerHTML = `<option value="">${resposta['msg']}</option>`;

            }

        });
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////


    // Receber o SELETOR ocultar formulário editar evento e apresentar o detalhe do evento
    const btnViewEvento = document.getElementById("btnViewEvento");
    if (btnViewEvento) {

        // Aguardar o usuário clicar no botão editar
        btnViewEvento.addEventListener("click", () => {

            // Apresentar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "block";
            document.getElementById("visualizarModalLabel").style.display = "block";

            // Ocultar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "none";
            document.getElementById("editarModalLabel").style.display = "none";

        });
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////

    // Receber o seletor do formulário editar evento
    const formEditEvento = document.getElementById("formEditEvento");

    // Receber o SELETOR da mensagem editar evento msgEditEvento
    const msgEditEvento = document.getElementById("msgEditEvento");

    // Receber o SELETOR do botão editar evento
    const btnEditEvento = document.getElementById("btnEditEvento");


    // Somente acessa o IF quando existir o SELETOR "formEditEvento"
    if (formEditEvento) {

        // Aguardar o usuário clicar no botão editar
        formEditEvento.addEventListener("submit", async (e) => {

            // Não permitir a atualização da pagina
            e.preventDefault();

            // Apresentar no botão o texto salvando
            btnEditEvento.value = "Salvando edição..."

            // Receber os dados do formulário
            const dadosForm = new FormData(formEditEvento);


            // Chamar o arquivo PHP responsável em editar o evento
            const dados = await fetch("editar_evento.php", {
                method: "POST",
                body: dadosForm
            });

            // Realizar a leitura dos dados retornados pelo PHP
            const resposta = await dados.json();

            // Acessa o IF quando não editar com sucesso
            if (!resposta['status']) {

                // Enviar a mensagem para o HTML
                msgEditEvento.innerHTML = `
                <div class="alert alert-danger" role="alert"> 
                    ${resposta['msg']} 
                </div>`;

            } else {

                // Enviar a mensagem para o HTML
                msg.innerHTML = `
               <div class="alert alert-success" role="alert">
                    ${resposta['msg']},
                    ${resposta['title']}
               </div>`;

                //Envia a mensagem para o HTML
                msgEditEvento.innerHTML = "";

                // Limpar o formulario
                formEditEvento.reset();

                // Recuperar o evento no FullCalendar pelo id
                const eventoExiste = calendar.getEventById(resposta['id']);


                // Receber o id do usuário do campo Select
                var user_id = document.getElementById('user_id').value;

                // Verificar se exista a pesquisa pelo usuário, se o editar for para o mesmo usuário
                // pesquisado, mantem no FullCalendar
                if (user_id == "" || resposta['user_id'] == user_id) {

                    // Verificar se encontrou o evento no FullCalendar pelo id
                    if (eventoExiste) {

                        // Atualizar os atributos do evento com os novos valores do banco de dados
                        eventoExiste.setProp('title', resposta['title']);
                        eventoExiste.setExtendedProp('description', resposta['description']);
                        eventoExiste.setProp('color', resposta['color']);
                        eventoExiste.setStart(resposta['start']);
                        eventoExiste.setEnd(resposta['end']);
                        eventoExiste.setExtendedProp('user_id', resposta['user_id']);
                        eventoExiste.setExtendedProp('user_nome', resposta['user_nome']);
                        eventoExiste.setExtendedProp('user_email', resposta['user_email']);
                    }

                } else {

                    // Verificar se encontrou o evento no FullCalendar pelo id
                    if (eventoExiste) {

                        // Remover o evento do calendário
                        eventoExiste.remove();
                    }

                }



                // Chamar a função para remover a mensagem após 3 segundos
                removeMsg();

                // Fechar a janela modal
                visualizarModal.hide();
            }

            // Apresentar no botão o texto salvar
            btnEditEvento.value = "Salvar";
        });
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////



    // Receber o seletor do botão apagar evento
    const btnApagarEvento = document.getElementById("btnApagarEvento");
    if (btnApagarEvento) {

        // Aguardar o usuário clicar no botão apagar
        btnApagarEvento.addEventListener("click", async () => {

            // Exibir um caixa de diálogo de confirmação
            const confirmacao = window.confirm("Tem certeza que deseja apagar esse evento ?");

            if (confirmacao) {

                // Receber o id do evento
                var idEvento = document.getElementById("visualizar_id").textContent;

                // Chamar o arquivo PHP responsável apagar o evento
                const dados = await fetch("apagar_evento.php?id=" + idEvento);

                // Realizar a leitura dos objetos retornados do arquivo apagar_evento.php
                const resposta = await dados.json();

                // Acessa o IF quando não cadastrar com sucesso 
                if (!resposta['status']) {

                    // Envia a mensagem para o HTML
                    msgViewEvento.innerHTML = `<div class="alert alert-danger" role="alert"> ${resposta['msg']} </div>`;

                } else {

                    // Envia a mensagem para o HTML
                    msg.innerHTML = `<div class="alert alert-success" role="alert"> ${resposta['msg']} </div>`;

                    // Zerar o conteudo do span
                    msgViewEvento.innerHTML = "";


                    // Recuperar o evento no FUllCalendar
                    const eventoExisteRemover = calendar.getEventById(idEvento);

                    //Verificar se encontrou o evento no FullCalendar
                    if (eventoExisteRemover) {
                        // Remover o evento do calendário
                        eventoExisteRemover.remove();
                    }

                    // Chamar a função para remover a mensagem após 3 segundos
                    removeMsg();

                    // Fechar a janela modal
                    visualizarModal.hide();

                }
            }
        });
    }
});


////////////////////////////////////////////////////////////////////////////////////////////////


// Receber o seletor do campo listar os usuários
const user = document.getElementById("user_id");

// Verificar se existe o seletor user_id no HTML
if (user) {

    // Chamar a função
    listarUsuarios();
}

// Função para recuperar os usuários
async function listarUsuarios() {

    // Chamar o arquivo PHP para recuperar os usuários
    const dados = await fetch('listar_usuarios.php');

    // Ler os dados retornado do PHP
    const resposta = await dados.json();

    //console.log(resposta);

    // Verificar se status e TRUE e acessa o IF, senão acessa o ELSE e retorna a mensagem de erro
    if (resposta['status']) {

        // Criar a variavel com as opções para campo SELECT
        var opcoes = `<option value="">Selecionar ou limpar</option>`;

        // Percorrer o array de usuários
        for (var i = 0; i < resposta.dados.length; i++) {

            // Atribuir o usuário como opção para o campo SELECT
            opcoes += `<option value="${resposta.dados[i]['id']}">${resposta.dados[i]['nome']}</option>`;
        }

        // Enviar para o HTMML as opções para o campo SELECT 
        user.innerHTML = opcoes;

    } else {
        user.innerHTML = `<option value="">${resposta['msg']}</option>`;
    }


}