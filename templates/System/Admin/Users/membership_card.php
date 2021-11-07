<?php
    /**
     * @var \App\View\AppView      $this
     * @var \App\Model\Entity\User $user
     */

    $this->assign('title', $user->name);
?>

<?php $this->start('page_options'); ?>
<div class="dropdown dropleft ml-auto">
    <button type="button" class="btn bg-transparent _r_btn border-0 " id="asaPageOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
        <span class="_dot _r_block-dot bg-dark"></span>
    </button>
    <div class="dropdown-menu" aria-labelledby="asaPageOptions">
        <?= $this->Html->link(__('Edit Details'), ['action' => 'edit', $user->id],
            ['class' => 'dropdown-item']) ?>
        <?= $this->Html->link(__('Access Credentials'), ['action' => 'accessDetails', $user->id],
            ['class' => 'dropdown-item']) ?>
        <?php if (4 === $this->Identity->get('role_id') && 2 & $user->user_status): ?>
            <?= $this->Html->link(__('View Leads'), ['action' => 'index'], ['class' => 'dropdown-item']) ?>
        <?php elseif (in_array($this->Identity->get('role_id'), [1, 2, 3]) && 2 & $user->user_status): ?>
            <?= $this->Html->link(__('View Leads'), ['action' => 'index', '?' =>['volunteer' =>$user->id]], ['class' => 'dropdown-item']) ?>
        <?php endif; ?>
        <div class="dropdown-divider"></div>
        <a href="javascript:window.print()" class="dropdown-item">Print Membership Card</a>
        <?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
            <div class="dropdown-divider"></div>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                [
                    'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
                    'class'   => 'dropdown-item text-mute',
                ]
            ) ?>
        <?php endif; ?>
    </div>
</div>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-4" id="print-area">
        <?= $this->element('membership_card') ?>
    </div>
</div>

