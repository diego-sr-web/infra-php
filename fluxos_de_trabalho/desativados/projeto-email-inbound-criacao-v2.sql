#Projeto email marketing V2

# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(120, 'Cadastro do Briefing/Pedido', 0, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(120, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(4, 120, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(120, 'Etapa', 'Tarefa', 'etapa', 1),
(120, 'Observacoes', 'Observações', 'text', 1),
(120, 'briefing', 'Briefing', 'editor', 1),
(120, 'conteudo', 'Conteudo', 'editor', 1),
(120, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(120, 'gdrive', 'Link da Arte', 'gdrive', 1),
(120, 'url', 'Link do HTML', 'text', 1),
(120, 'assunto', 'Assunto', 'text', 1),
(120, 'geraHtml', 'Gerar html ?', 'boolean', 1),
(120, 'referencia', 'referencia', 'arquivo', 1),
(120, 'enviarInbound', 'Vai ser disparado aqui? ', 'boolean', 1);


# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(200, 120, 'Cadastro do Briefing', 4, 201, NULL, NULL, NULL, 1),
(201, 120, 'Criar Conteúdo do E-mail Marketing', 6, 202, NULL, NULL, NULL, 1),
(202, 120, 'Aprovar Conteúdo do E-mail Marketing', 12, 205, 203, NULL, NULL, 1),
(203, 120, 'Alterar Conteúdo do E-mail Marketing', 6, 204, NULL, NULL, NULL, 1),
(204, 120, 'Revisar Conteúdo Alterado do E-mail Marketing', 12, 205, NULL, NULL, NULL, 1),
(205, 120, 'Aprovar Conteúdo E-mail Marketing', 4, 206, 203, NULL, NULL, 1),
(206, 120, 'Decisao 1, Qual Area ?', 22, NULL, NULL, NULL, NULL, 1),
#CAMINHO A
(207, 120, 'Criar Arte do e-mail', 9, 208, NULL, NULL, NULL, 1),
(208, 120, 'Revisar Arte do e-mail', 11, 211, 209, NULL, NULL, 1),
(209, 120, 'Alterar Arte do e-mail', 9, 210, NULL, NULL, NULL, 1),
(210, 120, 'Revisar Alteração da Arte do e-mail', 11, 211, 209, NULL, NULL, 1),
(211, 120, 'Aprovar Arte do e-mail', 4, 217, 209, NULL, NULL, 1),
#CAMINHO B
(212, 120, 'Criar Arte do e-mail', 3, 213, NULL, NULL, NULL, 1),
(213, 120, 'Revisar Arte do e-mail', 13, 215, 214, NULL, NULL, 1),
(214, 120, 'Alterar Arte do e-mail', 3, 215, NULL, NULL, NULL, 1),
(215, 120, 'Revisar Alteração da Arte do e-mail', 13, 216, 214, NULL, NULL, 1),
(216, 120, 'Aprovar Arte do e-mail', 4, 217, 208, NULL, NULL, 1),
#CAMINHO COMUM
(217, 120, 'Decisao 2, Gera HTML ?', 22, NULL, NULL, NULL, NULL, 1),
(218, 120, 'Montar HTML do e-mail marketing', 7, 219, NULL, NULL, NULL, 1),
(219, 120, 'Entregar HTML do e-mail marketing', 4, 222, 220, NULL, NULL, 1),
(220, 120, 'Alterar HTML do e-mail marketing', 7, 221, NULL, NULL, NULL, 1),
(221, 120, 'Entregar HTML do e-mail marketing Alterado', 4, 222, 220, NULL, NULL, 1),
(222, 120, 'Decisao 3, Enviar pra inbound ?', 22, NULL, NULL, NULL, NULL, 1),
(223, 120, 'Publicar / Disparar e-mail marketing', 25, 224, NULL, NULL, NULL, 1),
(224, 120, 'Email Programado', 4, NULL, NULL, NULL, NULL, 1);


# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(16, 'E-mail Inbound via criação', 'Producao de email Marketing', 1, '#FD8340', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V2', 0, 1);

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(16, 120, 1);
COMMIT;
