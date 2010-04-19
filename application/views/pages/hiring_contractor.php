
<p><a href="index.php">Home</a></p>
<h2>
  <?php echo html::image('media/img/contractor.png'); ?>Contractor New Hire Request
</h2>
<form method="post" action="" id="newHireForm" accept-charset="utf-8">

  <table>

    <tr>
      <td align="right" id="hire_type_label"><strong>Hire type:</strong></td>
      <td>
        Contractor
      </td>
    </tr>
    <tr class="">
      <td align="right" id="contract_type_label"><strong>New/Extension:</strong></td>
      <td>
          <?php echo form::radio('contract_type', 'New', $this->input->post('contract_type')=='New'); ?>New contract
          <?php echo form::radio('contract_type', 'Extention', $this->input->post('contract_type')=='Extention'); ?>Extension of existing contract
          <?php client::validation('contract_type'); ?>
      </td>
    </tr>
    <tr class="">
      <td align="right" id="contract_category_label"><strong>Category:</strong></td>
      <td>
          <?php echo form::radio('contract_category', 'Independent', $this->input->post('contract_category')=='Independent'); ?>Independent
          <?php echo form::radio('contract_category', 'Corp to Corp', $this->input->post('contract_category')=='Corp to Corp'); ?>Corp to Corp
          <?php client::validation('contract_category'); ?>

      </td>
    </tr>
    <tr id="first_name_row">
      <td align="right" id="first_name_label"><strong>First name:</strong></td>
      <td>
        <?php echo form::input('first_name', $form['first_name'], 'size="20"'); ?>
        <?php client::validation('first_name'); ?>
      </td>
    </tr>

    <tr id="last_name_row">
      <td align="right" id="last_name_label"><strong>Last name:</strong></td>
      <td>
        <?php echo form::input('last_name', $form['last_name'], 'size="20"'); ?>
        <?php client::validation('last_name'); ?>
      </td>
    </tr>
    <tr id="org_name_row" class="">
      <td align="right" id="org_name_label"><strong>Organization name:</strong></td>
      <td>
          <?php echo form::input('org_name', $form['org_name'], 'size="30"'); ?>
          <?php client::validation('org_name'); ?>
      </td>
    </tr>
    <tr class="">
      <td align="right" id="address_label"><strong>Address:</strong></td>
      <td>
          <?php echo form::input('address', $form['address'], 'size="30"'); ?>
          <?php client::validation('address'); ?>
      </td>
    </tr>
    <tr class="">
      <td align="right" id="phone_number_label"><strong>Phone Number:</strong></td>
      <td>
          <?php echo form::input('phone_number', $form['phone_number'], 'size="30"'); ?>
          <?php client::validation('phone_number'); ?>
      </td>
    </tr>
    <tr class="">
      <td align="right" id="email_address_label"><strong>Current e-mail address:</strong></td>
      <td>
          <?php echo form::input('email_address', $form['email_address'], 'size="30"'); ?>
          <?php client::validation('email_address'); ?>
      </td>
    </tr>


    <tr>
      <td align="right" id="start_date_label"><strong>Start date:</strong></td>
      <td>
        <?php echo form::input('start_date', $form['start_date'], 'size="10"'); ?>
        <?php client::validation('start_date'); ?>
      </td>
    </tr>

    <tr class="">
      <td align="right" id="end_date_label"><strong>End date:</strong></td>
      <td>
        <?php echo form::input('end_date', $form['end_date'], 'size="10"'); ?>
        <?php client::validation('end_date'); ?>
      </td>
    </tr>

    <tr class="">
      <td align="right" id="rate_of_pay_label"><strong>Rate of pay:</strong></td>
      <td>
           <?php echo form::input('pay_rate', $form['pay_rate'], 'size="10"'); ?>
        <?php client::validation('pay_rate'); ?>

      </td>
    </tr>
    <tr class="">
      <td align="right" id="payment_limit_label"><strong>Total payment limitation:</strong></td>
      <td>
          <?php echo form::input('payment_limit', $form['payment_limit'], 'size="10"'); ?>
        <?php client::validation('payment_limit'); ?>
      </td>
    </tr>

    <tr>
      <td align="right" id="manager_label"><strong>Manager:</strong></td>
      <td>
        <?php echo form::dropdown('manager',$lists['manager'],$form['manager']); ?>
        <?php client::validation('manager'); ?>
      </td>
    </tr>

    <tr>
      <td align="right" id="location_label"><strong>Location:</strong></td>
      <td>
        <?php echo form::dropdown('location',$lists['location'],$form['location']); ?>
        <?php client::validation('location'); ?>
      </td>
    </tr>
    <tr id="other_location_tr" style="display: none;">
      <td align="right" id="other_location_label"></td>
      <td>
          <?php echo form::input('location_other', $form['location_other'], 'size="20"'); ?>
            <?php client::validation('location_other'); ?>
      </td>
    </tr>

    <tr class="">
      <td valign="top" align="right" id="statement_of_work_label"><strong>Statement of Work:</strong>
      <p><?php client::validation('statement_of_work'); ?></p></td>
      <td>
          <?php echo form::textarea('statement_of_work', $form['statement_of_work'],'rows="8" cols="60"'); ?>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table>
    <tr>
      <td align="right">
          <?php echo form::checkbox('mail_needed', '1',$this->input->post('mail_needed')==1);?>
      </td>
      <td colspan="3" id="bug_needed_label">
        <strong>Will this user need a mail account?</strong>
      </td>
    </tr>

    <tbody id="mail_box" style="display: none;">
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">
        <i>User accounts are created in the form of &lt;first letter of first
          name&gt;&lt;full last name&gt; (example: John Doe would be "jdoe").</i>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td align="right" valign="top" width="200">
        <strong>Default username:</strong>
      </td>
      <td>
        <input type="text" id="default_username" size="20" disabled />
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">
        <i>Mailing aliases are <strong>optional</strong>! Only fill this out if
          you want a username in ADDITION to the default.</i>
      </td>

    </tr>
      <tr>
        <td>&nbsp;</td>
	<td align="right" valign="top">
      <strong><label for="mail_alias">Mailing Alias:</label></strong>
    </td>
        <td valign="top">
            <?php echo form::input('mail_alias', $form['mail_alias'], 'size="20"'); ?>
          <?php client::validation('mail_alias'); ?>
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>

      <td colspan="2">
        <i>Besides "all" and any location-based lists, are there any mailing
          lists should this user be a member of? (optional)</i>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="right">
        <strong><label for="mail_lists">Mailing Lists:</label></strong>
      </td>
      <td>
          <?php echo form::input('mail_lists', $form['mail_lists'], 'size="30"'); ?>
        <?php client::validation('mail_lists'); ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
        <td valign="top" align="right">
          <strong><label for="other_comments">Other comments:</label></strong><br />
          <?php client::validation('other_comments'); ?>
        </td>
        <td>
            <?php echo form::textarea('other_comments', $form['other_comments'],'rows="5" cols="40"'); ?>
        </td>
    </tr>

    </tbody>
  </table>


  <br>
  <input type="submit" id="submit" name="submit" value="Submit Request" />
</form>

<p>Thanks for contacting us.
  You will be notified by email of any progress made in resolving your
  request.
</p>