#Projeto email marketing V2

# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(132, 'Criar Conteúdo do Material Gráfico', 6, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(132, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(6, 132, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(132, 'briefing', 'Briefing', 'editor', 1),
(132, 'conteudo', 'Conteudo', 'editor', 1),
(132, 'gdrive', 'Link do Drive', 'gdrive', 1),
(132, 'imagem', 'Arte', 'file', 1),
(132, 'Observacoes', 'Observações', 'textarea', 1),
(132, 'Etapa', 'Tarefa', 'etapa', 1),
(132, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
(132, 'fechaImpressao', 'Fechar para impressao?', 'boolean', 1);

# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(450, 132, 'Criar Conteúdo do Material Gráfico', 6, 451, NULL, NULL, NULL, 1),
(451, 132, 'Aprovar Conteúdo do Material Gráfico', 4, 454, 452, NULL, NULL, 1),
(452, 132, 'Alterar Conteúdo do Material Gráfico', 6, 453, NULL, NULL, NULL, 1),
(453, 132, 'Aprovar Conteúdo Alterado do Material Gráfico', 4, 454, 452, NULL, NULL, 1),
(454, 132, 'Criar Arte do Material Gráfico', 9, 455, 452, NULL, NULL, 1),
(455, 132, 'Aprovar Material Gráfico', 30, 459, 458, NULL, NULL, 1),
(456, 132, 'Alterar Arte do Material Gráfico', 9, 457, NULL, NULL, NULL, 1),
(457, 132, 'Aprovar Material Gráfico Alterado', 30, 459, 458, NULL, NULL, 1),
(458, 132, 'Decisao 1, Pra Aonde ?', 22, NULL, NULL, NULL, NULL, 1),
(459, 132, 'Aprovar Material Grafico', 4, 460, 458, NULL, NULL, 1),
(460, 132, 'Decisao 2, Tem Fechamento ?', 22, NULL, NULL, NULL, NULL, 1),
(461, 132, 'Fechar Para Impressão', 9, 462, NULL, NULL, NULL, 1),
(462, 132, 'Entregar Material Gráfico Fechado para Impressão', 4, NULL, 463, NULL, NULL, 1),
(463, 132, 'Alterar Fechamento do Material Gráfico', 9, 464, NULL, NULL, NULL, 1),
(464, 132, 'Entregar Material Gráfico Alterado Fechado para Impressão', 4, NULL, 463, NULL, NULL, 1);


# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(28, 'Material Gráfico - V3', 'Producao de material Grafico', 1, '#CD7CB1', 'fa-file-image-o', 'POP_PROJETO_MATERIAL_GRAFICO_V2', 0, 1);

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(28, 132, 1);
COMMIT;
