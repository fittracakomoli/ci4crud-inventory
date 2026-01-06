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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if (in_array(uri_string(), ['inventory', 'category'])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Inventory
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item <?php if (uri_string() == 'inventory') echo 'active'; ?>" href="/inventory">Product</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php if (uri_string() == 'category') echo 'active'; ?>" href="/category">Category</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (uri_string() == 'transaction') echo 'active'; ?>" href="/transaction">Transaction</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if (in_array(uri_string(), ['supplier', 'division'])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Partner
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item <?php if (uri_string() == 'supplier') echo 'active'; ?>" href="/supplier">Supplier</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php if (uri_string() == 'division') echo 'active'; ?>" href="/division">Division</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
            </ul>
        </div>
    </div>
</nav>