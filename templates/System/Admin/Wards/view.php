<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ward $ward
 */

    $this->assign('title', __($ward->name));

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
            <?= $this->Html->link('<i class="i-Pen-2"></i> ' . __('Edit Ward'),
                ['action' => 'edit', $ward->id],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <?= $this->Html->link('<i class="i-Add-User"></i> ' . __('New Ward'),
                ['action' => 'add'],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>

            <?= $this->Form->postLink('<i class="i-Close-Window"></i> ' . __('Delete Ward'),
                ['action' => 'delete', $ward->id],
                ['confirm' => __('Are you sure you want to delete {0}?', $ward->name), 'class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>
        <?php endif; ?>

        <?= $this->Html->link('<i class="i-Folder-Open-2r"></i> ' . __('List All Wards'),
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
                        <td><?= $ward->id ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <td><?= h($ward->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Area') ?></th>
                        <td><?= $ward->has('area') ? $this->Html->link($ward->area->name, ['controller' => 'Areas', 'action' => 'view', $ward->area->id]) : '' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <h3 class="w-80 float-left card-title m-0"><?= __('Voting Stations') ?></h3>
                <div class="ml-auto float-right">
                    <?= $this->Html->link(__('+ Voting Station'),
                        ['controller'=>'VotingStations', 'action' => 'add', $ward->id],
                        ['class' => 'btn btn-secondary', 'escape'=>false]) ?>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($ward->voting_stations)) : ?>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><?= __('ID') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Approved') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($ward->voting_stations as $votingStations) : ?>
                        <tr>
                            <td><?= h($votingStations->id) ?></td>
                            <td><?= h($votingStations->name) ?></td>
                            <td><?= ($votingStations->approved) ? 'Yes' : 'No' ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'VotingStations', 'action' => 'view', $votingStations->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'VotingStations', 'action' => 'edit', $votingStations->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'VotingStations', 'action' => 'delete', $votingStations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $votingStations->id)]) ?>
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
