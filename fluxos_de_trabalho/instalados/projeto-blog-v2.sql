SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


# Insere a area nova que eh de automacao
INSERT INTO `back_AdmArea` (`area`, `nome`, `cor`, `hidden`, `isUsed`) VALUES
(22, 'Sistema e Automacao', '#804000', 1, 1);

# Tipo de elemento
INSERT INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(106, 'Cadastrar e Criar Pautas', 0, 1),
(107, 'Criacao de texto do Post', 0, 1);

# Criando o link entre os tipo de elemento base
INSERT INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(106, 1, 1),
(107, 1, 1);

# Inserindo a Area Base do tipo
INSERT INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(14, 106, 1),
(4, 107, 1);


# Campos Extras
INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(106, 'Observacoes', 'Observações', 'text', 1),
(107, 'Etapa', 'Tarefa', 'etapa', 1),
(107, 'Observacoes', 'Observações', 'text', 1),
(107, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(107, 'tituloPost', 'Titulo', 'text', 1),
(107, 'imagemPost', 'Imagem de Capa', 'img', 1),
(107, 'slugPost', 'Slug', 'text', 1),
(107, 'conteudoPost', 'Conteudo', 'textarea', 1),
(107, 'temFacebookPost', 'Publicar no Facebook?', 'boolean', 1),
(107, 'impulsionarPost', 'Impulsionar Post?', 'boolean', 1),
(107, 'verbaPost', 'Verba para Impulsionamento', 'textarea', 1),
(107, 'editoria', 'Editoria', 'text', 1),
(107, 'referencias', 'Referencias', 'text', 1);



# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(159, 107, 'Aprovar Pautas com Cliente', 4, 162, 160, NULL, NULL, 1),
(160, 107, 'Alterar Pautas', 14, 161, NULL, NULL, NULL, 1),
(161, 107, 'Aprovar Pautas Alteradas com Cliente', 4, 162, 160, NULL, NULL, 1),
(162, 107, 'Produzir Texto', 14, 164, NULL, NULL, NULL, 1),
(163, 107, 'Revisar Texto', 12, 164, 165, NULL, NULL, 1), #SAI
(164, 107, 'Aprovar Texto com cliente', 4, 169, 165, NULL, NULL, 1),
(165, 107, 'Alterar Texto', 14, 167, NULL, NULL, NULL, 1),
(166, 107, 'Revisar Texto Alterado', 12, 167, 165, NULL, NULL, 1),#SAI
(167, 107, 'Aprovar Texto Alterado com cliente', 4, 169, 165, NULL, NULL, 1),
(168, 107, 'Definir data de Publicacao', 14, 169, NULL, NULL, NULL, 1), # SAI
(169, 107, 'Publicar Texto', 14, 171, NULL, NULL, NULL, 1),
(170, 107, 'Revisar publicacao no Blog', 12, 171, NULL, NULL, NULL, 1), #SAI
(171, 107, 'Decisao 1, Facebook/impulsionamento', 22, NULL, NULL, NULL, NULL, 1),
(172, 107, 'Publicar nas Redes Sociais', 6, 173, NULL, NULL, NULL, 1),
(173, 107, 'Decisao 2, Impulsionamento', 22, NULL, NULL, NULL, NULL, 1),
(174, 107, 'Impulsionar Post do Blog', 10, NULL, NULL, NULL, NULL, 1),
(175, 107, 'Revisar Impulsionamento do Post', 10, NULL, 174, NULL, NULL, 1);



# Comeco da parte de projeto

INSERT INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(14, 'Producao Blog', 'Producao e revisao de textos para blogs e outras medias', 1, '#36d209', 'fa-file-word', 'POP_PROJETO_PRODUCAO-BLOG', 0, 1);

# Dados Extra projeto
#INSERT INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
#  (14, 'end', NULL, 'date', 1),
#  (14, 'start', NULL, 'date', 1);


# Primeiros elementos criados no projeto
INSERT INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(14, 106, 1);
COMMIT;
