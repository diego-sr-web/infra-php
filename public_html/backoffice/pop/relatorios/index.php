<?php
require_once __DIR__ . "/../../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
require_once __DIR__ . '/../../includes/is_logged.php';

$listaUsuarios = $database->ngSelectPrepared('back_AdmUsuario', ["ativo" => 1], "", "nome");
?>
<h2>Lista de tarefas por usuario</h2>
<form action="usuario.php" method="get">
    <select name="user_id">
        <option value="0">Selecione um usuário</option>
        <?php foreach ($listaUsuarios as $usuario) { ?>
            <option value="<?php echo $usuario['usuarioNerdweb']; ?>"><?php echo $usuario['nome']; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Ver Tarefas</button>
</form>
<br>
<?php
$listaClientes = $database->ngSelectPrepared("back_Cliente", ["ativo" => 1], "", "nomeFantasia")
?>
<h2>Lista de tarefas por cliente</h2>
<form action="clientes.php" method="get">
    <select name="client_id">
        <option value="0">Selecione um Cliente</option>
        <?php foreach ($listaClientes as $cliente) { ?>
            <option value="<?php echo $cliente['cliente']; ?>"><?php echo $cliente['nomeFantasia']; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Ver Tarefas</button>
</form>

<h2>Relatorio de Pedidos por cliente</h2>
<form action="csv_clientes.php" method="get">
    <select name="client_id">
        <option value="0">Selecione um Cliente</option>
        <?php foreach ($listaClientes as $cliente) { ?>
            <option value="<?php echo $cliente['cliente']; ?>"><?php echo $cliente['nomeFantasia']; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Gera CSV</button>
</form>
<?php
$listaAno = ["2016", "2017", "2018", "2019"];
$listaMes = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
$listaDia = [
    "01", "02", "03", "04", "05", "06", "07", "08", "09", "10",
    "11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
    "21", "22", "23", "24", "25", "26", "27", "28", "29", "30",
    "31"
];
?>
<br>
<h2>Lista de tarefas por Dia</h2>
<form action="diario.php" method="get">
    <select name="ano">
        <option value="0">Selecione um ANO</option>
        <?php foreach ($listaAno as $ano) { ?>
            <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
        <?php } ?>
    </select>
    <select name="mes">
        <option value="0">Selecione um MES</option>
        <?php foreach ($listaMes as $mes) { ?>
            <option value="<?php echo $mes; ?>"><?php echo $mes; ?></option>
        <?php } ?>
    </select>
    <select name="dia">
        <option value="0">Selecione um DIA</option>
        <?php foreach ($listaDia as $dia) { ?>
            <option value="<?php echo $dia; ?>"><?php echo $dia; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Ver Tarefas</button>
</form>
<br>
<h2>Lista de tarefas por Semana</h2>
<form action="semanal.php" method="get">
    <select name="ano">
        <option value="0">Selecione um ANO</option>
        <?php foreach ($listaAno as $ano) { ?>
            <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
        <?php } ?>
    </select>
    <select name="mes">
        <option value="0">Selecione um MES</option>
        <?php foreach ($listaMes as $mes) { ?>
            <option value="<?php echo $mes; ?>"><?php echo $mes; ?></option>
        <?php } ?>
    </select>
    <select name="dia">
        <option value="0">Selecione um DIA</option>
        <?php foreach ($listaDia as $dia) { ?>
            <option value="<?php echo $dia; ?>"><?php echo $dia; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Ver Tarefas</button>
</form>
<br>
<h2>Lista de tarefas por Mes</h2>
<form action="mensal.php" method="get">
    <select name="ano">
        <option value="0">Selecione um ANO</option>
        <?php foreach ($listaAno as $ano) { ?>
            <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
        <?php } ?>
    </select>
    <select name="mes">
        <option value="0">Selecione um MES</option>
        <?php foreach ($listaMes as $mes) { ?>
            <option value="<?php echo $mes; ?>"><?php echo $mes; ?></option>
        <?php } ?>
    </select>

    <button type="submit">Ver Tarefas</button>
</form>
