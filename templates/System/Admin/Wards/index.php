<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ward[]|\Cake\Collection\CollectionInterface $wards
 */

$this->assign('title', __('Wards'));

?>
<?php $this->start('page_options'); ?>
<?= $this->Html->link(__('New Ward'),
    ['action' => 'add'],
    ['class' => 'btn btn-secondary ml-auto']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            <?= $this->element('locations_filter') ?>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('area_id', 'Region') ?></th>
                            <th class="actions text-right" style="width:200px;">&vellip;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($wards as $ward): ?>
                            <tr>
                                <td><?= $ward->id ?></td>
                                <td><?= $this->Html->link('Ward ' . __($ward->name), ['action' => 'view', $ward->id]) ?></td>
                                <td>
                                    <?php if ($ward->has('Areas')): ?>
                                        <?= $this->Html->link($ward->Areas['name'],
                                            ['controller' => 'Areas', 'action' => 'view', $ward->Areas['id']]) ?>
                                        <br>
                                        <small class="text-small text-gray-50">
                                            <?= $ward->Provinces['code'] ?>,
                                            <?= $ward->Municipality['name'] ?>
                                        </small>
                                    <?php endif; ?>

                                </td>
                                <td class="actions text-right">
                                    <?= $this->Html->link('<i class="i-Folder-Open-2 text-16"></i>', ['action'=>'view', $ward->id], ['class'=>'btn btn-sm text-success mr-1', 'escape'=>false]) ?>
                                    <?= $this->Html->link('<i class="i-Pen-2 text-16"></i>', ['action'=>'edit', $ward->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>
                                    <?= $this->Form->postLink('<i class="i-Close-Window text-16"></i>', ['action'=>'delete', $ward->id], ['class'=>'btn btn-sm text-mute', 'escape'=>false, 'confirm' => __('Are you sure you want to delete # {0}?', $ward->id)]) ?>
                                    <?= $this->Html->link('<i class="i-Clock-4 text-16"></i>', ['controller'=>'votingStations', 'action'=>'add', $ward->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!--/.card-body -->
            <div class="card-footer">
                <div class="paginator d-flex align-items-center">
                    <div class="w-75">
                        <nav aria-label="<?= __('Wards') ?> navigation">
                            <ul class="pagination">
                                <?= $this->Paginator->first('«') ?>
                                <?= $this->Paginator->prev('<') ?>
                                <?= $this->Paginator->numbers() ?>
                                <?= $this->Paginator->next(' >') ?>
                                <?= $this->Paginator->last('»') ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="w-25">
                        <p><?= $this->Paginator->counter(__('Displaying {{start}} - {{end}} of {{count}} | Page {{page}} of {{pages}}')) ?></p>
                    </div>
                </div>
            </div><!--/.card-footer -->
        </div>
    </div>
</div>


