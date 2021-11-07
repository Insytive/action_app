<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Area $area
 */

    $this->assign('title', __($area->name));
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
            <?= $this->Html->link('<i class="i-Pen-2"></i> ' . __('Edit Region'),
                ['action' => 'edit', $area->id],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <?= $this->Html->link('<i class="i-Add-User"></i> ' . __('New Region'),
                ['action' => 'add'],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>

            <?= $this->Form->postLink('<i class="i-Close-Window"></i> ' . __('Delete Ward'),
                ['action' => 'delete', $area->id],
                ['confirm' => __('Are you sure you want to delete {0}?', $area->name), 'class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>
        <?php endif; ?>

        <?= $this->Html->link('<i class="i-Folder-Open-2r"></i> ' . __('List All Regions'),
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
                        <td><?= $area->id ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <td><?= h($area->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Municipality') ?></th>
                        <td><?= $area->has('municipality') ? $this->Html->link($area->municipality->name, ['controller' => 'Municipalities', 'action' => 'view', $area->municipality->id]) : '' ?></td>
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
                <h4><?= __('Wards') ?></h4>
                <?php if (!empty($area->wards)) : ?>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('ID') ?></th>
                                <th><?= __('Name') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($area->wards as $wards) : ?>
                                <tr>
                                    <td><?= h($wards->id) ?></td>
                                    <td><?= h($wards->name) ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link(__('View'), ['controller' => 'Wards', 'action' => 'view', $wards->id]) ?>
                                        <?= $this->Html->link(__('Edit'), ['controller' => 'Wards', 'action' => 'edit', $wards->id]) ?>
                                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Wards', 'action' => 'delete', $wards->id], ['confirm' => __('Are you sure you want to delete # {0}?', $wards->id)]) ?>
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


<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h4><?= __('Branches') ?></h4>
                <?php if (!empty($area->branches)) : ?>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><?= __('ID') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Phone') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Address') ?></th>
                            <th><?= __('Building') ?></th>
                            <th><?= __('City') ?></th>
                            <th><?= __('Post Code') ?></th>
                            <th><?= __('Created At') ?></th>
                            <th><?= __('Updated At') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($area->branches as $branches) : ?>
                        <tr>
                            <td><?= h($branches->id) ?></td>
                            <td><?= h($branches->name) ?></td>
                            <td><?= h($branches->phone) ?></td>
                            <td><?= h($branches->email) ?></td>
                            <td><?= h($branches->address) ?></td>
                            <td><?= h($branches->building) ?></td>
                            <td><?= h($branches->city) ?></td>
                            <td><?= h($branches->post_code) ?></td>
                            <td><?= h($branches->created_at) ?></td>
                            <td><?= h($branches->updated_at) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Branches', 'action' => 'view', $branches->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Branches', 'action' => 'edit', $branches->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Branches', 'action' => 'delete', $branches->id], ['confirm' => __('Are you sure you want to delete # {0}?', $branches->id)]) ?>
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
