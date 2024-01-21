# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(131, 'Criar Conteudo do E-mail', 0, 1);


# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(131, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(6, 131, 1);


REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(131, 'Etapa', 'Tarefa', 'etapa', 1),
(131, 'Observacoes', 'Observações', 'text', 1),
(131, 'briefing', 'Briefing', 'editor', 1),
(131, 'conteudo', 'Conteudo', 'editor', 1),
(131, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(131, 'gdrive', 'Link da Arte', 'gdrive', 1),
(131, 'url', 'Link do HTML', 'text', 1),
(131, 'assunto', 'Assunto', 'text', 1),
(131, 'geraHtml', 'Gerar html ?', 'boolean', 1),
(131, 'referencia', 'referencia', 'arquivo', 1),
(131, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
(131, 'AreaTipo', 'Area Tipo', 'area', 1),
(131, 'Texto', 'Texto Simples', 'text', 1),
(131, 'textoApoio', 'Opçoes de Apoio', 'textarea', 1),
(131, 'enviarInbound', 'Vai ser disparado aqui? ', 'boolean', 1);


# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(420, 131, 'Criar Conteudo do E-mail', 6, 422, NULL, NULL, NULL, 1),
(421, 131, 'Alterar Conteudo do E-mail', 6, 422, NULL, NULL, NULL, 1),
(422, 131, 'Criar Arte do E-mail', 3, 424, 421, NULL, NULL, 1),
(423, 131, 'Alterar Arte do E-mail', 3, 424, 421, NULL, NULL, 1),
(424, 131, 'Revisar E-mail Criado', 30, 425, 426, NULL, NULL, 1),
(425, 131, 'Aprovar E-mail Com Cliente', 4, 427, 426, NULL, NULL, 1),
(426, 131, 'Decisao 1, Reprovacao Condicional 6 / 9', 22, NULL, NULL, NULL, NULL, 1),
(427, 131, 'Decisao 2, Tem HTML ?', 22, NULL, NULL, NULL, NULL, 1),
(428, 131, 'Criar HTML', 7, 429, NULL, NULL, NULL, 1),
(429, 131, 'Decisao 3, Disparado Aqui?', 22, NULL, NULL, NULL, NULL, 1),
(430, 131, 'Programar E-mail', 25, 431, NULL, NULL, NULL, 1),
(431, 131, 'E-mail Finalizado', 4, NULL, NULL, NULL, NULL, 1);


# Comeco da parte de projeto
REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(26, 'E-mail Marketing via interface', 'Producao de email Marketing', 1, '#F0B64A', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V4.2', 0, 1);


#Campos Extras na criacao do projeto
REPLACE INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`)
VALUES (26, 'briefing', 'Briefing do Projeto', 'editor', '1');


# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(26, 131, 1);
