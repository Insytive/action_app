<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting[]|\Cake\Collection\CollectionInterface $settings
 */

$this->assign('title', __('Settings'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-75 float-left card-title m-0"><?= __('Settings') ?></h3>
                <?= $this->Html->link(__('New Setting'),
                    ['action' => 'add'],
                    ['class' => 'btn btn-primary float-right']) ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th><?= $this->Paginator->sort('id') ?></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('value') ?></th>
                            <th><?= $this->Paginator->sort('created') ?></th>
                            <th><?= $this->Paginator->sort('updated') ?></th>
                            <th class="actions text-right" style="width:130px;">&vellip;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($settings as $setting): ?>
                            <tr>
                                <td><?= $this->Number->format($setting->id) ?></td>
                                <td><?= $this->Html->link(h($setting->name), ['action' => 'view', $setting->id]) ?></td>
                                <td><?= h($setting->value) ?></td>
                                <td><?= h($setting->created) ?></td>
                                <td><?= h($setting->updated) ?></td>
                                <td class="actions text-right">
                                    <?= $this->Html->link('<i class="nav-icon i-Folder-Open-2 font-weight-bold"></i>', ['action' => 'view', $setting->id], ['class'=>'btn btn-sm text-success mr-1', 'escape'=>false]) ?>
                                    <?= $this->Html->link('<i class="nav-icon i-Pen-2 font-weight-bold"></i>', ['action' => 'edit', $setting->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>
                                    <?= $this->Form->postLink('<i class="nav-icon i-Close-Window font-weight-bold"></i>', ['action' => 'delete', $setting->id], ['class'=>'btn btn-sm text-mute', 'escape'=>false, 'confirm' => __('Are you sure you want to delete # {0}?', $setting->id)]) ?>
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
                        <nav aria-label="<?= __('Settings') ?> navigation">
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


