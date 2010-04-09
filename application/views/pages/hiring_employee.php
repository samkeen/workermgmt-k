
<p><a href="index.php">Home</a></p>
<h2>
    <?php echo html::image('media/img/employee.png'); ?>Employee/Intern New Hire Request
</h2>

<form method="post" action="" id="newHireForm" accept-charset="utf-8">

  <table>

    <tr>
      <td align="right" id="hire_type_label"><strong>Hire type:</strong></td>
      <td>
          <?php echo form::dropdown('hire_type',$lists['hire_types'],$form['hire_type']); ?>
        
        <?php client::validation('hire_type'); ?>
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

    <tr>
      <td align="right" id="start_date_label"><strong>Start date:</strong></td>
      <td>
          <?php echo form::input('start_date', $form['start_date'], 'size="10"'); ?>
        <?php client::validation('start_date'); ?>
      </td>
    </tr>

    <tr id="end_date_row">
      <td align="right" id="end_date_label"><strong>End date:</strong></td>
      <td>
          <?php echo form::input('end_date', $form['end_date'], 'size="10"'); ?>
        <?php client::validation('end_date'); ?>
      </td>
    </tr>

    <tr>
      <td align="right" id="manager_label"><strong>Manager:</strong></td>
      <td>
          <?php echo form::dropdown('manager',$lists['managers'],$form['manager']); ?>
        <?php client::validation('manager'); ?>
      </td>
    </tr>

    <tr>
      <td align="right" id="location_label"><strong>Location:</strong></td>
      <td>
         <?php echo form::dropdown('location',$lists['locations'],$form['location']); ?>
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
  <table>
    <tr>
      <td align="right">
          <?php echo form::checkbox('machine_needed', '1',$this->input->post('machine_needed')==1);?>
      </td>
      <td colspan="3"><strong>Will this user need a machine?</strong></td>
    </tr>

    <tbody id="machine_box" style="display: none;">

    <tr>
      <td>&nbsp;</td>
      <td valign="top" align="right" width="200">
        <strong><label for="machine_type">Type of machine needed:</label></strong>
      </td>
      <td>
          <?php echo form::dropdown('machine_type',$lists['machine_types'],$form['machine_type']); ?>
        
        <?php client::validation('machine_type'); ?>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>

      <td align="right" valign="top">
        <strong><label for="machine_special_requests">Special Requests:</label></strong><br/>
        (software/hardware/setup)<br />
        <?php client::validation('machine_special_requests'); ?>
      </td>
      <td>
          <?php echo form::textarea('machine_special_requests', $form['other_comments'],'rows="5" cols="40"'); ?>
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
