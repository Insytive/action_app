<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VotingStation[]|\Cake\Collection\CollectionInterface $votingStations
 */

$this->assign('title', __('Voting Stations'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('ward_id') ?></th>
                            <th><?= $this->Paginator->sort('approved') ?></th>
                            <th class="actions text-right" style="width:130px;">&vellip;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($votingStations as $votingStation): ?>
                            <tr>
                                <td><?= $votingStation->id ?></td>
                                <td><?= $this->Html->link(h($votingStation->name), ['action' => 'view', $votingStation->id]) ?></td>
                                <td>
                                    <?php if ($votingStation->has('Ward')): ?>
                                        <span class="text-muted">Ward</span> <?= $this->Html->link(
                                            $votingStation->Ward['name'],
                                            [
                                                'controller' => 'Wards',
                                                'action' => 'view', $votingStation->Ward['id']
                                            ]) ?>
                                        <br>
                                        <small class="text-small text-gray-50">
                                            <?= $votingStation->Provinces['code'] ?>,
                                            <?= $votingStation->Municipality['name'] ?>,
                                            <?= $votingStation->Areas['name'] ?>
                                        </small>
                                    <?php endif; ?>
                                    </td>
                                <td><?= $votingStation->approved ? 'Y' : 'N' ?></td>
                                <td class="actions text-right">
                                    <?= $this->Html->link('<i class="nav-icon i-Folder-Open-2 font-weight-bold"></i>', ['action' => 'view', $votingStation->id], ['class'=>'btn btn-sm text-success mr-1', 'escape'=>false]) ?>
                                    <?= $this->Html->link('<i class="nav-icon i-Pen-2 font-weight-bold"></i>', ['action' => 'edit', $votingStation->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>

                                    <?php if (in_array($this->Identity->get('role_id'), [1, 2])): ?>
                                    <?= $this->Form->postLink('<i class="nav-icon i-Close-Window font-weight-bold"></i>', ['action' => 'delete', $votingStation->id], ['class'=>'btn btn-sm text-mute', 'escape'=>false, 'confirm' => __('Are you sure you want to delete # {0}?', $votingStation->id)]) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!--/.card-body -->
            <div class="card-footer">
                <div class="paginator d-flex align-items-center">
                    <div class="w-60">
                        <nav aria-label="<?= __('Voting Stations') ?> navigation">
                            <ul class="pagination">
                                <?= $this->Paginator->first('«') ?>
                                <?= $this->Paginator->prev('<') ?>
                                <?= $this->Paginator->numbers() ?>
                                <?= $this->Paginator->next(' >') ?>
                                <?= $this->Paginator->last('»') ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="w-40">
                        <p><?= $this->Paginator->counter(__('Displaying {{start}} - {{end}} of {{count}} | Page {{page}} of {{pages}}')) ?></p>
                    </div>
                </div>
            </div><!--/.card-footer -->
        </div>
    </div>
</div>


