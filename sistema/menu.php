<nav class="menu">
    <div class="menu-topo">
        <span class="logo">Estoque Merchan</span>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
    </div>

    <ul id="menu-lista">
        <li><a href="../sistema/dashboard.php">Dashboard</a></li>

        <li class="dropdown">
            <a href="#">Produtos</a>
            <ul class="submenu">
                <li><a href="../produtos/produto_cadastro.php">Cadastrar</a></li>
                <li><a href="../produtos/produto_listar.php">Estoque</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#">Clientes</a>
            <ul class="submenu">
                <li><a href="../clientes/cliente_cadastro.php">Cadastrar</a></li>
                <li><a href="../clientes/cliente_listar.php">Listar</a></li>
                <li><a href="../clientes/clientes_importar.php">Importar Clientes</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#">Vendedores</a>
            <ul class="submenu">
                <li><a href="../vendedores/vendedor_cadastro.php">Cadastrar</a></li>
                <li><a href="../vendedores/vendedor_listar.php">Listar</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#">Motoristas</a>
            <ul class="submenu">
                <li><a href="../motoristas/motorista_cadastro.php">Cadastrar</a></li>
                <li><a href="../motoristas/motorista_listar.php">Listar</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#">Pedidos</a>
            <ul class="submenu">
                <li><a href="../pedidos/pedido_cadastro.php">Novo Pedido</a></li>
                <li><a href="../pedidos/pedido_listar.php">Listar</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#">Romaneios</a>
            <ul class="submenu">
                <li><a href="../relatorios/entrega_cliente.php?pedido=<?= $p['codPedido'] ?>"target="_blank">Entrega Cliente</a></li>
                <li><a href="../relatorios/romaneio.php?pedido=<?= $p['codPedido'] ?>"target="_blank">Romaneio</a></li>
            </ul>
        </li>

        <li class="sair"><a href="logout.php">Sair</a></li>
    </ul>
</nav>

<script>
function toggleMenu() {
    document.getElementById('menu-lista').classList.toggle('ativo');
}

document.querySelectorAll('.dropdown > a').forEach(item => {
    item.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            this.parentElement.classList.toggle('ativo');
        }
    });
});
</script>
