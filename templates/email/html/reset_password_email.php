<?php
    use App\View\AppView;
    use Cake\Datasource\EntityInterface;

    /**
     * @var AppView         $this
     * @var EntityInterface $user
     */

    $this->assign('emailPreview', __('ACTION has been taken!'));
?>
<table align="center" class="container" style="margin: 0 auto; background: #fefefe; border: none; border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: inherit; vertical-align: top; width: 600px">
    <tbody>
        <tr style="padding: 0; text-align: left; vertical-align: top">
            <td style="-moz-hyphens: auto; -webkit-hyphens: auto; margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: normal; hyphens: auto; line-height: 1.5; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word">
                <h5>Hi <?= $user->name ?>,</h5>

                <h6>A password reset for your account has been requested.</h6>

                <p>Please go to https://actionsa.app/rp and enter <strong><?= $user->password_reset ?></strong> to change your password or simply visit http://actionsa.app/system/admin/users/reset-password/<?= $user->password_reset ?>.</p>

                <p>If you did not request this ACTION, please notify us.</p>
            </td>
        </tr>
    </tbody>
</table>
