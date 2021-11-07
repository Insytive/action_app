<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 * @var mixed $role
 */

$provinces = [
    1 => 'Eastern Cape',
    2 => 'Free State',
    3 => 'Gauteng',
    9 => 'KwaZulu-Natal',
    4 => 'Limpopo',
    5 => 'Mpumalanga',
    6 => 'Northern Cape',
    7 => 'North West',
    8 => 'Western Cape'
];

$this->assign('title', __('Members'));
?>

<?php $this->start('page_options'); ?>
<div class="dropdown dropleft ml-auto">
    <button type="button" class="btn bg-transparent _r_btn border-0 " id="asaPageOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
    </button>
    <div class="dropdown-menu" aria-labelledby="asaPageOptions">
        <a href="javascript:" class="dropdown-item"><i class="i-Find-User"></i> Filter</a>
        <?php if (in_array($this->Identity->get('role_id'), [1, 2])): ?>
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header">Export</h6>
            <?= $this->Html->link('<i class="i-File-CSV"></i> CSV',
            ['action' => 'export', '?'=> $this->getRequest()->getQueryParams()],
            ['class' => 'dropdown-item', 'escape'=>false]) ?>
        <a href="javascript:" class="dropdown-item disabled"><i class="i-File-Text--Image"></i> PDF</a>
        <a href="javascript:window.print()" class="dropdown-item"><i class="i-Folder-With-Document"></i> Print</a>
        <?php endif; ?>

        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3, 5, 6]) ||
                  (4 === $this->Identity->get('role_id') && 2 & $this->Identity->get('user_status'))): ?>
            <div class="dropdown-divider"></div>
            <?= $this->Html->link('<i class="i-Add-User"></i> ' . __('New {0}', $role),
                ['action' => 'add'],
                ['class' => 'dropdown-item', 'escape'=>false]) ?>
        <?php endif; ?>
    </div>
</div>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header p-0">
                <div class="accordion" id="accordionRightIcon">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title ul-collapse__icon--size ul-collapse__right-icon mb-0">
                            <a class="text-default collapsed" data-toggle="collapse" href="#accordion-item-icons-1" aria-expanded="false">
                                <span><i class="i-Magnifi-Glass1 ul-accordion__font"> </i></span> Filter</a>
                        </h6>
                    </div>
                    <div class="collapse show" id="accordion-item-icons-1" data-parent="#accordionRightIcon">
                        <div class="card-body">
                            <?= $this->Form->create(null, ['type'=>'get', 'autocomplete'=>'off']) ?>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <?= $this->Form->control('p', [
                                        'class' => 'form-control-rounded',
                                        'empty' => 'Province Filter',
                                        'label' => false,
                                        'id'    => 'filterProv',
                                        'options' => $provinces
                                    ]); ?>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label class="switch pr-5 switch-success mr-3"><span>No Voting Station</span>
                                        <input type="checkbox" name="nvs" id="nvs" /><span class="slider"></span>
                                    </label>
                                    <label class="switch pr-5 switch-success mr-3"><span>No Phone Number</span>
                                        <input type="checkbox" name="npn" id="npn" /><span class="slider"></span>
                                    </label>
                                    <label class="switch pr-5 switch-success mr-3"><span>No Email</span>
                                        <input type="checkbox" name="nea" id="nea" /><span class="slider"></span>
                                    </label>
                                </div>
                                <div class="col-md-1 form-group text-right">
                                    <button class="btn btn-rounded btn-primary" id="filterGo" type="button">Search</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card-body pt-0 px-0">
            <?php if ($users->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th>&nbsp;</th>
                            <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
                                <th><?= $this->Paginator->sort('role_id') ?></th>
                                <th><?= $this->Paginator->sort('parent_id', 'Referrer') ?></th>
                            <?php endif; ?>
                            <th style="white-space: nowrap;">
                                <?= $this->Paginator->sort('first_name', 'Name') ?>
                                /
                                <?= $this->Paginator->sort('last_name', 'Surname') ?>
                            </th>
                            <th><?= $this->Paginator->sort('membership_number', 'Member#') ?></th>
                            <th><?= $this->Paginator->sort('email') ?></th>
                            <th><?= $this->Paginator->sort('created_at', 'Created') ?>/<?= $this->Paginator->sort('updated_at', 'Updated') ?></th>
                            <th class="actions text-right" style="min-width:90px;">&vellip;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><span class="badge-dot-<?= ($user->active_status) ? 'success' : 'danger'; ?>"></span></td>
                                <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
                                    <td><?= $user->has('role') ? $user->role->icon : '' ?></td>
                                    <td><?= $user->has('parent_user') ? $this->Html->link($user->parent_user->name, ['controller' => 'Users', 'action' => 'view', $user->parent_user->id]) : '' ?></td>
                                <?php endif; ?>
                                <td>
                                    <?= $this->Html->link(h($user->name), ['action' => 'view', $user->id]) ?>
                                </td>
                                <td><?= h($user->membership_number) ?></td>
                                <td><?= h($user->email) ?></td>
                                <td><small><?= h($user->created_at) ?>
                                    <?php if ($user->created_at != $user->updated_at): ?>
                                        <br>
                                        <?= h($user->updated_at) ?>
                                    <?php endif; ?>
                                    </small></td>
                                <td class="actions text-right">
                                    <?= $this->Html->link('<i class="nav-icon i-Pen-2 font-weight-bold"></i>', ['action' => 'edit', $user->id], ['class'=>'btn btn-sm mr-1', 'escape'=>false]) ?>
                                <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3, 4])): ?>
                                    <?= $this->Form->postLink('<i class="nav-icon i-Close-Window font-weight-bold"></i>', ['action' => 'delete', $user->id], ['class'=>'btn btn-sm text-mute', 'escape'=>false, 'confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            </div><!--/.card-body -->
            <div class="card-footer">
                <div class="paginator d-flex align-items-center">
                    <div class="w-75">
                        <nav aria-label="<?= __('Users') ?> navigation">
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

<?php $this->start('element_footer_scripts'); ?>
<script>
    jQuery(document).ready(function() {

        $("#filterGo").on('click', function (){
            let prov = $("#filterProv").val();
            let nvs  = $('#nvs').is(':checked');
            let npn  = $('#npn').is(':checked');
            let nea  = $('#nea').is(':checked');

            if (prov === '' && nvs === false && npn === false && nea === false){
                return;
            }

            url = '/system/admin/users/index/?';

            if (prov !== ''){
                url = url + 'p='+ prov +'&';
            }

            if (nvs !== false){
                url = url + 'nvs='+ 1 +'&';
            }

            if (npn !== false){
                url = url + 'npn='+ 1 +'&';
            }

            if (nea !== false){
                url = url + 'nea='+ 1;
            }

            window.location = url;
        });

    });
</script>
<?php $this->end(); ?>
