<?php
/**
 * @var App\View\AppView $this View object
 * @var \App\Model\Entity\Area[]|\Cake\Collection\CollectionInterface $areas Voting stations
 * @var string $term The term that the user searched for
 */
?>
<?php
    if ($areas->count() > 0):
        foreach ($areas as $area):
?>
<a href="javascript:" class="list-group-item list-group-item-action px-2 py-1 asaVD" data-id="<?= $area->id ?>"><i class="i-Geo2-"></i><?= preg_replace('/'.$term.'/i', '<strong><mark class="p-0">$0</mark></strong>', $area->area_municipality) ?></a>
<?php
        endforeach;
    endif;
?>
