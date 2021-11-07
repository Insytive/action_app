<?php
/**
 * @var App\View\AppView $this View object
 * @var \App\Model\Entity\Municipality[]|\Cake\Collection\CollectionInterface $municipalities Voting stations
 * @var string $term The term that the user searched for
 */
?>
<?php
    if ($municipalities->count() > 0):
        foreach ($municipalities as $municipality):
?>
<a href="javascript:" class="list-group-item list-group-item-action px-2 py-1 asaMunOpt" data-id="<?= $municipality->id ?>"><i class="i-Geo2-"></i><?= preg_replace('/'.$term.'/i', '<strong><mark class="p-0">$0</mark></strong>', $municipality->name) ?></a>
<?php
        endforeach;
    endif;
?>
