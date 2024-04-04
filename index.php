<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>

    <link rel="stylesheet" href="css/custom.css" />

    <title>Calendário</title>
</head>

<body>

    <div class="container">
        
        <div class="card mb-4 border-light shadow">
            <div class="card-body"> 

                <h2 class="mt-0 me-3 ms-2 pb-2 border-bottom titulo-agenda">Agenda</h2>
                <span id="msg"></span>

                <form class="ms-2 m2-2">
                    
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label" for="user_id">Pesquisar metérias do professor:</label>

                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Selecione</option>
                        </select>

                    </div>
                
                </form>

            </div>
        </div>

        <div class="card p-4 border-light shadow">
            <div class="card-body"> 

                <div id='calendar'></div>

            </div>
        </div>

    </div>
        <!-- Modal Visualisar-->
        <div class="modal fade" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h1 class="modal-title fs-5" id="visualizarModalLabel">Visualizar matéria</h1>

                        <h1 class="modal-title fs-5" id="editarModalLabel" style="display:none">Editar evento</h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <span id="msgViewEvento"></span>

                        <div id="visualizarEvento">
                            <dl class="row">
                                <!-- DADOS DO EVENTO -->
                                <dt class="col-sm-3">ID evento: </dt>
                                <dd class="col-sm-9" id="visualizar_id"></dd>

                                <dt class="col-sm-3">Titulo: </dt>
                                <dd class="col-sm-9" id="visualizar_title"></dd>

                                <dt class="col-sm-3">Descrição: </dt>
                                <dd class="col-sm-9" id="visualizar_description"></dd>

                                <dt class="col-sm-3">Inicio: </dt>
                                <dd class="col-sm-9" id="visualizar_start"></dd>

                                <dt class="col-sm-3">Fim: </dt>
                                <dd class="col-sm-9" id="visualizar_end"></dd>
                                
                                <hr/>
                                <!-- DADOS DO PROFESSOR -->
                                <dt class="col-sm-4">ID do professor: </dt>
                                <dd class="col-sm-8" id="visualizar_user_id"></dd>

                                <dt class="col-sm-4">Nome professor: </dt>
                                <dd class="col-sm-8" id="visualizar_user_nome"></dd>

                                <dt class="col-sm-4">Email professor: </dt>
                                <dd class="col-sm-8" id="visualizar_user_email"></dd>

                                <hr/>
                                <!-- DADOS DA TURMA -->
                                <dt class="col-sm-4">ID da Turma: </dt>
                                <dd class="col-sm-8" id="visualizar_turma_id"></dd>

                                <dt class="col-sm-4">Nome da Turma: </dt>
                                <dd class="col-sm-8" id="visualizar_turma_nome"></dd>

                                <dt class="col-sm-4">Descrição Turma: </dt>
                                <dd class="col-sm-8" id="visualizar_turma_descricao"></dd>

                            </dl>

                            <button type="button" class="btn btn-warning" id="btnViewEditEvento">Editar</button>

                            <button type="button" class="btn btn-danger" id="btnApagarEvento">Apagar</button>
                        </div>

                        <!-- EDIÇÃO DE EVENTO -->
                        <div id="editarEvento" style="display:none">  

                            <span id="msgEditEvento" style="margin: 10px; font-weight: bold;"></span>

                            <form method="POST" id="formEditEvento">

                                <input type="hidden" name="edit_id" id="edit_id">

                                <div class="row mb-3">
                                    <label for="edit_title" class="col-sm-2 col-form-label">Titulo</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="edit_title" class="form-control" id="edit_title" placeholder="Título do evento">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="edit_description" class="col-sm-2 col-form-label">Descrição</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="edit_description" class="form-control" id="edit_description" placeholder="Descrição do evento">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="edit_start" class="col-sm-2 col-form-label">Inicio</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" name="edit_start" class="form-control" id="edit_start">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="edit_end" class="col-sm-2 col-form-label">Fim</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" name="edit_end" class="form-control" id="edit_end">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="edit_color" class="col-sm-2 col-form-label">Cor</label>
                                    <div class="col-sm-10">
                                        <select name="edit_color" class="form-control" id="edit_color">
                                            <option value="">Selecione</option>
                                            <option style="color: #FF0000; background-color: black;" value="#FF0000">Vermelho</option>
                                            <option style="color: #008000; background-color: black;" value="#008000">Verde</option>
                                            <option style="color: #0000FF; background-color: black;" value="#0000FF">Azul</option>
                                            <option style="color: #FFFFFF; background-color: black;" value="#FFFFFF">Branco</option>
                                            <option style="color: #000000; background-color: gray;" value="#000000">Preto</option>
                                            <option style="color: #FFC0CB; background-color: black;" value="#FFC0CB">Rosa</option>
                                            <option style="color: #FFA500; background-color: black;" value="#FFA500">Laranja</option>
                                            <option style="color: #800080; background-color: black;" value="#800080">Roxo</option>
                                            <option style="color: #808080; background-color: black;" value="#808080">Cinza</option>
                                            <option style="color: #FFFFE0; background-color: black;" value="#FFFFE0">Amarelo Pálido</option>
                                            <option style="color: #40E0D0; background-color: black;" value="#40E0D0">Turquesa</option>
                                            <option style="color: #A52A2A; background-color: black;" value="#A52A2A">Marrom</option>
                                            <option style="color: #ADD8E6; background-color: black;" value="#ADD8E6">Azul Claro</option>
                                            <option style="color: #00FF00; background-color: black;" value="#00FF00">Verde Lima</option>
                                            <option style="color: #A9A9A9; background-color: black;" value="#A9A9A9">Cinza Escuro</option>
                                            <option style="color: #FF7F50; background-color: black;" value="#FF7F50">Coral</option>
                                            <option style="color: #48D1CC; background-color: black;" value="#48D1CC">Turquesa Média</option>
                                            <option style="color: #E6E6FA; background-color: black;" value="#E6E6FA">Lavanda</option>
                                            <option style="color: #FFD700; background-color: black;" value="#FFD700">Amarelo</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="edit_user_id" class="col-sm-2 col-form-label">Professor</label>
                                    <div class="col-sm-10">
                                        <select name="edit_user_id" class="form-control" id="edit_user_id">
                                            <option value="">Selecione</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- <input type="hidden" name="edit_turma_id_old" id="edit_turma_id_old"> -->
                                    <label for="edit_turma_id" class="col-sm-2 col-form-label">Turma</label>
                                    <div class="col-sm-10">
                                        <select name="edit_turma_id" class="form-control" id="edit_turma_id">
                                            <option value="">Selecione</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="button" name="btnViewEvento" class="btn btn-primary" id="btnViewEvento">Cancelar</button>

                                <button type="submit" name="btnEditEvento" class="btn btn-warning" id="btnEditEvento">Cadastrar</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Cadastrar-->
        <div class="modal fade" id="cadastrarModal" tabindex="-1" aria-labelledby="cadastrarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="cadastrarModalLabel">Cadastrar evento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <span id="msgCadEvento" style="margin: 10px; font-weight: bold;"></span>

                        <form method="POST" id="formCadEvento">

                            <div class="row mb-3">
                                <label for="cad_title" class="col-sm-2 col-form-label">Titulo</label>
                                <div class="col-sm-10">
                                    <input type="text" name="cad_title" class="form-control" id="cad_title" placeholder="Título do evento">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_description" class="col-sm-2 col-form-label">Descrição</label>
                                <div class="col-sm-10">
                                    <input type="text" name="cad_description" class="form-control" id="cad_description" placeholder="Descrição do evento">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_start" class="col-sm-2 col-form-label">Inicio</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="cad_start" class="form-control" id="cad_start">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_end" class="col-sm-2 col-form-label">Fim</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="cad_end" class="form-control" id="cad_end">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_color" class="col-sm-2 col-form-label">Cor</label>
                                <div class="col-sm-10">
                                    <select name="cad_color" class="form-control" id="cad_color">
                                        <option value="">Selecione</option>
                                        <option style="color: #FF0000; background-color: black;" value="#FF0000">Vermelho</option>
                                        <option style="color: #008000; background-color: black;" value="#008000">Verde</option>
                                        <option style="color: #0000FF; background-color: black;" value="#0000FF">Azul</option>
                                        <option style="color: #FFFFFF; background-color: black;" value="#FFFFFF">Branco</option>
                                        <option style="color: #000000; background-color: gray;" value="#000000">Preto</option>
                                        <option style="color: #FFC0CB; background-color: black;" value="#FFC0CB">Rosa</option>
                                        <option style="color: #FFA500; background-color: black;" value="#FFA500">Laranja</option>
                                        <option style="color: #800080; background-color: black;" value="#800080">Roxo</option>
                                        <option style="color: #808080; background-color: black;" value="#808080">Cinza</option>
                                        <option style="color: #FFFFE0; background-color: black;" value="#FFFFE0">Amarelo Pálido</option>
                                        <option style="color: #40E0D0; background-color: black;" value="#40E0D0">Turquesa</option>
                                        <option style="color: #A52A2A; background-color: black;" value="#A52A2A">Marrom</option>
                                        <option style="color: #ADD8E6; background-color: black;" value="#ADD8E6">Azul Claro</option>
                                        <option style="color: #00FF00; background-color: black;" value="#00FF00">Verde Lima</option>
                                        <option style="color: #A9A9A9; background-color: black;" value="#A9A9A9">Cinza Escuro</option>
                                        <option style="color: #FF7F50; background-color: black;" value="#FF7F50">Coral</option>
                                        <option style="color: #48D1CC; background-color: black;" value="#48D1CC">Turquesa Média</option>
                                        <option style="color: #E6E6FA; background-color: black;" value="#E6E6FA">Lavanda</option>
                                        <option style="color: #FFD700; background-color: black;" value="#FFD700">Amarelo</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="cad_user_id" class="col-sm-2 col-form-label">Professor</label>
                                <div class="col-sm-10">
                                    <select name="cad_user_id" class="form-control" id="cad_user_id">
                                        <option value="">Selecione Professor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_turma_id" class="col-sm-2 col-form-label">Turma</label>
                                <div class="col-sm-10">
                                    <select name="cad_turma_id" class="form-control" id="cad_turma_id">
                                        <option value="">Selecione Turma</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="btnCadEvento" class="btn btn-success" id="btnCadEvento">Cadastrar</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src='js/index.global.min.js'></script>
        <script src="js/bootstrap5/index.global.min.js"></script>
        <script src='js/core/locales-all.global.min.js'></script>
        <script src="js/custom.js"></script>

</body>

</html>