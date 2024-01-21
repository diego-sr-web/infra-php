SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
  (109, 'Criar Post', 0, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
  (109, 1, 1);

# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
  (6, 109, 1);


# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
  (109, 'Etapa', 'Tarefa', 'etapa', 1),
  (109, 'impulsionarPost', 'Impulsionar Post?', 'boolean', 1),
  (109, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
  (109, 'arquivos', 'Imagem do Post', 'file', 1),
  (109, 'referencia', 'Referencia Texto', 'textarea', 1),
  (109, 'legenda', 'Legenda do Post', 'textarea', 1),
  (109, 'mecanica', 'Mecanica/Texto do Criativo', 'textarea', 1),
  (109, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
  (109, 'gdrive', 'Url Google Drive', 'gdrive', 1),
  (109, 'Observacoes', 'Observações', 'text', 1);

# Sub Etapas dos Elementos
REPLACE INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
  (180, 109, 'Criar Conteudo', 6, 184, NULL, NULL, NULL, 1),
  (182, 109, 'Alterar Conteudo', 6, 184, NULL, NULL, NULL, 1),
  (184, 109, 'Criar Peça', 9, 188, 182, NULL, NULL, 1),
  (186, 109, 'Alterar Peça', 9, 188, 182, NULL, NULL, 1),
  (188, 109, 'Revisar Post Criado', 30, 189, 190, NULL, NULL, 1),
  (189, 109, 'Aprovar Post Com Cliente', 4, 191, 190, NULL, NULL, 1),
  (190, 109, 'Decisao 1, Reprovacao Condicional 6 / 9', 22, NULL, NULL, NULL, NULL, 1),
  (191, 109, 'Programar (agendar) post', 6, 192, NULL, NULL, NULL, 1),
  (192, 109, 'Decisao 2, Impulsionar Post', 22, NULL, NULL, NULL, NULL, 1),
  (193, 109, 'Impulsionar Post', 10, NULL, NULL, NULL, NULL, 1);


# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
  (15, 'Redes Sociais - V3', 'Producao e revisao de Imagens e posts para medias sociais', 1, '#425F9C', 'fa-address-card', 'POP_PROJETO_SOCIAL_MEDIA_v3', 0, 1);

# Dados Extra projeto
REPLACE INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
  (15, 'mes', 'Mês', 'date_monthyear', 1);

# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
  (15, 109, 1);
COMMIT;
