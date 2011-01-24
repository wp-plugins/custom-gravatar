<?php
/*
  Plugin Name: Custom Gravatar
  Plugin URI: http://wordpress.org
  Description: Add widget with information from custom gravatar account
  Author: Vladimir Tsvang
  Version: 1.0
  Author URI: http://wordpress.org/extend/plugins/profile/vtsvang
 */

add_action('widgets_init', 'CustomGravatarRegister');

function CustomGravatarRegister() {
    return register_widget('CustomGravatar');
}

class CustomGravatar extends WP_Widget {

    function CustomGravatar() {
        parent::WP_Widget(
                        false,
                        'Custom Gravatar',
                        array(
                            'description' => 'Displays custom gravatar on you pages'
                        ),
                        array(
                            'width' => '250px',
                            'height' => '250px'
                        )
        );
    }

    function form($instance) {
        ob_start();
?>
        <div align="left">
            <strong><?php echo __('e-mail'); ?>: </strong>
            <input type="text" name="<?php echo $this->get_field_name('email') ?>" value="<?php echo $instance['email']; ?>" />
            <br />

            <strong><?php echo __('apply') . ' ' . __('style') . ' ' . __('from') . ' ' . __('gravatar'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('style') ?>" value="on" <?php if ($instance['style'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('name'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('name') ?>" value="on" <?php if ($instance['name'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('about me'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('aboutme') ?>" value="on" <?php if ($instance['aboutme'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('location'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('location') ?>" value="on" <?php if ($instance['location'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('ims'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('ims') ?>" value="on" <?php if ($instance['ims'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('accounts'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('accounts') ?>" value="on" <?php if ($instance['accounts'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('urls'); ?>? </strong>
            <input type="checkbox" name="<?php echo $this->get_field_name('urls') ?>" value="on" <?php if ($instance['urls'] == 'on') {
            echo 'checked="checked"';
        } ?> />
        <br />

        <strong><?php echo __('show') . ' ' . __('emails'); ?>? </strong>
        <input type="checkbox" name="<?php echo $this->get_field_name('emails') ?>" value="on" <?php if ($instance['emails'] == 'on') {
            echo 'checked="checked"';
        } ?> />
            <br />

            <strong><?php echo __('show') . ' ' . __('rels') . ' ' . __('nofollow'); ?>? </strong>
            <select name="<?php echo $this->get_field_name('rels') ?>">
                <option value="nofollow" <?php if ($instance['rels'] == 'nofollow') {
            echo 'selected="selceted"';
        } ?> >nofollow</option>
                <option value="follow" <?php if ($instance['rels'] == 'follow') {
            echo 'selected="selceted"';
        } ?> >none</option>
            </select>
            <br />

<?php if (!empty($instance)) {
 ?>
                <br />
                <img src="<?php echo 'http://www.gravatar.com/avatar/' . md5($instance['email']) . '?s=100'; ?>" alt="gravatar" />
<?php } ?>
        </div>
<?php
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }

    function update($new_instance, $old_instance) {
        $filename = 'http://gravatar.com/' . md5($new_instance['email']) . '.json';
        $result = file_get_contents($filename);
        if (!$result || $result == '"User not found"') {
            return $old_instance;
        } else {
            return $new_instance;
        }
    }

    function widget($args, $instance) {
        if (empty($instance['email'])) {
            return null;
        }
        if ($instance['name'] !== 'on' &&
                $instance['location'] !== 'on' &&
                $instance['aboutme'] !== 'on' &&
                $instance['ims'] !== 'on' &&
                $instance['accounts'] !== 'on' &&
                $instance['style'] !== 'on' &&
                $instance['urls'] !== 'on'
        ) {
            null;
        } else {
            $userdata = json_decode(@file_get_contents('http://gravatar.com/' . md5($instance['email']) . '.json'));
        }
        ob_start();
?>
    <div align="center" <?php if ($instance['style'] == 'on') { ?>style="background: url('<?php echo $userdata->entry[0]->profileBackground->url; ?>') <?php echo $userdata->entry[0]->profileBackground->color . ' ' . $userdata->entry[0]->profileBackground->repeat . ' ' . $userdata->entry[0]->profileBackground->position; ?>;"<?php } ?>>
                <?php if ($instance['name'] == 'on') {
 ?>
        <h3><?php echo $userdata->entry[0]->displayName; ?></h3>
<?php } ?>

    <a <?php if ($instance['rels'] == 'nofollow') {
                    echo 'rel="nofollow"';
                } ?> href="<?php echo $userdata->entry[0]->profileUrl; ?>" >
                    <img src="<?php echo 'http://www.gravatar.com/avatar/' . md5($instance['email']) . '?s=100'; ?>" alt="<?php echo $userdata->entry[0]->preferredUsername; ?>" /><br/>
                </a>

        <?php if ($instance['location'] == 'on') {
 ?>
                <div class="location" align="justify">
                    <p><i><?php echo $userdata->entry[0]->currentLocation; ?></i></p>
                    </div>
<?php } ?>

        <?php if ($instance['aboutme'] == 'on') {
 ?>
                <div class="aboutme" align="justify">
                    <blockquote>
                        <p>
<?php echo $userdata->entry[0]->aboutMe; ?>
                            </p>
                        </blockquote>
                    </div>
        <?php } ?>

        <?php if ($instance['ims'] == 'on') {
 ?>
                <div class="aims" align="left">
                    <h3><?php echo __('IMS'); ?></h3>
<?php foreach (@$userdata->entry[0]->ims as $value) {
 ?>
                                <p><strong><?php echo $value->type; ?>:</strong> <?php echo $value->value; ?></p>
<?php } ?>
                        </div>
<?php } ?>

<?php if ($instance['accounts'] == 'on') {
 ?>
                        <div class="accounts" align="left">
                            <h3><?php echo __('Accounts'); ?></h3>
<?php foreach (@$userdata->entry[0]->accounts as $value) { ?>
                                <p><strong><?php echo $value->shortname; ?>:</strong> <a <?php if ($instance['rels'] == 'nofollow') {
                            echo 'rel="nofollow"';
                        } ?> href="<?php echo $value->url; ?>"><?php if (!empty($value->username)) {
                            echo $value->username;
                        } else {
                            echo __('view');
                        } ?></a></p>
<?php } ?>
                        </div>
<?php } ?>

<?php if ($instance['urls'] == 'on') { ?>
                        <div class="urls" align="left">
                            <h3><?php echo __('URLs'); ?></h3>
<?php foreach (@$userdata->entry[0]->urls as $value) { ?>
                                <p><a <?php if ($instance['rels'] == 'nofollow') {
                            echo 'rel="nofollow"';
                        } ?> href="<?php echo $value->value; ?>"><?php echo $value->title; ?></a></p>
<?php } ?>
                        </div>
<?php } ?>
                </div>
<?php
                $content = ob_get_contents();
                ob_end_clean();
                echo $content;
            }

        }
?>
