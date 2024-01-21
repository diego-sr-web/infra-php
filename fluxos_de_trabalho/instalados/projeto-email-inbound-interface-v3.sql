#Projeto Inbound E-mail V2
# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(127, 'Cadastro do Briefing/Pedido', 0, 1);


# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(127, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(25, 127, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(127, 'Etapa', 'Tarefa', 'etapa', 1),
(127, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
(127, 'AreaTipo', 'Area Tipo', 'area', 1),
(127, 'Observacoes', 'Observações', 'text', 1),
(127, 'briefing', 'Briefing', 'editor', 1),
(127, 'conteudo', 'Conteudo', 'editor', 1),
(127, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(127, 'gdrive', 'Link da Arte', 'gdrive', 1),
(127, 'url', 'Link do HTML', 'text', 1),
(127, 'Texto', 'Texto Simples', 'text', 1),
(127, 'assunto', 'Opçoes de Assunto', 'text', 1),
(127, 'textoApoio', 'Opçoes de Apoio', 'textarea', 1),
(127, 'geraHtml', 'Gerar html ?', 'boolean', 1),
(127, 'referencia', 'referencia', 'arquivo', 1),
(127, 'enviarInbound', 'Vai ser disparado aqui?', 'boolean', 1);


# Comeco da parte de projeto
REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(23, 'E-mail Inbound via interface', 'Producao de Inbound E-mail', 1, '#FD8340', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V3.2', 0, 1);


#Campos Extras na criacao do projeto
REPLACE INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`)
VALUES (23, 'briefing', 'Briefing do Projeto', 'editor', '1');


# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(23, 127, 1);
