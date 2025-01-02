<?php $this->load->view("partial/header"); ?>

<?php
if (isset($error)) {
    echo "<div class='alert alert-dismissible alert-danger'>" . $error . "</div>";
}

if (!empty($warning)) {
    echo "<div class='alert alert-dismissible alert-warning'>" . $warning . "</div>";
}

if (isset($success)) {
    echo "<div class='alert alert-dismissible alert-success'>" . $success . "</div>";
}
?>

<div id="register_wrapper">

    <!-- Top register controls -->

    <?php echo form_open($controller_name . "/change_mode", array('id' => 'mode_form', 'class' => 'form-horizontal panel panel-default')); ?>
    <div class="panel-body form-group">
        <ul>
            <li class="pull-left first_li">
                <label class="control-label"><?php echo $this->lang->line('sales_mode'); ?></label>
            </li>
            <li class="pull-left">
                <?php echo form_dropdown('mode', $modes, $mode, array('onchange' => "$('#mode_form').submit();", 'class' => 'selectpicker show-menu-arrow', 'data-style' => 'btn-default btn-sm', 'data-width' => 'fit')); ?>
            </li>
            <?php
            if ($this->config->item('dinner_table_enable') == TRUE) {
                ?>
                <li class="pull-left first_li">
                    <label class="control-label"><?php echo $this->lang->line('sales_table'); ?></label>
                </li>
                <li class="pull-left">
                    <?php echo form_dropdown('dinner_table', $empty_tables, $selected_table, array('onchange' => "$('#mode_form').submit();", 'class' => 'selectpicker show-menu-arrow', 'data-style' => 'btn-default btn-sm', 'data-width' => 'fit')); ?>
                </li>
                <?php
            }
            if (count($stock_locations) > 1) {
                ?>
                <li class="pull-left">
                    <label class="control-label"><?php echo $this->lang->line('sales_stock_location'); ?></label>
                </li>
                <li class="pull-left">
                    <?php echo form_dropdown('stock_location', $stock_locations, $stock_location, array('onchange' => "$('#mode_form').submit();", 'class' => 'selectpicker show-menu-arrow', 'data-style' => 'btn-default btn-sm', 'data-width' => 'fit')); ?>
                </li>
                <?php
            }
            ?>

            <li class="pull-right">
                <button class='btn btn-default btn-sm modal-dlg' id='show_suspended_sales_button'
                        data-href="<?php echo site_url($controller_name . "/suspended"); ?>"
                        title="<?php echo $this->lang->line('sales_suspended_sales'); ?>">
                    <span class="glyphicon glyphicon-align-justify">&nbsp</span><?php echo $this->lang->line('sales_suspended_sales'); ?>
                </button>
            </li>

            <?php
            if ($this->Employee->has_grant('reports_sales', $this->session->userdata('person_id'))) {
                ?>
                <li class="pull-right">
                    <?php echo anchor($controller_name . "/manage", '<span class="glyphicon glyphicon-list-alt">&nbsp</span>' . $this->lang->line('sales_takings'),
                        array('class' => 'btn btn-primary btn-sm', 'id' => 'sales_takings_button', 'title' => $this->lang->line('sales_takings'))); ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php echo form_close(); ?>

    <?php $tabindex = 0; ?>

    <?php echo form_open($controller_name . "/add", array('id' => 'add_item_form', 'class' => 'form-horizontal panel panel-default')); ?>
    <div class="panel-body form-group">
        <ul>
            <li class="pull-left first_li">
                <label for="item"
                       class='control-label'><?php echo $this->lang->line('sales_find_or_scan_item_or_receipt'); ?></label>
            </li>
            <li class="pull-left">
                <?php echo form_input(array('name' => 'item', 'id' => 'item', 'class' => 'form-control input-sm', 'size' => '50', 'tabindex' => ++$tabindex)); ?>
                <span class="ui-helper-hidden-accessible" role="status"></span>
            </li>
            <li class="pull-right">
                <button id='new_item_button' class='btn btn-info btn-sm pull-right modal-dlg'
                        data-btn-new="<?php echo $this->lang->line('common_new') ?>"
                        data-btn-submit="<?php echo $this->lang->line('common_submit') ?>"
                        data-href="<?php echo site_url("items/view"); ?>"
                        title="<?php echo $this->lang->line($controller_name . '_new_item'); ?>">
                    <span class="glyphicon glyphicon-tag">&nbsp</span><?php echo $this->lang->line($controller_name . '_new_item'); ?>
                </button>
            </li>
        </ul>
    </div>
    <?php echo form_close(); ?>


    <!-- Sale Items List -->

    <table class="sales_table_100" id="register">
        <thead>
        <tr>
            <th style="width: 5%;"><?php echo $this->lang->line('common_delete'); ?></th>
            <th style="width: 15%;"><?php echo $this->lang->line('sales_item_number'); ?></th>
            <th style="width: 30%;"><?php echo $this->lang->line('sales_item_name'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('sales_price'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
            <th style="width: 15%;"><?php echo $this->lang->line('sales_discount'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('sales_total'); ?></th>
            <th style="width: 5%;"><?php echo $this->lang->line('sales_update'); ?></th>
        </tr>
        </thead>

        <tbody id="cart_contents">
        <?php
        if (count($cart) == 0) {
            ?>
            <tr>
                <td colspan='8'>
                    <div class='alert alert-dismissible alert-info'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
                </td>
            </tr>
            <?php
        } else {
            foreach (array_reverse($cart, TRUE) as $line => $item) {
                ?>

                <?php echo form_open($controller_name . "/edit_item/$line", array('class' => 'form-horizontal', 'id' => 'cart_' . $line)); ?>
                <tr>
                    <td>
                        <?php echo anchor($controller_name . "/delete_item/$line", '<span class="glyphicon glyphicon-trash"></span>'); ?>
                        <?php echo form_hidden('location', $item['item_location']); ?>
                        <?php echo form_input(array('type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id'])); ?>
                    </td>
                    <?php
                    if ($item['item_type'] == ITEM_TEMP) {
                        ?>
                        <td><?php echo form_input(array('name' => 'item_number', 'id' => 'item_number', 'class' => 'form-control input-sm', 'value' => $item['item_number'], 'tabindex' => ++$tabindex)); ?></td>
                        <td style="align: center;">
                            <?php echo form_input(array('name' => 'name', 'id' => 'name', 'class' => 'form-control input-sm', 'value' => $item['name'], 'tabindex' => ++$tabindex)); ?>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td><?php echo $item['item_number']; ?></td>
                        <td style="align: center;">
                            <?php echo $item['name'] . ' ' . implode(' ', array($item['attribute_values'], $item['attribute_dtvalues'])); ?>
                            <br/>
                            <?php if ($item['stock_type'] == '0'): echo '[' . to_quantity_decimals($item['in_stock']) . ' in ' . $item['stock_name'] . ']'; endif; ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($items_module_allowed && $change_price) {
                        ?>
                        <td><?php echo form_input(array('name' => 'price', 'class' => 'form-control input-sm', 'value' => to_currency_no_money($item['price']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();')); ?></td>
                        <?php
                    } else {
                        ?>
                        <td>
                            <?php echo to_currency($item['price']); ?>
                            <?php echo form_hidden('price', to_currency_no_money($item['price'])); ?>
                        </td>
                        <?php
                    }
                    ?>

                    <td>
                        <?php
                        if ($item['is_serialized'] == 1) {
                            echo to_quantity_decimals($item['quantity']);
                            echo form_hidden('quantity', $item['quantity']);
                        } else {
                            echo form_input(array('name' => 'quantity', 'class' => 'form-control input-sm', 'value' => to_quantity_decimals($item['quantity']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();'));
                        }
                        ?>
                    </td>

                    <td>
                        <div class="input-group">
                            <?php echo form_input(array('name' => 'discount', 'class' => 'form-control input-sm', 'value' => $item['discount'], 'tabindex' => ++$tabindex, 'onClick' => 'this.select();')); ?>
                            <span class="input-group-btn">
										<?php echo form_checkbox(array('id' => 'discount_toggle', 'name' => 'discount_toggle', 'value' => 1, 'data-toggle' => "toggle", 'data-size' => 'small', 'data-onstyle' => 'success', 'data-on' => '<b>' . $this->config->item('currency_symbol') . '</b>', 'data-off' => '<b>%</b>', 'data-line' => $line, 'checked' => $item['discount_type'])); ?>
									</span>
                        </div>
                    </td>

                    <td>
                        <?php
                        if ($item['item_type'] == ITEM_AMOUNT_ENTRY) {
                            echo form_input(array('name' => 'discounted_total', 'class' => 'form-control input-sm', 'value' => to_currency_no_money($item['discounted_total']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();'));
                        } else {
                            echo to_currency($item['discounted_total']);
                        }
                        ?>
                    </td>

                    <td><a href="javascript:document.getElementById('<?php echo 'cart_' . $line ?>').submit();"
                           title=<?php echo $this->lang->line('sales_update') ?>><span
                                    class="glyphicon glyphicon-refresh"></span></a></td>
                </tr>
                <tr>
                    <?php
                    if ($item['item_type'] == ITEM_TEMP) {
                        ?>
                        <td><?php echo form_input(array('type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id'])); ?></td>
                        <td style="align: center;" colspan="6">
                            <?php echo form_input(array('name' => 'item_description', 'id' => 'item_description', 'class' => 'form-control input-sm', 'value' => $item['description'], 'tabindex' => ++$tabindex)); ?>
                        </td>
                        <td></td>
                        <?php
                    } else {
                        ?>
                        <td></td>
                        <?php
                        if ($item['allow_alt_description'] == 1) {
                            ?>
                            <td style="color: #2F4F4F;"><?php echo $this->lang->line('sales_description_abbrv'); ?></td>
                            <?php
                        }
                        ?>

                        <td colspan='2' style="text-align: left;">
                            <?php
                            if ($item['allow_alt_description'] == 1) {
                                echo form_input(array('name' => 'description', 'class' => 'form-control input-sm', 'value' => $item['description'], 'onClick' => 'this.select();'));
                            } else {
                                if ($item['description'] != '') {
                                    echo $item['description'];
                                    echo form_hidden('description', $item['description']);
                                } else {
                                    echo $this->lang->line('sales_no_description');
                                    echo form_hidden('description', '');
                                }
                            }
                            ?>
                        </td>
                        <td>&nbsp;</td>
                        <td style="color: #2F4F4F;">
                            <?php
                            if ($item['is_serialized'] == 1) {
                                echo $this->lang->line('sales_serial');
                            }
                            ?>
                        </td>
                        <td colspan='4' style="text-align: left;">
                            <?php
                            if ($item['is_serialized'] == 1) {
                                echo form_input(array('name' => 'serialnumber', 'class' => 'form-control input-sm', 'value' => $item['serialnumber'], 'onClick' => 'this.select();'));
                            } else {
                                echo form_hidden('serialnumber', '');
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>

                </tr>
                <?php echo form_close(); ?>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Overall Sale -->

<div id="overall_sale" class="panel panel-default">
    <div class="panel-body">
        <?php echo form_open($controller_name . "/select_customer", array('id' => 'select_customer_form', 'class' => 'form-horizontal')); ?>
        <?php
        if (isset($customer)) {
            ?>
            <table class="sales_table_100">
                <tr>
                    <th style='width: 55%;'><?php echo $this->lang->line("sales_customer"); ?></th>
                    <th style="width: 45%; text-align: right;"><?php echo anchor('customers/view/' . $customer_id, $customer, array('class' => 'modal-dlg', 'data-btn-submit' => $this->lang->line('common_submit'), 'title' => $this->lang->line('customers_update'))); ?></th>
                </tr>
                <?php
                if (!empty($customer_email)) {
                    ?>
                    <tr>
                        <th style='width: 55%;'><?php echo $this->lang->line("sales_customer_email"); ?></th>
                        <th style="width: 45%; text-align: right;"><?php echo $customer_email; ?></th>
                    </tr>
                    <?php
                }
                ?>
                <?php
                if (!empty($customer_address)) {
                    ?>
                    <tr>
                        <th style='width: 55%;'><?php echo $this->lang->line("sales_customer_address"); ?></th>
                        <th style="width: 45%; text-align: right;"><?php echo $customer_address; ?></th>
                    </tr>
                    <?php
                }
                ?>
                <?php
                if (!empty($customer_location)) {
                    ?>
                    <tr>
                        <th style='width: 55%;'><?php echo $this->lang->line("sales_customer_location"); ?></th>
                        <th style="width: 45%; text-align: right;"><?php echo $customer_location; ?></th>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <th style='width: 55%;'><?php echo $this->lang->line("sales_customer_discount"); ?></th>
                    <th style="width: 45%; text-align: right;"><?php echo ($customer_discount_type == FIXED) ? to_currency($customer_discount) : $customer_discount . '%'; ?></th>
                </tr>
                <?php if ($this->config->item('customer_reward_enable') == TRUE): ?>
                    <?php
                    if (!empty($customer_rewards)) {
                        ?>
                        <tr>
                            <th style='width: 55%;'><?php echo $this->lang->line("rewards_package"); ?></th>
                            <th style="width: 45%; text-align: right;"><?php echo $customer_rewards['package_name']; ?></th>
                        </tr>
                        <tr>
                            <th style='width: 55%;'><?php echo $this->lang->line("customers_available_points"); ?></th>
                            <th style="width: 45%; text-align: right;"><?php echo $customer_rewards['points']; ?></th>
                        </tr>
                        <?php
                    }
                    ?>
                <?php endif; ?>
                <tr>
                    <th style='width: 55%;'><?php echo $this->lang->line("sales_customer_total"); ?></th>
                    <th style="width: 45%; text-align: right;"><?php echo to_currency($customer_total); ?></th>
                </tr>
                <?php
                if (!empty($mailchimp_info)) {
                    ?>
                    <tr>
                        <th style='width: 55%;'><?php echo $this->lang->line("sales_customer_mailchimp_status"); ?></th>
                        <th style="width: 45%; text-align: right;"><?php echo $mailchimp_info['status']; ?></th>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <?php echo anchor($controller_name . "/remove_customer", '<span class="glyphicon glyphicon-remove">&nbsp</span>' . $this->lang->line('common_remove') . ' ' . $this->lang->line('customers_customer'),
                array('class' => 'btn btn-danger btn-sm', 'id' => 'remove_customer_button', 'title' => $this->lang->line('common_remove') . ' ' . $this->lang->line('customers_customer'))); ?>
            <?php
        } else {
            ?>
            <div class="form-group" id="select_customer">
                <label id="customer_label" for="customer" class="control-label"
                       style="margin-bottom: 1em; margin-top: -1em;"><?php echo $this->lang->line('sales_select_customer') . ' ' . $customer_required; ?></label>
                <?php echo form_input(array('name' => 'customer', 'id' => 'customer', 'class' => 'form-control input-sm', 'value' => $this->lang->line('sales_start_typing_customer_name'))); ?>

                <button class='btn btn-info btn-sm modal-dlg'
                        data-btn-submit="<?php echo $this->lang->line('common_submit') ?>"
                        data-href="<?php echo site_url("customers/view"); ?>"
                        title="<?php echo $this->lang->line($controller_name . '_new_customer'); ?>">
                    <span class="glyphicon glyphicon-user">&nbsp</span><?php echo $this->lang->line($controller_name . '_new_customer'); ?>
                </button>

            </div>
            <?php
        }
        ?>
        <?php echo form_close(); ?>

        <table class="sales_table_100" id="sale_totals">
            <tr>
                <th style="width: 55%;"><?php echo $this->lang->line('sales_quantity_of_items', $item_count); ?></th>
                <th style="width: 45%; text-align: right;"><?php echo $total_units; ?></th>
            </tr>
            <tr>
                <th style="width: 55%;"><?php echo $this->lang->line('sales_sub_total'); ?></th>
                <th style="width: 45%; text-align: right;"><?php echo to_currency($subtotal); ?></th>
            </tr>

            <?php
            foreach ($taxes as $tax_group_index => $tax) {
                ?>
                <tr>
                    <th style='width: 55%;'><?php echo (float)$tax['tax_rate'] . '% ' . $tax['tax_group']; ?></th>
                    <th style="width: 45%; text-align: right;"><?php echo to_currency_tax($tax['sale_tax_amount']); ?></th>
                </tr>
                <?php
            }
            ?>

            <tr>
                <th style='width: 55%;'><?php echo $this->lang->line('sales_total'); ?></th>
                <th style="width: 45%; text-align: right;"><span
                            id="sale_total"><?php echo to_currency($total); ?></span></th>
            </tr>
        </table>

        <?php
        // Only show this part if there are Items already in the sale.
        if (count($cart) > 0) {
            ?>
            <table class="sales_table_100" id="payment_totals">
                <tr>
                    <th style="width: 55%;"><?php echo $this->lang->line('sales_payments_total'); ?></th>
                    <th style="width: 45%; text-align: right;"><?php echo to_currency($payments_total); ?></th>
                </tr>
                <tr>
                    <th style="width: 55%;"><?php echo $this->lang->line('sales_amount_due'); ?></th>
                    <th style="width: 45%; text-align: right;"><span
                                id="sale_amount_due"><?php echo to_currency($amount_due); ?></span></th>
                </tr>
            </table>

            <div id="payment_details">
                <?php
                // Show Complete sale button instead of Add Payment if there is no amount due left
                if ($payments_cover_total) {
                    ?>
                    <?php echo form_open($controller_name . "/add_payment", array('id' => 'add_payment_form', 'class' => 'form-horizontal')); ?>
                    <table class="sales_table_100">
                        <tr>
                            <td><?php echo $this->lang->line('sales_payment'); ?></td>
                            <td>
                                <?php echo form_dropdown('payment_type', $payment_options, $selected_payment_type, array('id' => 'payment_types', 'class' => 'selectpicker show-menu-arrow', 'data-style' => 'btn-default btn-sm', 'data-width' => 'auto', 'disabled' => 'disabled')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered'); ?></span>
                            </td>
                            <td>
                                <?php echo form_input(array('name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'form-control input-sm disabled', 'disabled' => 'disabled', 'value' => '0', 'size' => '5', 'tabindex' => ++$tabindex, 'onClick' => 'this.select();')); ?>
                            </td>
                        </tr>
                    </table>

                    <?php echo form_close(); ?>
                    <?php
                    $payment_type = $this->input->post('payment_type');
                    // Only show this part if the payment cover the total and in sale or return mode

                    if ($pos_mode == '1' && $payment_type != $this->lang->line('sales_due') && !isset($customer)) {
                        ?>
                        <div class='btn btn-sm btn-success pull-right' id='finish_sale_button'
                             tabindex="<?php echo ++$tabindex; ?>"><span
                                    class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('sales_complete_sale'); ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if ($pos_mode == '1' && $payment_type = $this->lang->line('sales_due') && isset($customer)) {
                        ?>
                        <div class='btn btn-sm btn-success pull-right' id='finish_sale_button'
                             tabindex="<?php echo ++$tabindex; ?>"><span
                                    class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('sales_complete_sale'); ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                } else {
                    ?>
                    <?php echo form_open($controller_name . "/add_payment", array('id' => 'add_payment_form', 'class' => 'form-horizontal')); ?>
                    <table class="sales_table_100">
                        <tr>
                            <td><?php echo $this->lang->line('sales_payment'); ?></td>
                            <td>
                                <?php echo form_dropdown('payment_type', $payment_options, $selected_payment_type, array('id' => 'payment_types', 'class' => 'selectpicker show-menu-arrow', 'data-style' => 'btn-default btn-sm', 'data-width' => 'fit')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered'); ?></span>
                            </td>
                            <td>
                                <?php echo form_input(array('name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'form-control input-sm non-giftcard-input', 'value' => to_currency_no_money($amount_due), 'size' => '5', 'tabindex' => ++$tabindex, 'onClick' => 'this.select();')); ?>
                                <?php echo form_input(array('name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'form-control input-sm giftcard-input', 'disabled' => true, 'value' => to_currency_no_money($amount_due), 'size' => '5', 'tabindex' => ++$tabindex)); ?>
                            </td>
                        </tr>
                    </table>
                    <input id="mpesa-transaction-id" name="mpesa_transaction_id" type="hidden">

                    <?php echo form_close(); ?>

                    <div class='btn btn-sm btn-primary pull-right d-none' id='listen-for-mpesa-payment'
                         tabindex="<?php echo ++$tabindex ?>" style="display: none">
                        <span class="glyphicon glyphicon-earphone">&nbsp;</span>
                        <?php echo $this->lang->line('sales_listen_mpesa'); ?>
                    </div>

                    <div class='btn btn-sm btn-success pull-right' id='add_payment_button'
                         tabindex="<?php echo ++$tabindex; ?>"><span
                                class="glyphicon glyphicon-credit-card">&nbsp</span><?php echo $this->lang->line('sales_add_payment'); ?>
                    </div>
                    <?php
                }
                ?>

                <?php
                // Only show this part if there is at least one payment entered.
                if (count($payments) > 0) {
                    ?>
                    <table class="sales_table_100" id="register">
                        <thead>
                        <tr>
                            <th style="width: 10%;"><?php echo $this->lang->line('common_delete'); ?></th>
                            <th style="width: 60%;"><?php echo $this->lang->line('sales_payment_type'); ?></th>
                            <th style="width: 20%;"><?php echo $this->lang->line('sales_payment_amount'); ?></th>
                        </tr>
                        </thead>

                        <tbody id="payment_contents">
                        <?php
                        foreach ($payments as $payment_id => $payment) {
                            ?>
                            <tr>
                                <td><?php echo anchor($controller_name . "/delete_payment/$payment_id", '<span class="glyphicon glyphicon-trash"></span>'); ?></td>
                                <td><?php echo $payment['payment_type']; ?></td>
                                <td style="text-align: right;"><?php echo to_currency($payment['payment_amount']); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>

            <?php echo form_open($controller_name . "/cancel", array('id' => 'buttons_form')); ?>
            <div class="form-group" id="buttons_sale">
                <div class='btn btn-sm btn-default pull-left' id='suspend_sale_button'><span
                            class="glyphicon glyphicon-align-justify">&nbsp</span><?php echo $this->lang->line('sales_suspend_sale'); ?>
                </div>
                <?php
                // Only show this part if the payment covers the total
                if (!$pos_mode && isset($customer)) {
                    ?>
                    <div class='btn btn-sm btn-success' id='finish_invoice_quote_button'><span
                                class="glyphicon glyphicon-ok">&nbsp</span><?php echo $mode_label; ?></div>
                    <?php
                }
                ?>

                <div class='btn btn-sm btn-danger pull-right' id='cancel_sale_button'><span
                            class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('sales_cancel_sale'); ?>
                </div>
            </div>
            <?php echo form_close(); ?>

            <?php
            // Only show this part if the payment cover the total
            if ($payments_cover_total || !$pos_mode) {
                ?>
                <div class="container-fluid">
                    <div class="no-gutter row">
                        <div class="form-group form-group-sm">
                            <div class="col-xs-12">
                                <?php echo form_label($this->lang->line('common_comments'), 'comments', array('class' => 'control-label', 'id' => 'comment_label', 'for' => 'comment')); ?>
                                <?php echo form_textarea(array('name' => 'comment', 'id' => 'comment', 'class' => 'form-control input-sm', 'value' => $comment, 'rows' => '2')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group form-group-sm">
                            <div class="col-xs-6">
                                <label for="sales_print_after_sale" class="control-label checkbox">
                                    <?php echo form_checkbox(array('name' => 'sales_print_after_sale', 'id' => 'sales_print_after_sale', 'value' => 1, 'checked' => $print_after_sale)); ?>
                                    <?php echo $this->lang->line('sales_print_after_sale') ?>
                                </label>
                            </div>

                            <?php
                            if (!empty($customer_email)) {
                                ?>
                                <div class="col-xs-6">
                                    <label for="email_receipt" class="control-label checkbox">
                                        <?php echo form_checkbox(array('name' => 'email_receipt', 'id' => 'email_receipt', 'value' => 1, 'checked' => $email_receipt)); ?>
                                        <?php echo $this->lang->line('sales_email_receipt'); ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                            <?php
                            if ($mode == "sale_work_order") {
                                ?>
                                <div class="col-xs-6">
                                    <label for="price_work_orders" class="control-label checkbox">
                                        <?php echo form_checkbox(array('name' => 'price_work_orders', 'id' => 'price_work_orders', 'value' => 1, 'checked' => $price_work_orders)); ?>
                                        <?php echo $this->lang->line('sales_include_prices'); ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if (($mode == "sale") && $this->config->item('invoice_enable') == TRUE) {
                        ?>
                        <div class="row">
                            <div class="form-group form-group-sm">
                                <div class="col-xs-6">
                                    <label for="sales_invoice_enable" class="control-label checkbox">
                                        <?php echo form_checkbox(array('name' => 'sales_invoice_enable', 'id' => 'sales_invoice_enable', 'value' => 1, 'checked' => $invoice_number_enabled)); ?>
                                        <?php echo $this->lang->line('sales_invoice_enable'); ?>
                                    </label>
                                </div>

                                <div class="col-xs-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon input-sm">#</span>
                                        <?php echo form_input(array('name' => 'sales_invoice_number', 'id' => 'sales_invoice_number', 'class' => 'form-control input-sm', 'value' => $invoice_number)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <?php
        }
        ?>
    </div>
</div>

<div id="mpesa-payments-modal" class="modal bootstrap-dialog modal-dlg-wide type-primary fade size-normal in" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="bootstrap-dialog-header">
                    <div class="bootstrap-dialog-close-button" data-dismiss="modal" >
                        <span class="close" aria-label="Close" aria-hidden="true">&times;</span>
                    </div>
                    <h4 class="bootstrap-dialog-title">Mpesa Payments</h4>
                </div>
            </div>
            <div class="modal-body">
                <!--<div class="search-bar" style="display: flex; justify-content: space-between;
                 border-bottom: 1px solid #ececec; padding-bottom: 10px;">
                    <div>
                        <div style="display: flex;">
                            <div style="margin-right: 5px; margin-top: auto; margin-bottom: auto">
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-primary" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                        <span id="mpesa-payment-search-parameter">Amount</span>
                                        <span class="caret"></span>
                                    </a>
                                    <ul id="mpesa-payment-search-parameters" class="dropdown-menu" aria-labelledby="dLabel">
                                        <li><a id="amount" href="#">Amount</a></li>
                                        <li><a id="transaction-id" href="#">Transaction ID</a></li>
                                        <li><a id="phone-number" href="#">Phone Number</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group form-group-sm" style="margin-bottom: 0; margin-right: 10px">
                                <input id="mpesa-payment-search-query" class="form-control">
                            </div>
                            <div id="search-mpesa-payment" class="form-group form-group-sm" style="margin-bottom: 0">
                                <button class="btn btn-primary form-control">Listen</button>
                            </div>
                        </div>

                        <div id="mpesa-search-query-validation-error"
                             style="position: relative; left: 68px; display: none; color: red"></div>
                    </div>

                    <img src="http://ospos.local/images/spinner.gif" alt="Loading..." width="35px" style="align-self: flex-start; object-fit: contain">
                </div>-->

                <div class="search-bar" style="display: flex; justify-content: space-between;
                 border-bottom: 1px solid #ececec; padding-bottom: 10px;">
                    <div>
                        <div style="display: grid; grid-auto-columns: max-content">
                            <div style="margin-right: 5px; margin-top: auto; margin-bottom: auto; grid-column: 1; grid-row: 1">
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-primary" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                        <span id="mpesa-payment-search-parameter">Amount</span>
                                        <span class="caret"></span>
                                    </a>
                                    <ul id="mpesa-payment-search-parameters" class="dropdown-menu" aria-labelledby="dLabel">
                                        <li><a id="amount" href="#">Amount</a></li>
                                        <li><a id="transaction-id" href="#">Transaction ID</a></li>
                                        <li><a id="phone-number" href="#">Phone Number</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group form-group-sm" style="margin-bottom: 0; margin-right: 10px; grid-column: 2; grid-row: 1">
                                <input id="mpesa-payment-search-query" class="form-control">
                            </div>
                            <div id="search-mpesa-payment" class="form-group form-group-sm" style="margin-bottom: 0; grid-column: 3; grid-row: 1">
                                <button class="btn btn-primary form-control">Listen</button>
                            </div>

                            <div id="mpesa-search-query-validation-error" style="color: red; grid-column: 2 / span 2; grid-row: 2">
                            </div>
                        </div>
                    </div>

                    <img src="http://ospos.local/images/spinner.gif" alt="Loading..." width="35px" style="align-self: flex-start; object-fit: contain">
                </div>

                <div style="margin-top: 20px; min-height: 40px">
                    <ul id="mpesa-payments-container" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("input[name='item_number']").change(function () {
            var item_id = $(this).parents("tr").find("input[name='item_id']").val();
            var item_number = $(this).val();
            $.ajax({
                url: "<?php echo site_url('sales/change_item_number');?>",
                method: 'post',
                data: {
                    "item_id": item_id,
                    "item_number": item_number,
                },
                dataType: 'json'
            });
        });

        $("input[name='name']").change(function () {
            var item_id = $(this).parents("tr").find("input[name='item_id']").val();
            var item_name = $(this).val();
            $.ajax({
                url: "<?php echo site_url('sales/change_item_name');?>",
                method: 'post',
                data: {
                    "item_id": item_id,
                    "item_name": item_name,
                },
                dataType: 'json'
            });
        });

        $("input[name='item_description']").change(function () {
            var item_id = $(this).parents("tr").find("input[name='item_id']").val();
            var item_description = $(this).val();
            $.ajax({
                url: "<?php echo site_url('sales/change_item_description');?>",
                method: 'post',
                data: {
                    "item_id": item_id,
                    "item_description": item_description,
                },
                dataType: 'json'
            });
        });

        $('#item').focus();

        $('#item').blur(function () {
            $(this).val("<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
        });

        $('#item').autocomplete(
            {
                source: "<?php echo site_url($controller_name . '/item_search'); ?>",
                minChars: 0,
                autoFocus: false,
                delay: 500,
                select: function (a, ui) {
                    $(this).val(ui.item.value);
                    $("#add_item_form").submit();
                    return false;
                }
            });

        $('#item').keypress(function (e) {
            if (e.which == 13) {
                $('#add_item_form').submit();
                return false;
            }
        });

        var clear_fields = function () {
            if ($(this).val().match("<?php echo $this->lang->line('sales_start_typing_item_name') . '|' . $this->lang->line('sales_start_typing_customer_name'); ?>")) {
                $(this).val('');
            }
        };

        $('#item, #customer').click(clear_fields).dblclick(function (event) {
            $(this).autocomplete("search");
        });

        $('#customer').blur(function () {
            $(this).val("<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
        });

        $("#customer").autocomplete(
            {
                source: "<?php echo site_url("customers/suggest"); ?>",
                minChars: 0,
                delay: 10,
                select: function (a, ui) {
                    $(this).val(ui.item.value);
                    $("#select_customer_form").submit();
                }
            });

        $('#customer').keypress(function (e) {
            if (e.which == 13) {
                $('#select_customer_form').submit();
                return false;
            }
        });

        $(".giftcard-input").autocomplete(
            {
                source: "<?php echo site_url("giftcards/suggest"); ?>",
                minChars: 0,
                delay: 10,
                select: function (a, ui) {
                    $(this).val(ui.item.value);
                    $("#add_payment_form").submit();
                }
            });

        $('#comment').keyup(function () {
            $.post("<?php echo site_url($controller_name . "/set_comment");?>", {comment: $('#comment').val()});
        });

        <?php
        if($this->config->item('invoice_enable') == TRUE)
        {
        ?>
        $('#sales_invoice_number').keyup(function () {
            $.post("<?php echo site_url($controller_name . "/set_invoice_number");?>", {sales_invoice_number: $('#sales_invoice_number').val()});
        });

        var enable_invoice_number = function () {
            var enabled = $("#sales_invoice_enable").is(":checked");
            $("#sales_invoice_number").prop("disabled", !enabled).parents('tr').show();
            return enabled;
        }

        enable_invoice_number();

        $("#sales_invoice_enable").change(function () {
            var enabled = enable_invoice_number();
            $.post("<?php echo site_url($controller_name . "/set_invoice_number_enabled");?>", {sales_invoice_number_enabled: enabled});
        });
        <?php
        }
        ?>

        $("#sales_print_after_sale").change(function () {
            $.post("<?php echo site_url($controller_name . "/set_print_after_sale");?>", {sales_print_after_sale: $(this).is(":checked")});
        });

        $("#price_work_orders").change(function () {
            $.post("<?php echo site_url($controller_name . "/set_price_work_orders");?>", {price_work_orders: $(this).is(":checked")});
        });

        $('#email_receipt').change(function () {
            $.post("<?php echo site_url($controller_name . "/set_email_receipt");?>", {email_receipt: $(this).is(":checked")});
        });

        $("#finish_sale_button").click(function () {
            $('#buttons_form').attr('action', "<?php echo site_url($controller_name . "/complete"); ?>");
            $('#buttons_form').submit();
        });

        $("#finish_invoice_quote_button").click(function () {
            $('#buttons_form').attr('action', "<?php echo site_url($controller_name . "/complete"); ?>");
            $('#buttons_form').submit();
        });

        $("#suspend_sale_button").click(function () {
            $('#buttons_form').attr('action', "<?php echo site_url($controller_name . "/suspend"); ?>");
            $('#buttons_form').submit();
        });

        $("#cancel_sale_button").click(function () {
            if (confirm("<?php echo $this->lang->line("sales_confirm_cancel_sale"); ?>")) {
                $('#buttons_form').attr('action', "<?php echo site_url($controller_name . "/cancel"); ?>");
                $('#buttons_form').submit();
            }
        });

        $("#add_payment_button").click(function () {
            $('#add_payment_form').submit();
        });

        $("#payment_types").change(check_payment_type).ready(check_payment_type);

        $("#cart_contents input").keypress(function (event) {
            if (event.which == 13) {
                $(this).parents("tr").prevAll("form:first").submit();
            }
        });

        $("#amount_tendered").keypress(function (event) {
            if (event.which == 13) {
                $('#add_payment_form').submit();
            }
        });

        $("#finish_sale_button").keypress(function (event) {
            if (event.which == 13) {
                $('#finish_sale_form').submit();
            }
        });

        dialog_support.init("a.modal-dlg, button.modal-dlg");

        table_support.handle_submit = function (resource, response, stay_open) {
            $.notify(response.message, {type: response.success ? 'success' : 'danger'});

            if (response.success) {
                if (resource.match(/customers$/)) {
                    $("#customer").val(response.id);
                    $("#select_customer_form").submit();
                } else {
                    var $stock_location = $("select[name='stock_location']").val();
                    $("#item_location").val($stock_location);
                    $("#item").val(response.id);
                    if (stay_open) {
                        $("#add_item_form").ajaxSubmit();
                    } else {
                        $("#add_item_form").submit();
                    }
                }
            }
        }

        $('[name="price"],[name="quantity"],[name="discount"],[name="description"],[name="serialnumber"],[name="discounted_total"]').change(function () {
            $(this).parents("tr").prevAll("form:first").submit()
        });

        $('[name="discount_toggle"]').change(function () {
            var input = $("<input>").attr("type", "hidden").attr("name", "discount_type").val(($(this).prop('checked')) ? 1 : 0);
            $('#cart_' + $(this).attr('data-line')).append($(input));
            $('#cart_' + $(this).attr('data-line')).submit();
        });
    });

    function check_payment_type() {
        var cash_rounding = <?php echo json_encode($cash_rounding); ?>;

        if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_giftcard'); ?>") {
            $("#sale_total").html("<?php echo to_currency($total); ?>");
            $("#sale_amount_due").html("<?php echo to_currency($amount_due); ?>");
            $("#amount_tendered_label").html("<?php echo $this->lang->line('sales_giftcard_number'); ?>");
            $("#amount_tendered:enabled").val('').focus();
            $(".giftcard-input").attr('disabled', false);
            $(".non-giftcard-input").attr('disabled', true);
            $(".giftcard-input:enabled").val('').focus();
        } else if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_cash'); ?>" && cash_rounding) {
            $("#sale_total").html("<?php echo to_currency($cash_total); ?>");
            $("#sale_amount_due").html("<?php echo to_currency($cash_amount_due); ?>");
            $("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");
            $("#amount_tendered:enabled").val("<?php echo to_currency_no_money($cash_amount_due); ?>");
            $(".giftcard-input").attr('disabled', true);
            $(".non-giftcard-input").attr('disabled', false);
        } else {
            $("#sale_total").html("<?php echo to_currency($non_cash_total); ?>");
            $("#sale_amount_due").html("<?php echo to_currency($non_cash_amount_due); ?>");
            $("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");
            $("#amount_tendered:enabled").val("<?php echo to_currency_no_money($non_cash_amount_due); ?>");
            $(".giftcard-input").attr('disabled', true);
            $(".non-giftcard-input").attr('disabled', false);
        }

        if ($('#payment_types').val() === "<?php echo $this->lang->line('sales_mpesa'); ?>") {
            $('#listen-for-mpesa-payment').css('display', 'block');
            $('#add_payment_button').css('display', 'none');
            $('#mpesa-payments-modal').modal();
        } else {
            $('#listen-for-mpesa-payment').css('display', 'none');
            $('#add_payment_button').css('display', 'block');
        }
    }

    $('#listen-for-mpesa-payment').on('click', () => {
        $('#mpesa-payments-modal').modal();
    });


    /**
     * Mpesa modal setup
     */

    // On modal start-up
    $('#mpesa-payments-modal').on('show.bs.modal', function (e) {
        const amountTendered = '<?php echo $non_cash_amount_due ?>';
        $(this).find('#mpesa-payment-search-query').val(amountTendered);
        $(this).find('#mpesa-payment-search-parameter').text('Amount');
        searchForMpesaPayment('amount', amountTendered);
    });

    // On modal close
    $('#mpesa-payments-modal').on('hide.bs.modal', e => {
        stopOngoingSearchForMpesaPayment();
    });

    // Mpesa payment query options setup
    $('#mpesa-payment-search-parameters > li > a').on('click', e => {
        const searchParam = $(e.currentTarget).text();
        $('#mpesa-payment-search-parameter').text(searchParam);

        e.preventDefault();
    });

    const SearchParam = {
        AMOUNT: 'amount',
        TRANSACTION_ID: 'transaction_id',
        PHONE_NUMBER: 'phone_number',
    };

    $('#search-mpesa-payment').on('click', e => {
        const searchParam = mapTextToSearchParam($('#mpesa-payment-search-parameter').text());
        const searchQuery = $('#mpesa-payment-search-query').val();
        validateSearchQuery({searchParam, searchQuery,
            onValidated: () => searchForMpesaPayment(searchParam, searchQuery)});
    });

    const $error = $('#mpesa-search-query-validation-error');

    function validateSearchQuery(props = {}) {
        hideSearchQueryError();

        const {searchParam, searchQuery, onValidated} = props;
        let isValid = true;

        if (searchParam === SearchParam.AMOUNT) {
            isValid = !isNaN(searchQuery);
        } else if (searchParam === SearchParam.PHONE_NUMBER) {
            isValid = checkPhoneNumberValidity(searchQuery);
        } else if (searchParam === SearchParam.TRANSACTION_ID) {
            isValid = searchQuery.length === 10
        }

        if (isValid)
            onValidated(searchParam, searchQuery);
        else
            showSearchQueryError();

        function showSearchQueryError() {
            $error.text(`Invalid ${searchParam} provided`);
            $error.css('display', 'block');
        }

        function hideSearchQueryError() {
            $error.text('');
            $error.css('display', 'none');
        }
    }

    function checkPhoneNumberValidity(phoneNumber) {
        const charLength = phoneNumber.length;
        const firstChar = phoneNumber[0];

        console.log(charLength, firstChar);

        if (firstChar !== '0' && firstChar !== '2')
            return false;

        if (firstChar === '0' && charLength !== 10)
            return false;
        else if (firstChar === '2' && charLength !== 12)
            return false;

        return true;
    }

    function mapTextToSearchParam(text) {
        switch (text) {
            case 'Amount':
                return 'amount';
            case 'Transaction ID':
                return 'transaction_id';
            case 'Phone Number':
                return 'phone_number';
            default:
                return text.toLowerCase();
        }
    }

    let intervalID;
    const allPaymentsTransactionIDs = new Set();

    function searchForMpesaPayment(searchParam, searchQuery) {
        stopOngoingSearchForMpesaPayment();
        clearPreviousSearchResults();

        makeSearchRequest();
        intervalID = setInterval(makeSearchRequest, 5000);

        function makeSearchRequest() {
            $.ajax({
                url: "<?php echo site_url('mpesa/search_payments');?>",
                method: 'post',
                data: {
                    search_param: searchParam,
                    search_query: searchQuery,
                    csrf_ospos_v3: csrf_token()
                },
                dataType: 'json',
                success: data => {
                    if (data.success) appendPaymentsToModal(data.payments)
                }
            })
        }
    }

    function appendPaymentsToModal(receivedPayments) {
        for (const payment of receivedPayments) {
            if (! allPaymentsTransactionIDs.has(payment.transaction_id)) {
                allPaymentsTransactionIDs.add(payment.transaction_id);
                appendPaymentToModal(payment)
            }
        }
    }

    function appendPaymentToModal(payment) {
        $('#mpesa-payments-container').append(`
            <li class="list-group-item">
                <div>
                    <table class="mpesa-payment-details">
                        <tr>
                            <td>Transaction ID:</td>
                            <td>${payment.transaction_id}</td>
                        </tr>
                        <tr>
                            <td>Name:</td>
                            <td>${payment.payer_first_name} ${payment.payer_middle_name} ${payment.payer_last_name}</td>
                        </tr>
                        <tr>
                            <td>Amount:</td>
                            <td>Kshs. ${payment.amount}</td>
                        </tr>
                    </table>
                    <div style="display: flex; justify-content: flex-end;">
                        <div class='btn btn-sm btn-success add-mpesa-payment' data-transaction-id='${payment.transaction_id}'>
                            <span class="glyphicon glyphicon-credit-card">&nbsp</span>
                            <?php echo $this->lang->line('sales_add_payment'); ?>
                        </div>
                    </div>
                </div>
            </li>
        `);
    }

    function stopOngoingSearchForMpesaPayment() {
        if (!!intervalID)
            clearInterval(intervalID);
    }

    function clearPreviousSearchResults() {
        $('#mpesa-payments-container').html('');
        allPaymentsTransactionIDs.clear();
    }

    $(document).on('click', '.add-mpesa-payment', e => {
        // console.log($(e.currentTarget).data('transactionId'));
        $('#mpesa-transaction-id').val($(e.currentTarget).data('transactionId'));
        $('#add_payment_form').trigger('submit');
    });

</script>

<?php $this->load->view("partial/footer"); ?>
