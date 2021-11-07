<?php
    /**
     * @var \App\View\AppView      $this
     * @var \App\Model\Entity\User $user
     * @var bool $actions
     */

    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;

    $actions = (isset($actions)) ? $actions : true;
?>
<div class="card card-profile-1 mb-4" style="background-color: #05b615;">
    <div class="card-header border-0">
        <?php if (true === $actions): ?>
        <div class="dropdown float-right position-absolute d-print-none" style="right:0px; top: 0px;">
            <button type="button" class="btn bg-transparent _r_btn border-0 " id="asaMemberCard" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="_dot _r_block-dot bg-dark"></span>
                <span class="_dot _r_block-dot bg-dark"></span>
                <span class="_dot _r_block-dot bg-dark"></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="asaMemberCard">
                <a href="javascript:window.print()" class="dropdown-item"><i class="i-Download"></i> Print Membership Card</a>
            </div>
        </div>
        <?php endif; ?>

        <img src="/images/logo_front.png" alt="ActionSA">
        <h5 class="text-white text-uppercase text-center">Membership Card</h5>
    </div>
    <div class="card-body text-center bg-white pb-5">
        <h5 class="m-0"><?= h($user->name) ?></h5>
        <p><?= h($user->membership_number) ?></p>
        <p><small>JOINED: <?= h($user->created_at->toDateString()) ?></small></p>
    </div>
    <div class="card-footer user-profile" style="border-top: 3px solid #3053a3;">
        <div class="user-info" style="margin-top: -60px;">
            <?php
                $data = 'Name: ' . $user->name . PHP_EOL;
                $data .= '#: ' . $user->membership_number . PHP_EOL;
                $data .= 'Since: ' . $user->created_at->toDateString();

                $options = new QROptions([
                    'version'      => 7,
                    'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'     => QRCode::ECC_L,
                    'scale'        => 5,
                    'imageBase64'  => false,
                    'moduleValues' => [
                        // finder
                        1536 => [0, 63, 255],
                        // dark (true)
                        6    => [255, 255, 255],
                        // light (false), white is the transparency color and is enabled by default
                        // alignment
                        2560 => [255, 0, 255],
                        10   => [255, 255, 255],
                        // timing
                        3072 => [255, 0, 0],
                        12   => [255, 255, 255],
                        // format
                        3584 => [67, 191, 84],
                        14   => [255, 255, 255],
                        // version
                        4096 => [62, 174, 190],
                        16   => [255, 255, 255],
                        // data
                        1024 => [0, 0, 0],
                        4    => [255, 255, 255],
                        // darkmodule
                        512  => [0, 0, 0],
                        // separator
                        8    => [255, 255, 255],
                        // quietzone
                        18   => [255, 255, 255],
                    ],
                ]);
            ?>
            <div class="box-shadow-2 mb-3 bg-white">
                <img src="<?= (new QRCode)->render($data) ?>" alt="ASA QR Code" style="width: 200px;"/>
            </div>
        </div>
    </div>
</div>
