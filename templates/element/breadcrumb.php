<?php
    /**
     * @var \App\View\AppView $this
     * @var array $breadcrumb Links to this page set on the controller
     */
?>
<ul>
    <li><a href="/system/admin/">Dashboard</a></li>
<?php
    if (isset($breadcrumb) && is_array($breadcrumb) && !empty($breadcrumb)):
        foreach ($breadcrumb as $key => $crumb):
?>
    <li <?= (!is_array($crumb)) ? 'style="color:#ababab;"' : '' ?>><?= (!is_array($crumb)) ? $crumb : $this->Html->link($key, $crumb['link']); ?></li>
<?php
        endforeach;
    endif;
?>
    <li style="color:#ababab;"><?= $this->fetch('title') ?></li>
</ul>
