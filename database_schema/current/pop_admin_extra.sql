ALTER TABLE `back_AdmArea`
    ADD PRIMARY KEY (`area`);

ALTER TABLE `back_AdmAreaUsuario`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_back_AdmArea_has_back_AdmUsuario_back_AdmUsuario1_idx` (`usuarioNerdweb`),
    ADD KEY `fk_back_AdmArea_has_back_AdmUsuario_back_AdmArea1_idx` (`area`);

ALTER TABLE `back_AdmGrupo`
    ADD PRIMARY KEY (`grupo`);

ALTER TABLE `back_AdmGrupoUsuario`
    ADD PRIMARY KEY (`grupoUsuario`),
    ADD KEY `fk_admUser_has_admGrupo_admGrupo1_idx` (`grupo`),
    ADD KEY `fk_admUser_has_admGrupo_admUser1_idx` (`usuarioNerdweb`);

ALTER TABLE `back_AdmPermissao`
    ADD PRIMARY KEY (`permissao`);

ALTER TABLE `back_AdmPermissaoGrupo`
    ADD PRIMARY KEY (`permissaoGrupo`),
    ADD KEY `fk_admPermissao_has_admGrupo_admGrupo1_idx` (`grupo`),
    ADD KEY `fk_admPermissao_has_admGrupo_admPermissao_idx` (`permissao`);

ALTER TABLE `back_AdmUsuario`
    ADD PRIMARY KEY (`usuarioNerdweb`);

ALTER TABLE `back_Cliente`
    ADD PRIMARY KEY (`cliente`);

ALTER TABLE `back_Servico`
    ADD PRIMARY KEY (`servico`);

ALTER TABLE `back_ServicoCliente`
    ADD PRIMARY KEY (`servicoCliente`),
    ADD KEY `fk_back_Cliente_has_back_Servico_back_Servico1_idx` (`servico`),
    ADD KEY `fk_back_Cliente_has_back_Servico_back_Cliente1_idx` (`cliente`);

ALTER TABLE `pop_AdmAreaXelementoTipo`
    ADD UNIQUE KEY `elementoTipo` (`elementoTipo`),
    ADD KEY `area` (`area`);

ALTER TABLE `pop_Alerta`
    ADD PRIMARY KEY (`alerta`),
    ADD KEY `fk_projeto` (`projeto`),
    ADD KEY `fk_elemento` (`elemento`);

ALTER TABLE `pop_ColunasBase`
    ADD PRIMARY KEY (`colunaBase`);

ALTER TABLE `pop_ColunasBaseNomeTipo`
    ADD UNIQUE KEY `colunaBase_2` (`colunaBase`, `nome`),
    ADD KEY `colunaBase` (`colunaBase`);

ALTER TABLE `pop_ColunasBaseXelementoTipo`
    ADD UNIQUE KEY `elementoTipo_2` (`elementoTipo`, `colunasBase`),
    ADD KEY `elementoTipo` (`elementoTipo`),
    ADD KEY `elementoBase` (`colunasBase`);

ALTER TABLE `pop_Elemento`
    ADD PRIMARY KEY (`elemento`),
    ADD KEY `fk_pop_Elemento_pop_Projeto1_idx` (`projeto`),
    ADD KEY `fk_pop_Elemento_pop_ElementoStatus1_idx` (`elementoStatus`),
    ADD KEY `fk_pop_Elemento_pop_ElementoTipo1_idx` (`elementoTipo`);

ALTER TABLE `pop_ElementoAnterior`
    ADD UNIQUE KEY `projetoTipo` (`projetoTipo`, `elementoTipo`, `anterior`),
    ADD KEY `fk_pop_ElementoAnterior_pop_ProjetoTipo1_idx` (`projetoTipo`),
    ADD KEY `fk_pop_ElementoAnterior_pop_ElementoTipo1_idx` (`elementoTipo`),
    ADD KEY `fk_pop_ElementoAnterior_pop_ElementoTipo2_idx` (`anterior`);

ALTER TABLE `pop_ElementoCategoria`
    ADD PRIMARY KEY (`categoria`);

ALTER TABLE `pop_ElementoChat`
    ADD KEY `elemento` (`elemento`),
    ADD KEY `responsavel` (`responsavel`);

ALTER TABLE `pop_ElementoConteudo`
    ADD UNIQUE KEY `elemento` (`elemento`, `chave`),
    ADD KEY `fk_pop_ElementoConteudo_pop_Elemento1_idx` (`elemento`),
    ADD KEY `chave` (`chave`),
    ADD KEY `elemento_2` (`elemento`);

ALTER TABLE `pop_ElementoHistorico`
    ADD UNIQUE KEY `usuario_2` (`usuario`, `elemento`, `acao`, `data`),
    ADD KEY `usuario` (`usuario`),
    ADD KEY `elemento` (`elemento`),
    ADD KEY `acao` (`acao`);

ALTER TABLE `pop_ElementoHistoricoAcao`
    ADD PRIMARY KEY (`acao`);

ALTER TABLE `pop_ElementoPrimeiro`
    ADD UNIQUE KEY `projetoTipo` (`projetoTipo`, `elementoTipo`),
    ADD KEY `fk_pop_ElementoPrimeiro_pop_ProjetoTipo1_idx` (`projetoTipo`),
    ADD KEY `fk_pop_ElementoPrimeiro_pop_ElementoTipo1_idx` (`elementoTipo`);

ALTER TABLE `pop_ElementoProximo`
    ADD UNIQUE KEY `projetoTipo` (`projetoTipo`, `elementoTipo`, `proximo`),
    ADD KEY `fk_pop_ElementoProximo_pop_ProjetoTipo1_idx` (`projetoTipo`),
    ADD KEY `fk_pop_ElementoProximo_pop_ElementoTipo1_idx` (`elementoTipo`),
    ADD KEY `fk_pop_ElementoProximo_pop_ElementoTipo2_idx` (`proximo`);

ALTER TABLE `pop_ElementoStatus`
    ADD PRIMARY KEY (`elementoStatus`),
    ADD UNIQUE KEY `identifier_UNIQUE` (`identifier`);

ALTER TABLE `pop_ElementoTipo`
    ADD PRIMARY KEY (`elementoTipo`);

ALTER TABLE `pop_ElementoTipoNomeTipo`
    ADD UNIQUE KEY `elementoTipo` (`elementoTipo`, `nome`),
    ADD KEY `id` (`elementoTipo`);

ALTER TABLE `pop_ElementoTipoSubEtapa`
    ADD UNIQUE KEY `etapa` (`etapa`),
    ADD KEY `elementoTipo` (`elementoTipo`),
    ADD KEY `area` (`area`),
    ADD KEY `responsavel` (`responsavel`),
    ADD KEY `etapa_2` (`etapa`, `elementoTipo`),
    ADD KEY `proximo` (`proximo`),
    ADD KEY `anterior` (`anterior`);

ALTER TABLE `pop_Notificacao`
    ADD PRIMARY KEY (`notificacao`),
    ADD KEY `fk_usuario` (`usuario`),
    ADD KEY `fk_area` (`area`) USING BTREE,
    ADD KEY `fk_cliente` (`cliente`);

ALTER TABLE `pop_Prioridade`
    ADD PRIMARY KEY (`prioridade`);

ALTER TABLE `pop_Projeto`
    ADD PRIMARY KEY (`projeto`),
    ADD KEY `fk_pop_Projeto_pop_ProjetoTipo1_idx` (`projetoTipo`),
    ADD KEY `cliente` (`cliente`);

ALTER TABLE `pop_ProjetoChat`
    ADD KEY `elemento` (`projeto`),
    ADD KEY `responsavel` (`responsavel`),
    ADD KEY `data` (`data`),
    ADD KEY `fk_elemento` (`elemento`);

ALTER TABLE `pop_ProjetoConteudo`
    ADD UNIQUE KEY `projeto` (`projeto`, `chave`),
    ADD KEY `fk_pop_ProjetoConteudo_pop_Projeto_idx` (`projeto`);

ALTER TABLE `pop_ProjetoDadosTemporarios`
    ADD UNIQUE KEY `projeto` (`projeto`, `user`);

ALTER TABLE `pop_ProjetoTipo`
    ADD PRIMARY KEY (`projetoTipo`),
    ADD UNIQUE KEY `identifier_UNIQUE` (`identifier`);

ALTER TABLE `pop_ProjetoTipoNomeTipo`
    ADD UNIQUE KEY `projetoTipo` (`projetoTipo`, `nome`),
    ADD KEY `id` (`projetoTipo`);

ALTER TABLE `sessions_v2`
    ADD PRIMARY KEY (`id`);


ALTER TABLE `back_AdmArea`
    MODIFY `area` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_AdmAreaUsuario`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_AdmGrupo`
    MODIFY `grupo` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_AdmGrupoUsuario`
    MODIFY `grupoUsuario` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_AdmPermissao`
    MODIFY `permissao` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_AdmPermissaoGrupo`
    MODIFY `permissaoGrupo` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_AdmUsuario`
    MODIFY `usuarioNerdweb` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_Cliente`
    MODIFY `cliente` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_Servico`
    MODIFY `servico` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `back_ServicoCliente`
    MODIFY `servicoCliente` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_Alerta`
    MODIFY `alerta` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ColunasBase`
    MODIFY `colunaBase` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_Elemento`
    MODIFY `elemento` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ElementoCategoria`
    MODIFY `categoria` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ElementoHistoricoAcao`
    MODIFY `acao` tinyint(4) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ElementoStatus`
    MODIFY `elementoStatus` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ElementoTipo`
    MODIFY `elementoTipo` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ElementoTipoSubEtapa`
    MODIFY `etapa` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_Notificacao`
    MODIFY `notificacao` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_Prioridade`
    MODIFY `prioridade` tinyint(4) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_Projeto`
    MODIFY `projeto` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pop_ProjetoTipo`
    MODIFY `projetoTipo` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `back_AdmAreaUsuario`
    ADD CONSTRAINT `back_AdmAreaUsuario_ibfk_1` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
    ADD CONSTRAINT `back_AdmAreaUsuario_ibfk_2` FOREIGN KEY (`usuarioNerdweb`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`);

