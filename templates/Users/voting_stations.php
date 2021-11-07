<?php
/**
 * @var App\View\AppView $this View object
 * @var \App\Model\Entity\VotingStation[]|\Cake\Collection\CollectionInterface $stations Voting stations
 * @var string $term The term that the user searched for
 */
?>
<?php
    if ($stations->count() > 0):
        foreach ($stations as $station):
?>
<a href="javascript:" class="list-group-item list-group-item-action px-2 py-1 asaVD" data-id="<?= $station->id ?>"><i class="i-Geo2-"></i><?= preg_replace('/'.$term.'/i', '<strong><mark class="p-0">$0</mark></strong>', $station->name) ?>, <small class="text-muted"><?= $station->Provinces['name'] ?>, <?= $station->Municipality['name'] ?>, <?= $station->Areas['name'] ?></small></a>
<?php
        endforeach;
    endif;
?>
