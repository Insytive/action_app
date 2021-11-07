<?php
    use App\View\AppView;
    use Cake\Datasource\EntityInterface;

    /**
     * @var AppView         $this
     * @var EntityInterface $user
     */

    $this->assign('emailPreview', __('Thank you for taking ACTION!'));
?>
<table align="center" class="container" style="margin: 0 auto; background: #fefefe; border: none; border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: inherit; vertical-align: top; width: 600px">
    <tbody>
        <tr style="padding: 0; text-align: left; vertical-align: top">
            <td style="-moz-hyphens: auto; -webkit-hyphens: auto; margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: normal; hyphens: auto; line-height: 1.5; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word">
                <h5>Hi <?= $user->name ?>,</h5>

                <h6>Thank you for taking ACTION!</h6>

                <p>You have officially registered with ActionSA as a member.</p>

                <p>Your membership number is <?= $user->membership_number ?> which in future can be used as your login username, and also give you access to participate in the organization’s meetings and functions.</p>

                <p>Should you lose this email or wish to retrieve your membership number, please follow this link <a href="www.actionsa.app/myaction">www.actionsa.app/myaction</a>.</p>

                <p>Thank you for taking that first important step on the journey towards building a prosperous South Africa that is free from the hyenas and looters currently in government.</p>
            </td>
        </tr>
    </tbody>
</table>