ALTER TABLE `back_AdmGrupoUsuario`
    ADD CONSTRAINT `back_AdmGrupoUsuario_ibfk_1` FOREIGN KEY (`usuarioNerdweb`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`),
    ADD CONSTRAINT `back_AdmGrupoUsuario_ibfk_2` FOREIGN KEY (`grupo`) REFERENCES `back_AdmGrupo` (`grupo`);

ALTER TABLE `back_AdmPermissaoGrupo`
    ADD CONSTRAINT `back_AdmPermissaoGrupo_ibfk_1` FOREIGN KEY (`permissao`) REFERENCES `back_AdmPermissao` (`permissao`),
    ADD CONSTRAINT `back_AdmPermissaoGrupo_ibfk_2` FOREIGN KEY (`grupo`) REFERENCES `back_AdmGrupo` (`grupo`);

ALTER TABLE `back_ServicoCliente`
    ADD CONSTRAINT `back_ServicoCliente_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `back_Cliente` (`cliente`),
    ADD CONSTRAINT `back_ServicoCliente_ibfk_2` FOREIGN KEY (`servico`) REFERENCES `back_Servico` (`servico`);

ALTER TABLE `pop_AdmAreaXelementoTipo`
    ADD CONSTRAINT `pop_AdmAreaXelementoTipo_ibfk_1` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
    ADD CONSTRAINT `pop_AdmAreaXelementoTipo_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

ALTER TABLE `pop_ColunasBaseNomeTipo`
    ADD CONSTRAINT `pop_ColunasBaseNomeTipo_ibfk_1` FOREIGN KEY (`colunaBase`) REFERENCES `pop_ColunasBase` (`colunaBase`);

ALTER TABLE `pop_ColunasBaseXelementoTipo`
    ADD CONSTRAINT `pop_ColunasBaseXelementoTipo_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
    ADD CONSTRAINT `pop_ColunasBaseXelementoTipo_ibfk_2` FOREIGN KEY (`colunasBase`) REFERENCES `pop_ColunasBase` (`colunaBase`);

ALTER TABLE `pop_Elemento`
    ADD CONSTRAINT `pop_Elemento_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
    ADD CONSTRAINT `pop_Elemento_ibfk_2` FOREIGN KEY (`elementoStatus`) REFERENCES `pop_ElementoStatus` (`elementoStatus`),
    ADD CONSTRAINT `pop_Elemento_ibfk_3` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`);

