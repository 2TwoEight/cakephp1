<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake','custom']) ?>
    <?= $this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js') ?>
    <?= $this->Html->script('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js') ?>
    <?= $this->Html->css('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css') ?>
 <?= $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>"><span>My</span>App</a>
        </div>
        <div class="top-nav-links">
                        <?= $this->Html->link(__('Home'), ['controller' => 'Pages', 'action' => 'display', 'home']) ?>
            <?= $this->Html->link(__('Sleep Records'), ['controller' => 'SleepRecords', 'action' => 'index']) ?>
            <?php if ($this->request->getAttribute('identity')): ?>
                <span><?= __('Hello, {0} {1}', h($this->request->getAttribute('identity')->prenom), h($this->request->getAttribute('identity')->nom)) ?></span>
                <?= $this->Form->postLink(__('Logout'), ['controller' => 'Users', 'action' => 'logout'], ['confirm' => __('Are you sure you want to logout?')]) ?>
            <?php else: ?>
                <?= $this->Html->link(__('Login'), ['controller' => 'Users', 'action' => 'login']) ?>
            <?php endif; ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="column column-20" id="menu">
                    <h4><?= __('Menu') ?></h4>
                    <ul id="sortable-menu">
                        <?php
                        // Load the 'Menus' table using TableRegistry
                        $menusTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Menus');

                        // Fetch the menus, ordered by the 'ordre' field in ascending order
                        $menus = $menusTable->find()->order(['ordre' => 'ASC'])->toArray();

                        // Loop through each menu item
                        foreach ($menus as $menu): ?>
                            <li class="ui-state-default" data-id="<?= $menu->id ?>">
                                <!-- Create a link to the menu item -->
                                <?= $this->Html->link($menu->intitule, $menu->lien) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>
                <div class="column column-80">
                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <script>
    $(function() {
        $("#sortable-menu").sortable({
            update: function(event, ui) {
                var order = $(this).sortable('toArray', { attribute: 'data-id' });
                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Menus', 'action' => 'reorder']) ?>',
                    method: 'POST',
                    data: { order: order },
                    headers: {
                        'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                    },
                    success: function(response) {
                        alert('Menu order updated successfully.');
                    },
                    error: function() {
                        alert('An error occurred while updating the menu order.');
                    }
                });
            }
        });
        $("#sortable-menu").disableSelection();
    });
    </script>
</body>
</html>
