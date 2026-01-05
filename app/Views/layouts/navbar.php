<nav class="navbar navbar-expand-lg bg-primary navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">CI4 Inventory</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if (uri_string() == '') echo 'active'; ?>" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (uri_string() == 'inventory') echo 'active'; ?>" href="/inventory">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (uri_string() == 'category') echo 'active'; ?>" href="/category">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (uri_string() == 'transaction') echo 'active'; ?>" href="/transaction">Transaction</a>
                </li>
                <li class="nav-item">
            </ul>
        </div>
    </div>
</nav>