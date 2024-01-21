SET FOREIGN_KEY_CHECKS=0;

# Cria o elemento tipo
INSERT INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES(105, 'Pedido-V3', 0, 1);
INSERT INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES (105, 1, 1);

# Cria os campos do elemento tipo
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Aprovar', NULL, 'boolean', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'arquivos', 'Material Extra', 'file', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Cliente', 'Nome Cliente', 'text', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Descricao', 'Descricao', 'Text', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'De_Area', NULL, 'area', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Etapa', 'Etapa', 'etapa', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Finalizado', NULL, 'bool', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Nome', NULL, 'text', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Observacoes', 'Observações', 'textarea', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Para_Area', NULL, 'area', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'Produto', 'Produto', 'text', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'responsavel_de', 'Responsável', 'responsavel', 1);
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES(105, 'responsavel_para', 'Responsável', 'responsavel', 1);

# Cria as etapas do elemento tipo
INSERT INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES(155, 105, 'Etapa 1: Pedido reprovado pela area destino', NULL, 156, NULL, NULL, NULL, 1);
INSERT INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES(156, 105, 'Etapa 2: Pedido recebido na area destino', NULL, 157, 155, NULL, NULL, 1);
INSERT INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES(157, 105, 'Etapa 3: Pedido para ser aprovado pelo gestor', NULL, 158, 156, NULL, NULL, 1);
INSERT INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES(158, 105, 'Etapa 4: Pedido pra ser aprovado por quem pediu', NULL, NULL, 156, NULL, NULL, 1);

SET FOREIGN_KEY_CHECKS=1;