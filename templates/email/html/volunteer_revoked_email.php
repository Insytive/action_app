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

                <h6>ACTION has been taken!</h6>

                <p>You have officially registered with ActionSA as a volunteer.</p>

                <p>Your profile status has been changed to review your account. You will no longer be able to login until further notice.</p>
            </td>
        </tr>
    </tbody>
</table>