ALTER TABLE `pop_ElementoAnterior`
    ADD CONSTRAINT `pop_ElementoAnterior_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
    ADD CONSTRAINT `pop_ElementoAnterior_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
    ADD CONSTRAINT `pop_ElementoAnterior_ibfk_3` FOREIGN KEY (`anterior`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

ALTER TABLE `pop_ElementoChat`
    ADD CONSTRAINT `pop_ElementoChat_ibfk_1` FOREIGN KEY (`responsavel`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`),
    ADD CONSTRAINT `pop_ElementoChat_ibfk_2` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`);

ALTER TABLE `pop_ElementoConteudo`
    ADD CONSTRAINT `pop_ElementoConteudo_ibfk_1` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`);

ALTER TABLE `pop_ElementoHistorico`
    ADD CONSTRAINT `pop_ElementoHistorico_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`),
    ADD CONSTRAINT `pop_ElementoHistorico_ibfk_2` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`),
    ADD CONSTRAINT `pop_ElementoHistorico_ibfk_3` FOREIGN KEY (`acao`) REFERENCES `pop_ElementoHistoricoAcao` (`acao`);

ALTER TABLE `pop_ElementoPrimeiro`
    ADD CONSTRAINT `pop_ElementoPrimeiro_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
    ADD CONSTRAINT `pop_ElementoPrimeiro_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

