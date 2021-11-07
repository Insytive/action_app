<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Province $province
 */

$this->assign('title', __('Add Province'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-75 float-left card-title m-0"><?= __('Add Province') ?></h3>
                <div class="dropdown dropleft text-right w-25 float-right">
                    <button class="btn bg-gray-100" id="asaCtrlOpts" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon i-Gear-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="asaCtrlOpts">
                        <?= $this->Html->link(__('List Provinces'), ['action' => 'index'], ['class' => 'dropdown-item']) ?>
                    </div>
                </div>
            </div>

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
