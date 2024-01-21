# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
  (130, 'Criar Conteudo do E-mail', 0, 1);


# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
  (130, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
  (6, 130, 1);


REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
    (130, 'Etapa', 'Tarefa', 'etapa', 1),
    (130, 'Observacoes', 'Observações', 'text', 1),
    (130, 'briefing', 'Briefing', 'editor', 1),
    (130, 'conteudo', 'Conteudo', 'editor', 1),
    (130, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
    (130, 'gdrive', 'Link da Arte', 'gdrive', 1),
    (130, 'url', 'Link do HTML', 'text', 1),
    (130, 'assunto', 'Assunto', 'text', 1),
    (130, 'geraHtml', 'Gerar html ?', 'boolean', 1),
    (130, 'referencia', 'referencia', 'arquivo', 1),
    (130, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
    (130, 'AreaTipo', 'Area Tipo', 'area', 1),
    (130, 'Texto', 'Texto Simples', 'text', 1),
    (130, 'textoApoio', 'Opçoes de Apoio', 'textarea', 1),
    (130, 'enviarInbound', 'Vai ser disparado aqui? ', 'boolean', 1);


# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
  (400, 130, 'Criar Conteudo do E-mail', 6, 402, NULL, NULL, NULL, 1),
  (401 , 130, 'Alterar Conteudo do E-mail', 6, 402, NULL, NULL, NULL, 1),
  (402, 130, 'Criar Arte do E-mail', 9, 404, 401, NULL, NULL, 1),
  (403, 130, 'Alterar Arte do E-mail', 9, 404, 401, NULL, NULL, 1),
  (404, 130, 'Revisar E-mail Criado', 30, 405, 406, NULL, NULL, 1),
  (405, 130, 'Aprovar E-mail Com Cliente', 4, 407, 406, NULL, NULL, 1),
  (406, 130, 'Decisao 1, Reprovacao Condicional 6 / 9', 22, NULL, NULL, NULL, NULL, 1),
  (407, 130, 'Decisao 2, Tem HTML ?', 22, NULL, NULL, NULL, NULL, 1),
  (408, 130, 'Criar HTML', 7, 409, NULL, NULL, NULL, 1),
  (409, 130, 'Decisao 3, Disparado Aqui?', 22, NULL, NULL, NULL, NULL, 1),
  (410, 130, 'Programar E-mail', 25, 411, NULL, NULL, NULL, 1),
  (411, 130, 'E-mail Finalizado', 4, NULL, NULL, NULL, NULL, 1);


# Comeco da parte de projeto
REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
  (25, 'E-mail Marketing via criação', 'Producao de email Marketing', 1, '#F0B64A', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V4.1', 0, 1);


#Campos Extras na criacao do projeto
REPLACE INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`)
VALUES (25, 'briefing', 'Briefing do Projeto', 'editor', '1');


# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
  (25, 130, 1);
