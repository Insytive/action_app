<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Province $province
 */

$this->assign('title', __('Edit Province'));
?>

<?php $this->start('page_options'); ?>
<?= $this->Html->link(__('All Provinces'),
    ['action' => 'index'],
    ['class' => 'btn btn-secondary ml-auto']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            <?= $this->Form->create($province) ?>
            <div class="card-body">
                <div class="provinces form content">
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('code');
                ?>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-secondary" type="reset" data-dismiss="modal">Reset</button>
                <button class="btn btn-primary ml-2" type="submit">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
