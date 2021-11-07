<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VotingStation $votingStation
 * @var array $wards
 */

$this->assign('title', __('Add a Voting Station'));
?>

<?php $this->start('page_options'); ?>
<?= $this->Html->link(__('All Voting Stations'),
    ['action' => 'index'],
    ['class' => 'btn btn-secondary ml-auto']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
        <?= $this->Form->create($votingStation) ?>
            <div class="card-body">
                <div class="votingStations form row content">
                    <div class="col-md-6 form-group mb-3"><?= $this->Form->control('name'); ?></div>
                    <div class="col-md-3 form-group mb-3"><?= $this->Form->control('ward_id', ['options' => $wards]); ?></div>
                    <div class="col-md-3 form-group mb-3 mt-4"><?= $this->Form->control('approved', ['custom'=>true, 'label'=>'Approved']); ?></div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-secondary" type="reset">Reset</button>
                <button class="btn btn-primary ml-2" type="submit">Save</button>
            </div>
        <?= $this->Form->end() ?>
        </div>
    </div>
</div>
