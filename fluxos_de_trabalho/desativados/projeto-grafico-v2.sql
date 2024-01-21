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
(122, 'Observacoes', 'Observações', 'text', 1),
(122, 'Arquivos', 'Link Drive', 'gdrive', 1),
(122, 'Descricao', 'Observações', 'textarea', 1),
(122, 'fechaImpressao', 'Fechar para impressao ?', 'boolean', 1);

# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(220, 122, 'Cadastro do Briefing', 4, 221, NULL, NULL, NULL, 1),
(221, 122, 'Criar Conteúdo do Material Gráfico', 6, 222, NULL, NULL, NULL, 1),
(222, 122, 'Aprovar Conteúdo do Material Gráfico', 12, 225, 223, NULL, NULL, 1),
(223, 122, 'Alterar Conteúdo do Material Gráfico', 6, 224, NULL, NULL, NULL, 1),
(223, 122, 'Aprovar Conteúdo Alterado do Material Gráfico', 6, 225, 223, NULL, NULL, 1),
(225, 122, 'Aprovar Conteúdo do Material Gráfico', 4, 226, 223, NULL, NULL, 1),
(226, 122, 'Criar Material Gráfico', 9, 227, NULL, NULL, NULL, 1),
(227, 122, 'Aprovar Material Gráfico', 11, 230, 228, NULL, NULL, 1),
(228, 122, 'Alterar Material Gráfico', 9, 229, NULL, NULL, NULL, 1),
(229, 122, 'Aprovar Alteracao do Material Gráfico', 11, 230, 228, NULL, NULL, 1),
(230, 122, 'Aprovar Material Gráfico', 4, 231, 228, NULL, NULL, 1),
(231, 122, 'Decisao 1, Fecha pra impressao ?', 22, NULL, NULL, NULL, NULL, 1),

(232, 122, 'Fechar para Impressão', 6, 233, NULL, NULL, NULL, 1),
(233, 122, 'Aprovar Fechamento para Impressão', 9, 236, 234, NULL, NULL, 1),
(234, 122, 'Alterar Fechamento Material Gráfico', 6, 235, NULL, NULL, NULL, 1),
(235, 122, 'Aprovar Alteracao Fechamento para Impressão', 9, 236, 235, NULL, NULL, 1),
(236, 122, 'Entregar Material Gráfico Fechado para Impressão', 4, NULL, NULL, NULL, NULL, 1);


# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(17, 'Material Gráfico - V2', 'Producao de material Grafico', 1, '#CD7CB1', 'fa-file-image-o', 'POP_PROJETO_MATERIAL_GRAFICO_V2', 0, 1);

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(17, 122, 1);
COMMIT;
