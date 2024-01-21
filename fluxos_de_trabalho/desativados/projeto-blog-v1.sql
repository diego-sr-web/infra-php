SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


# Tipo de elemento
INSERT INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
  (106, 'Cadastrar e Criar Posts', 0, 1),
  (107, 'Texto', 0, 1),
  (108, 'Texto + Publicacao Facebook', 0, 1),
  (109, 'Texto + Publicacao Facebook + Impulsionamento', 0, 1);

# Criando o link entre os tipo de elemento base
INSERT INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
  (106, 1, 1),
  (107, 1, 1),
  (108, 1, 1),
  (109, 1, 1);

# Inserindo a Area Base do tipo
INSERT INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
  (6, 106, 1),
  (6, 107, 1),
  (6, 108, 1),
  (6, 109, 1);


# Campos Extras
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
  (106, 'Etapa', 'Tarefa', 'etapa', 1),
  (106, 'Observacoes', 'Observações', 'text', 1),
  (107, 'Etapa', 'Tarefa', 'etapa', 1),
  (107, 'Observacoes', 'Observações', 'text', 1),
  (108, 'Etapa', 'Tarefa', 'etapa', 1),
  (108, 'Observacoes', 'Observações', 'text', 1),
  (109, 'Etapa', 'Tarefa', 'etapa', 1),
  (109, 'Observacoes', 'Observações', 'text', 1);



# Sub Etapas dos Elementos
INSERT INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
  (159, 106, 'Cadastrar Pautas', 6, 160, NULL, NULL, NULL, 1),
  (160, 106, 'Aprovar Pautas com Cliente', 4, 163, 161, NULL, NULL, 1),
  (161, 106, 'Alterar Pautas', 6, 162, NULL, NULL, NULL, 1),
  (162, 106, 'Aprovar Pautas Alteradas com Cliente', 4, 163, 161, NULL, NULL, 1),
  (163, 106, 'Criar Posts', 6, NULL, NULL, NULL, NULL, 1),
  (164, 107, 'Produzir Texto', 6, 165, NULL, NULL, NULL, 1),
  (165, 107, 'Revisar Texto', 12, 166, 167, NULL, NULL, 1),
  (166, 107, 'Aprovar Texto com cliente', 4, 170, 167, NULL, NULL, 1),
  (167, 107, 'Alterar Texto', 6, 168, NULL, NULL, NULL, 1),
  (168, 107, 'Revisar Texto Alterado', 12, 169, 167, NULL, NULL, 1),
  (169, 107, 'Aprovar Texto Alterado com cliente', 4, 170, 167, NULL, NULL, 1),
  (170, 107, 'Definir data de Publicacao', 6, 171, NULL, NULL, NULL, 1),
  (171, 107, 'Publicar Texto', 6, 172, NULL, NULL, NULL, 1),
  (172, 107, 'Revisar publicacao no Blog', 12, NULL, NULL, NULL, NULL, 1),
  (173, 108, 'Produzir Texto', 6, 174, NULL, NULL, NULL, 1),
  (174, 108, 'Revisar Texto', 12, 175, 176, NULL, NULL, 1),
  (175, 108, 'Aprovar Texto com cliente', 4, 179, 176, NULL, NULL, 1),
  (176, 108, 'Alterar Texto', 6, 177, NULL, NULL, NULL, 1),
  (177, 108, 'Revisar Texto Alterado', 12, 178, 176, NULL, NULL, 1),
  (178, 108, 'Aprovar Texto Alterado com cliente', 4, 179, 176, NULL, NULL, 1),
  (179, 108, 'Definir data de Publicacao', 6, 180, NULL, NULL, NULL, 1),
  (180, 108, 'Publicar Texto', 6, 181, NULL, NULL, NULL, 1),
  (181, 108, 'Revisar publicacao no Blog', 12, 182, NULL, NULL, NULL, 1),
  (182, 108, 'Publicar no Facebook', 6, NULL, NULL, NULL, NULL, 1),
  (183, 109, 'Produzir Texto', 6, 184, NULL, NULL, NULL, 1),
  (184, 109, 'Revisar Texto', 12, 185, 186, NULL, NULL, 1),
  (185, 109, 'Aprovar Texto com cliente', 4, 189, 186, NULL, NULL, 1),
  (186, 109, 'Alterar Texto', 6, 187, NULL, NULL, NULL, 1),
  (187, 109, 'Revisar Texto Alterado', 12, 188, 186, NULL, NULL, 1),
  (188, 109, 'Aprovar Texto Alterado com cliente', 4, 189, 186, NULL, NULL, 1),
  (189, 109, 'Definir data de Publicacao', 6, 190, NULL, NULL, NULL, 1),
  (190, 109, 'Publicar Texto', 6, 191, NULL, NULL, NULL, 1),
  (191, 109, 'Revisar publicacao no Blog', 12, 192, NULL, NULL, NULL, 1),
  (192, 109, 'Publicar no Facebook', 6, 193, NULL, NULL, NULL, 1),
  (193, 109, 'Impulsionar Post do Blog no Facebook', 10, 194, NULL, NULL, NULL, 1),
  (194, 109, 'Revisar Impulsionamento do Post', 10, NULL, 193, NULL, NULL, 1);



# Comeco da parte de projeto

INSERT INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
  (14, 'Producao Blog', 'Producao e revisao de textos para blogs e outras medias', 1, '#36d209', 'fa-file-word', 'POP_PROJETO_PRODUCAO-BLOG', 0, 1);

# Dados Extra projeto
INSERT INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
  (14, 'end', NULL, 'date', 1),
  (14, 'start', NULL, 'date', 1);


# Primeiros elementos criados no projeto
INSERT INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
  (14, 106, 1);
COMMIT;



COMMIT;
