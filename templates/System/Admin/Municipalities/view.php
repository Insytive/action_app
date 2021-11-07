<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Municipality $municipality
 */

    $this->assign('title', __($municipality->name));

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
            <?= $this->Html->link('<i class="i-Pen-2"></i> ' . __('Edit Municipality'),
                ['action' => 'edit', $municipality->id],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <?= $this->Html->link('<i class="i-Add-User"></i> ' . __('New Municipality'),
                ['action' => 'add'],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>

            <?= $this->Form->postLink('<i class="i-Close-Window"></i> ' . __('Delete Ward'),
                ['action' => 'delete', $municipality->id],
                ['confirm' => __('Are you sure you want to delete {0}?', $municipality->name), 'class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>
        <?php endif; ?>

        <?= $this->Html->link('<i class="i-Folder-Open-2r"></i> ' . __('List All Municipalities'),
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
                        <td><?= $municipality->id ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <td><?= h($municipality->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Province') ?></th>
                        <td><?= $municipality->has('province') ? $this->Html->link($municipality->province->name, ['controller' => 'Provinces', 'action' => 'view', $municipality->province->id]) : '' ?></td>
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
                <h4><?= __('Areas') ?></h4>
                <?php if (!empty($municipality->areas)) : ?>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><?= __('ID') ?></th>
                            <th><?= __('Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($municipality->areas as $areas) : ?>
                        <tr>
                            <td><?= h($areas->id) ?></td>
                            <td><?= h($areas->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Areas', 'action' => 'view', $areas->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Areas', 'action' => 'edit', $areas->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Areas', 'action' => 'delete', $areas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $areas->id)]) ?>
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
