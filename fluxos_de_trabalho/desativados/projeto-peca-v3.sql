#Projeto pecas de midia v3

# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(126, 'Cadastro do Briefing/Pedido', 0, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(126, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(4, 126, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(126, 'Etapa', 'Tarefa', 'etapa', 1),
(126, 'Observacoes', 'Observações', 'text', 1),
(126, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(126, 'desdobrarPeca', 'Vai ter desdobramento?', 'boolean', 1),
(126, 'briefing', 'Briefing', 'editor', 1),
(126, 'conteudo', 'Conteúdo das Peças', 'editor', 1),
(126, 'referencia', 'Referencias', 'editor', 1),
(126, 'url', 'Formatos / Proporções & URLs', 'editor', 1),
(126, 'gdrive', 'Link do Drive', 'gdrive', 1),
(126, 'midia', 'Plano de Midia', 'text', 1),
(126, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1);

# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(300, 126, 'Solicitação de Peças de Mídia', 4, 301, NULL, NULL, NULL, 1),
(301, 126, 'Cadastro do Briefing ', 10, 303, 300, NULL, NULL, 1),
(302, 126, 'Alterar Briefing ', 10, 303, 300, NULL, NULL, 1),
(303, 126, 'Criar Conteúdo das Peças de Mídia', 6, 304, 302, NULL, NULL, 1),
(304, 126, 'Revisar Conteúdo das Peças de Mídia', 12, 325, 305, NULL, NULL, 1),
(305, 126, 'Alterar Conteúdo das Peças de Mídia', 6, 306, NULL, NULL, NULL, 1),
(306, 126, 'Aprovar Conteúdo Alterado das Peças de Mídia', 12, 325, 305, NULL, NULL, 1),
(307, 126, 'Aprovar Conteúdo das Peças de Mídia', 4, 308, 305, NULL, NULL, 1),
(308, 126, 'Criar Peça Conceito', 9, 309, 305, NULL, NULL, 1),
(309, 126, 'Aprovar Peça Conceito', 11, 312, 310, NULL, NULL, 1),
(310, 126, 'Alterar Peça Conceito', 9, 311, NULL, NULL, NULL, 1),
(311, 126, 'Aprovar Peça Conceito Alterada', 11, 312, 310, NULL, NULL, 1),
(312, 126, 'Aprovar Peças Conceito', 12, 313, 310, NULL, NULL, 1),
(313, 126, 'Aprovar Peças Conceito', 10, 314, 310, NULL, NULL, 1),
(314, 126, 'Aprovar Peças Conceito', 4, 316, 315, NULL, NULL, 1),
(315, 126, 'Decisao 1, Reprovacao Condicional 6 / 14 ', 22, NULL, NULL, NULL, NULL, 1),
(316, 126, 'Decisao 2 , Desdobrar?', 22, NULL, NULL, NULL, NULL, 1),
(317, 126, 'Desdobrar Peças', 9, 318, 326, NULL, NULL, 1),
(318, 126, 'Revisar Peças', 11, 321, 319, NULL, NULL, 1),
(319, 126, 'Alterar Peças', 9, 320, 320, NULL, NULL, 1),
(320, 126, 'Revisar Peças Alteradas', 11, 321, 319, NULL, NULL, 1),
(321, 126, 'Aprovar Pecas ', 12, 322, 319, NULL, NULL, 1),
(322, 126, 'Aprovar Peças de Mídia', 4, 323, 319, NULL, NULL, 1),
(323, 126, 'Aprovar & Publicar Campanha', 10, 324, 319, NULL, NULL, 1),
(324, 126, 'Campanha Programada', 4, NULL, 323, NULL, NULL, 1),
(325, 126, 'Revisar Conteúdo', 10, 307, 305, NULL, NULL, 1),
(326, 126, 'Completar Informações para Desdobramento', 4, 317, NULL, NULL, NULL, 1);

# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(24, 'Pecas de Midia - V3', 'Producao de peças de midia', 1, '#CD7CB1', 'fa-picture-o', 'POP_PROJETO_FLUXO_DE_MIDIA_V3', 0, 1);

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(24, 126, 1);

