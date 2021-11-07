<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Province $province
 */

    $this->assign('title', __($province->name));
?>

<?php $this->start('page_options'); ?>
<div class="dropdown dropleft ml-auto">
    <button type="button" class="btn bg-transparent _r_btn border-0 " id="asaPageOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
    </button>
    <div class="dropdown-menu" aria-labelledby="asaPageOptions">
        <?php if (in_array($this->Identity->get('role_id'), [1, 2]) ): ?>
            <?= $this->Html->link('<i class="i-Pen-2"></i> ' . __('Edit Province'),
                ['action' => 'edit', $province->id],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <?= $this->Html->link('<i class="i-Add-User"></i> ' . __('New Province'),
                ['action' => 'add'],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>
        <?php endif; ?>

        <?= $this->Html->link('<i class="i-Folder-Open-2r"></i> ' . __('List All Provinces'),
            ['action' => 'index'],
            ['class' => 'dropdown-item', 'escape'=>false]) ?>
    </div>
</div>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th><?= __('ID') ?></th>
                        <td><?= $province->id ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <td><?= h($province->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Code') ?></th>
                        <td><?= h($province->code) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h4><?= __('Municipalities') ?></h4>
                <?php if (!empty($province->municipalities)) : ?>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><?= __('ID') ?></th>
                            <th><?= __('Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($province->municipalities as $municipalities) : ?>
                        <tr>
                            <td><?= h($municipalities->id) ?></td>
                            <td><?= h($municipalities->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Municipalities', 'action' => 'view', $municipalities->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Municipalities', 'action' => 'edit', $municipalities->id]) ?>
                                <?= (in_array($this->Identity->get('role_id'), [1, 2])) ?
                                        $this->Form->postLink(__('Delete'), ['controller' => 'Municipalities', 'action' => 'delete', $municipalities->id], ['confirm' => __('Are you sure you want to delete {0}?', $municipalities->name)]) :
                                        ''
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
