<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VotingStation $votingStation
 */

    $this->assign('title', __($votingStation->name));
?>

<?php $this->start('page_options'); ?>
<div class="dropdown dropleft ml-auto">
    <button type="button" class="btn bg-transparent _r_btn border-0 " id="asaPageOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
    </button>
    <div class="dropdown-menu" aria-labelledby="asaPageOptions">
        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3]) ): ?>
            <?= $this->Html->link('<i class="i-Pen-2"></i> ' . __('Edit Voting Station'),
                ['action' => 'edit', $votingStation->id],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>

            <div class="dropdown-divider"></div>
        <?php endif; ?>

        <?= $this->Html->link('<i class="i-Folder-Open-2r"></i> ' . __('List All Voting Stations'),
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
                        <td><?= $votingStation->id ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <td><?= h($votingStation->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Ward') ?></th>
                        <td><?= $votingStation->has('ward') ? $this->Html->link('Ward '. $votingStation->ward->name, ['controller' => 'Wards', 'action' => 'view', $votingStation->ward->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Approved') ?></th>
                        <td><?= $votingStation->approved ? __('Yes') : __('No'); ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Members in this Voting Station') ?></th>
                        <td><?= (!empty($votingStation->users)) ? $this->Html->link(count($votingStation->users),
                                ['controller'=>'users', 'action' => 'index', '?' => ['voting_station_id' => $votingStation->id]],
                                ['class' => 'btn btn-sm btn-default']) : 0; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
