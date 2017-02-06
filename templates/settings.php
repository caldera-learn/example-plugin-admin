<?php
if( ! defined( 'ABSPATH' ) ){
    exit;
}

?>
<script type="text/html" id="settings-template">
    <div>
        <div id="report"></div>
        <form ng-submit="save()">
            <div class="form-group">
                <label for="api_key">
                    <?php esc_html_e( 'API Key', 'text-domain' ); ?>
                </label>
                <input type="text" id="api_key" ng-model="data.api_key" class="form-control" />
            </div>

            <div class="form-group">
                <label for="enabled">
                    <?php esc_html_e( 'Enabled', 'text-domain' ); ?>
                </label>
                <input type="checkbox" id="enabled" ng-model="data.enabled" ng-true-value="true" ng-false-value="false" />
            </div>

            <?php
                submit_button( 'Save' );
            ?>
        </form>

    </div>
</script>