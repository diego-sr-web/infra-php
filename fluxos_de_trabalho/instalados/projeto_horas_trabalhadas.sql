#Projeto horas trabalhadas ( vai ser igual pedido, um projeto pra pendurar varias tarefas )

# Tipo de elemento
REPLACE INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(111, 'Descricao de Horas Trabalhadas', 0, 1);

# Criando o link entre os tipo de elemento base
REPLACE INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(111, 1, 1);


# Inserindo a Area Base do tipo
REPLACE INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(1, 111, 1);

# Campos Extras
REPLACE INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(111, 'titulo', 'Titulo', 'text', 1),
(111, 'arquivos', 'Arquivos', 'file', 1),
(111, 'cliente', 'Nome Do Cliente', 'text', 1),
(111, 'descricao', 'Descricao', 'editor', 1),
(111, 'area', 'Area', 'area', 1),
(111, 'tempo', 'Tempo Trabalhado', 'int', 1),
(111, 'Observacoes', 'Observações', 'textarea', 1);


# Comeco da parte de projeto

REPLACE INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(20, 'Apontamento De Horas', 'Projeto de uso interno para apontamento de horas trabalhada',
 1, '#CD7CB1', 'fa-picture-o', 'POP_PROJETO_HORAS', 1, 1);


# Primeiros elementos criados no projeto
REPLACE INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(20, 111, 1);
