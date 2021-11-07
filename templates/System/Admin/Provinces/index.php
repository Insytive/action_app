<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Province[]|\Cake\Collection\CollectionInterface $provinces
 */

$this->assign('title', __('Provinces'));
?>
<?php $this->start('page_options'); ?>
<?= $this->Html->link(__('New Province'),
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
                                <th><?= $this->Paginator->sort('code') ?></th>
                                <th><?= $this->Paginator->sort('name') ?></th>
                                <th class="actions text-right" style="width:130px;">&vellip;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($provinces as $province): ?>
                            <tr>
                                <td><?= $province->code ?></td>
                                <td><?= $this->Html->link(h($province->name), ['action' => 'view', $province->id]) ?></td>
                                <td class="actions text-right">
                                    <?= $this->Html->link('<i class="nav-icon i-Folder-Open-2 font-weight-bold"></i>', ['action' => 'view', $province->id], ['class'=>'btn btn-sm text-success mr-1', 'escape'=>false]) ?>
                                    <?= $this->Html->link('<i class="nav-icon i-Pen-2 font-weight-bold"></i>', ['action' => 'edit', $province->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>
                                    <?php //$this->Form->postLink('<i class="nav-icon i-Close-Window font-weight-bold"></i>', ['action' => 'delete', $province->id], ['class'=>'btn btn-sm text-mute', 'escape'=>false, 'confirm' => __('Are you sure you want to delete # {0}?', $province->id)]) ?>
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
                        <nav aria-label="<?= __('Provinces') ?> navigation">
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