ALTER TABLE `pop_ElementoProximo`
    ADD CONSTRAINT `pop_ElementoProximo_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
    ADD CONSTRAINT `pop_ElementoProximo_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
    ADD CONSTRAINT `pop_ElementoProximo_ibfk_3` FOREIGN KEY (`proximo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

ALTER TABLE `pop_ElementoTipoNomeTipo`
    ADD CONSTRAINT `pop_ElementoTipoNomeTipo_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

ALTER TABLE `pop_ElementoTipoSubEtapa`
    ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
    ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_2` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
    ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_3` FOREIGN KEY (`responsavel`) REFERENCES `back_AdmAreaUsuario` (`id`),
    ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_4` FOREIGN KEY (`proximo`) REFERENCES `pop_ElementoTipoSubEtapa` (`etapa`),
    ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_5` FOREIGN KEY (`anterior`) REFERENCES `pop_ElementoTipoSubEtapa` (`etapa`);

ALTER TABLE `pop_Notificacao`
    ADD CONSTRAINT `fk_area` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
    ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`cliente`) REFERENCES `back_Cliente` (`cliente`),
    ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`);

ALTER TABLE `pop_Projeto`
    ADD CONSTRAINT `pop_Projeto_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
    ADD CONSTRAINT `pop_Projeto_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `back_Cliente` (`cliente`);

ALTER TABLE `pop_ProjetoChat`
    ADD CONSTRAINT `fk_elemento` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`),
    ADD CONSTRAINT `pop_ProjetoChat_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`),
    ADD CONSTRAINT `pop_ProjetoChat_ibfk_2` FOREIGN KEY (`responsavel`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`);

ALTER TABLE `pop_ProjetoConteudo`
    ADD CONSTRAINT `pop_ProjetoConteudo_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`);

ALTER TABLE `pop_ProjetoDadosTemporarios`
    ADD CONSTRAINT `pop_ProjetoDadosTemporarios_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`);

ALTER TABLE `pop_ProjetoTipoNomeTipo`
    ADD CONSTRAINT `pop_ProjetoTipoNomeTipo_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`);
