#Projeto Inbound E-mail V2

# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(125, 'Cadastro do Briefing/Pedido', 0, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(125, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(25, 125, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(125, 'Etapa', 'Tarefa', 'etapa', 1),
(125, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
(125, 'AreaTipo', 'Area Tipo', 'area', 1),
(125, 'Observacoes', 'Observações', 'text', 1),
(125, 'briefing', 'Briefing', 'editor', 1),
(125, 'conteudo', 'Conteudo', 'editor', 1),
(125, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(125, 'gdrive', 'Link da Arte', 'gdrive', 1),
(125, 'url', 'Link do HTML', 'text', 1),
(125, 'Texto', 'Texto Simples', 'text', 1),
(125, 'assunto', 'Opçoes de Assunto', 'text', 1),
(125, 'textoApoio', 'Opçoes de Apoio', 'textarea', 1),
(125, 'geraHtml', 'Gerar html ?', 'boolean', 1),
(125, 'referencia', 'referencia', 'arquivo', 1),
(125, 'enviarInbound', 'Vai ser disparado aqui?', 'boolean', 1);


# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(349, 125, 'Cadastro do Briefing', 25, 351, NULL, NULL, NULL, 1),
(350, 125, 'Alterar Briefing', 25, 351, NULL, NULL, NULL, 1), #SAI
(351, 125, 'Criar Conteúdo do Inbound E-mail', 6, 383, 350, NULL, NULL, 1),
(352, 125, 'Aprovar Conteúdo do Inbound E-mail', 12, 383, 353, NULL, NULL, 1), #SAI
(353, 125, 'Alterar Conteúdo do Inbound E-mail', 6, 384, NULL, NULL, NULL, 1),
(354, 125, 'Revisar Conteúdo Alterado do Inbound E-mail', 12, 384, 353, NULL, NULL, 1), #SAI
(355, 125, 'Aprovar Conteúdo Inbound E-mail', 4, 356, 353, NULL, NULL, 1),
(356, 125, 'Decisao 1, Qual Area ?', 22, NULL, NULL, NULL, NULL, 1),

#CAMINHO A ( Criacao )
(357, 125, 'Criar Arte do Email', 9, 359, 353, NULL, NULL, 1),
(358, 125, 'Aprovar Arte do Inbound E-mail', 11, 359, 360, NULL, NULL, 1), #SAI
(359, 125, 'Validar Arte do Inbound E-mail', 30, 363, 360, NULL, NULL, 1),
(360, 125, 'Alterar Arte do Inbound E-mail', 9, 362, NULL, NULL, NULL, 1),
(361, 125, 'Aprovar Arte do Inbound E-mail Alterado', 11, 362, 360, NULL, NULL, 1), #SAI
(362, 125, 'Validar Arte do Inbound E-mail Alterado', 30, 363, 360, NULL, NULL, 1),
(363, 125, 'Aprovar Inbound E-mail', 4, 371, 379, NULL, NULL, 1),

#CAMINHO B ( Interface )
(364, 125, 'Criar Arte do Email', 3, 366, 353, NULL, NULL, 1),
(365, 125, 'Aprovar Arte do Inbound E-mail', 13, 366, 367, NULL, NULL, 1), #SAI
(366, 125, 'Validar Arte do Inbound E-mail', 30, 370, 367, NULL, NULL, 1),
(367, 125, 'Alterar Arte do Inbound E-mail', 3, 369, NULL, NULL, NULL, 1),
(368, 125, 'Aprovar Arte do Inbound E-mail Alterado', 13, 369, 367, NULL, NULL, 1), #SAI
(369, 125, 'Validar Arte do Inbound E-mail Alterado', 30, 370, 367, NULL, NULL, 1),
(370, 125, 'Aprovar Inbound E-mail', 4, 371, 379, NULL, NULL, 1),

#CAMINHO COMUM
(371, 125, 'Decisao 2, Gera HTML ?', 22, NULL, NULL, NULL, NULL, 1),
(372, 125, 'Montar HTML do Inbound E-mail', 7, 373, 380, NULL, NULL, 1),
(373, 125, 'Entregar HTML do Inbound E-mail', 4, 376, 374, NULL, NULL, 1),
(374, 125, 'Alterar HTML do Inbound E-mail', 7, 375, NULL, NULL, NULL, 1),
(375, 125, 'Entregar HTML do Inbound E-mail Alterado', 4, 376, 374, NULL, NULL, 1),
(376, 125, 'Decisao 3, Enviar pra inbound ?', 22, NULL, NULL, NULL, NULL, 1),
(377, 125, 'Agendar Inbound E-mail', 25, 378, 374, NULL, NULL, 1),
(378, 125, 'Email Programado/Finalizado', 4, NULL, 377, NULL, NULL, 1),
(379, 125, 'Decisao 4, Reprovacao Condicional', 22, NULL, NULL, NULL, NULL, 1),
(380, 125, 'Decisao 5, Reprovacao Interface/Criacao', 22, NULL, NULL, NULL, NULL, 1),


#Etapas novas de inbound
(381, 125, 'Validar Briefing', 25, 351, 350, NULL, NULL, 1), #SAI
(382, 125, 'Validar Briefing Alterado', 25, 351, 350, NULL, NULL, 1), #SAI
(383, 125, 'Validar Conteúdo', 25, 355, 353, NULL, NULL, 1),
(384, 125, 'Validar Conteúdo Alterado', 25, 355, 353, NULL, NULL, 1);


# Comeco da parte de projeto
REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(21, 'E-mail Inbound via Criação', 'Producao de Inbound E-mail', 1, '#FD8340', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V3', 0, 1);


#Campos Extras na criacao do projeto
REPLACE INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`)
VALUES (21, 'briefing', 'Briefing do Projeto', 'editor', '1');

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(21, 125, 1);
