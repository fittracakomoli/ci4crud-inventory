    <nav class="sidebar w-100 d-flex flex-column flex-shrink-0 position-relative z-2 vh-100 bg-white border-end">
        <div class="dropdown-center px-4 py-2">
            <button type="button" class="w-100 px-2 py-2 text-start border-0 bg-transparent shadow-none bg-accent-hover rounded d-flex gap-3 align-items-center" data-bs-toggle="dropdown">
                <div class="d-grid flex-grow-1 ls-tight text-sm">
                    <span class="text-truncate fs-3 fw-semibold">Inventory</span>
                    <span class="text-truncate fs-5 text-body-secondary mt-n1">Codeigniter 4</span>
                </div>
            </button>
        </div>
        <div class="px-4 py-2 flex-fill overflow-y-auto scrollbar">
            <div class="vstack gap-5">
                <div>
                    <ul class="navbar-nav navbar-vertical-nav gap-0.5 mx-lg-n2">
                        <li class="nav-item">
                            <a class="nav-link px-2 rounded-1 bg-accent-hover" href="/">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2 rounded-1 bg-accent-hover" href="#sidebarinventory" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarinventory">
                                <i class="bi bi-wallet"></i> Inventory
                            </a>
                            <div class="collapse" id="sidebarinventory">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="/inventory" class="nav-link px-2 rounded-1 bg-accent-hover active">
                                            Products
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/category" class="nav-link px-2 rounded-1 bg-accent-hover">
                                            Category
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2 rounded-1 bg-accent-hover" href="/transaction">
                                <i class="bi bi-pie-chart"></i> Transaction
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2 rounded-1 bg-accent-hover" href="#sidebarpartner" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarpartner">
                                <i class="bi bi-people"></i> Partner
                            </a>
                            <div class="collapse" id="sidebarpartner">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="/supplier" class="nav-link px-2 rounded-1 bg-accent-hover">
                                            Suppliers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/division" class="nav-link px-2 rounded-1 bg-accent-hover">
                                            Divisions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>