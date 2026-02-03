<nav class="navbar navbar-expand-lg bg-primary navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">CI4 Inventory</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php if (uri_string() == '') echo 'active'; ?>" href="/">Home</a>
                    </li>
                    <?php if (auth()->user()->can('view.product', 'view.category')) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php if (in_array(uri_string(), ['inventory', 'category'])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Inventory
                        </a>

                        <ul class="dropdown-menu">
                            <?php if (auth()->user()->can('view.product')) : ?>
                            <li>
                                <a class="dropdown-item <?php if (uri_string() == 'inventory') echo 'active'; ?>" href="/inventory">Product</a>
                            </li>
                            <?php endif; ?>
                            <?php if (auth()->user()->can('view.category')) : ?>
                            <li>
                                <a class="dropdown-item <?php if (uri_string() == 'category') echo 'active'; ?>" href="/category">Category</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <?php if (auth()->user()->can('view.transaction')) : ?>
                        <a class="nav-link <?php if (uri_string() == 'transaction') echo 'active'; ?>" href="/transaction">Transaction</a>
                        <?php endif; ?>
                    </li>
                    <?php if (auth()->user()->can('view.supplier', 'view.division')) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php if (in_array(uri_string(), ['supplier', 'division'])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Partner
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (auth()->user()->can('view.supplier')) : ?>
                            <li>
                                <a class="dropdown-item <?php if (uri_string() == 'supplier') echo 'active'; ?>" href="/supplier">Supplier</a>
                            </li>
                            <?php endif; ?>
                            <?php if (auth()->user()->can('view.division')) : ?>
                            <li>
                                <a class="dropdown-item <?php if (uri_string() == 'division') echo 'active'; ?>" href="/division">Division</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <span class="navbar-text dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php $user = auth()->user() ?>
                            <?= $user->username; ?>
                        </span>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= site_url('logout') ?>">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>