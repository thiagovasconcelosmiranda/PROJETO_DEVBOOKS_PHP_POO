<aside class="mt-10">
            <nav>
                <a href="<?=$base;?>">
                    <div class="menu-item <?=($activeMenu == 'home'? 'active':'' )?>">
                        <div class="menu-item-icon">
                            <img src="<?=$base;?>/assets/images/home-run.png" width="16" height="16" />
                        </div>
                        <div class="menu-item-text">
                            Home
                        </div>
                    </div>
                </a>
                <a href="<?=$base;?>/perfil.php">
                    <div class="menu-item <?=($activeMenu == 'perfil'? 'active':'' )?>">
                        <div class="menu-item-icon">
                            <img src="<?=$base;?>/assets/images/user.png" width="16" height="16" />
                        </div>
                        <div class="menu-item-text">
                            Meu Perfil
                        </div>
                    </div>
                </a>
                <a href="<?=$base;?>/amigos.php">
                    <div class="menu-item <?=($activeMenu == 'amigos'? 'active':'' )?>">
                        <div class="menu-item-icon">
                            <img src="<?=$base;?>/assets/images/friends.png" width="16" height="16" />
                        </div>
                        <div class="menu-item-text">
                            Amigos
                        </div>
                        <div class="menu-item-badge">
                            33
                        </div>
                    </div>
                </a>
                <a href="<?=$base;?>/fotos.php">
                    <div class="menu-item <?=($activeMenu == 'fotos'? 'active':'' )?>">
                        <div class="menu-item-icon">
                            <img src="assets/images/photo.png" width="16" height="16" />
                        </div>
                        <div class="menu-item-text">
                            Fotos
                        </div>
                    </div>
                </a>
                <div class="menu-splitter"></div>
                <a href="<?=$base;?>/configuracoes.php">
                    <div class="menu-item <?=($activeMenu == 'configuracao'? 'active':'' )?>">
                        <div class="menu-item-icon">
                            <img src="<?=$base;?>/assets/images/settings.png" width="16" height="16" />
                        </div>
                        <div class="menu-item-text">
                            Configurações
                        </div>
                    </div>
                </a>
                <a href="<?=$base;?>/logaut.php">
                    <div class="menu-item">
                        <div class="menu-item-icon">
                            <img src="<?=$base;?>/assets/images/power.png" width="16" height="16" />
                        </div>
                        <div class="menu-item-text">
                            Sair
                        </div>
                    </div>
                </a>
            </nav>
        </aside>