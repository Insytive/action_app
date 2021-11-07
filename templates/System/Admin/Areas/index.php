<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Area[]|\Cake\Collection\CollectionInterface $areas
 */

$this->assign('title', __('Areas'));
?>

<?php $this->start('page_options'); ?>
<?= $this->Html->link(__('New Sub-region'),
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
                            <th><?= $this->Paginator->sort('id') ?></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('municipality_id') ?></th>
                            <th class="actions text-right" style="width:130px;">&vellip;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($areas as $area): ?>
                            <tr>
                                <td><?= $this->Number->format($area->id) ?></td>
                                <td><?= $this->Html->link(h($area->name), ['action' => 'view', $area->id]) ?></td>
                                <td><?= $area->has('municipality') ? $this->Html->link($area->municipality->name, ['controller' => 'Municipalities', 'action' => 'view', $area->municipality->id]) : '' ?></td>
                                <td class="actions text-right">
                                    <?= $this->Html->link('<i class="nav-icon i-Folder-Open-2 font-weight-bold"></i>', ['action' => 'view', $area->id], ['class'=>'btn btn-sm text-success mr-1', 'escape'=>false]) ?>
                                    <?= $this->Html->link('<i class="nav-icon i-Pen-2 font-weight-bold"></i>', ['action' => 'edit', $area->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>
                                    <?= $this->Form->postLink('<i class="nav-icon i-Close-Window font-weight-bold"></i>', ['action' => 'delete', $area->id], ['class'=>'btn btn-sm text-mute', 'escape'=>false, 'confirm' => __('Are you sure you want to delete # {0}?', $area->id)]) ?>
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
                        <nav aria-label="<?= __('Areas') ?> navigation">
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


