#Projeto email marketing V2

# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(122, 'Cadastro do Briefing/Pedido', 0, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(122, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(6, 122, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(122, 'Etapa', 'Tarefa', 'etapa', 1),
(122, 'Observacoes', 'Observações', 'text', 1),
(122, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(122, 'desdobrarPeca', 'Desdobrar peca ?', 'boolean', 1),
(122, 'enviarMidia', 'Enviar para Midia ?', 'boolean', 1);


# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(240, 122, 'Cadastro do Briefing', 4, 241, NULL, NULL, NULL, 1),
(241, 122, 'Criar Conteúdo das Peças de Mídia', 6, 242, NULL, NULL, NULL, 1),
(242, 122, 'Aprovar Conteúdo das Peças de Mídia', 12, 245, 243, NULL, NULL, 1),
(243, 122, 'Alterar Conteúdo das Peças de Mídia', 6, 244, NULL, NULL, NULL, 1),
(244, 122, 'Aprovar Alteracao do Conteúdo das Peças de Mídia', 12, 245, 243, NULL, NULL, 1),
(245, 122, 'Aprovar Conteúdo das Peças de Mídia', 4, 246, 243, NULL, NULL, 1),
(246, 122, 'Criar Peça Conceito', 9, 247, NULL, NULL, NULL, 1),
(247, 122, 'Aprovar Peça Conceito', 11, 250, 248, NULL, NULL, 1),
(248, 122, 'Alterar Peça Conceito', 9, 249, NULL, NULL, NULL, 1),
(249, 122, 'Aprovar Alteracao da Peça Conceito', 11, 250, 248, NULL, NULL, 1),
(250, 122, 'Aprovar Peça Conceito', 4, 251, 248, NULL, NULL, 1),
(251, 122, 'Decisao 1, Desdobrar ?', 22, NULL, NULL, NULL, NULL, 1),

(252, 122, 'Desdobrar Peças', 9, 253, NULL, NULL, NULL, 1),
(253, 122, 'Revisar Desdobramento de Peças ', 11, 256, 254, NULL, NULL, 1),
(254, 122, 'Alterar Desdobramento das Peças', 9, 255, NULL, NULL, NULL, 1),
(255, 122, 'Revisar Alteracao do Desdobramento de Peças ', 11, 256, 254, NULL, NULL, 1),
(256, 122, 'Aprovar Peças de Mídia', 4, 257, 254, NULL, NULL, 1),
(257, 122, 'Decisao 2, Enviar pra midia?', 22, NULL, NULL, NULL, NULL, 1),
(258, 122, 'Aprovar & Publicar Campanha', 9, NULL, NULL, NULL, NULL, 1);


# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(18, 'Pecas de Midia - V2', 'Producao de peças de midia', 1, '#CD7CB1', 'fa-picture-o', 'POP_PROJETO_FLUXO_DE_MIDIA_V2', 0, 1);

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(18, 122, 1);

