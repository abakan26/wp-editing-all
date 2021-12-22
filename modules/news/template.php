<?php
/**
 * @global $args
 */

?>
<div class="wea-wrap">
    <form action="" class="wea-form" data-bind="submit: weaSynchronize">
        <div class="wea-form__field">
            <div class="weo-submit-wrap" data-bind="class: loadingClass">
                <img class="wea-loader" src="/wp-admin/images/wpspin_light.gif" alt="">
                <button type="submit"
                        data-bind="enable: !loadingStatus()"
                        class="button button-primary button-large">
                    Синхронизировать новости
                </button>
            </div>
        </div>
    </form>
    <div data-bind="visible: results().length" style="border: 1px solid;margin-top: 8px;max-width: 400px;">
        <div data-bind="foreach: results">
            <p>
                <span class="dashicons dashicons-saved" style="color: #7ad03a"></span> <b data-bind="text: site_domain"></b>
            </p>
            <p>Обновлено: <span data-bind="text: update"></span></p>
            <p>Создано: <span data-bind="text: insert"></span></p>
        </div>
    </div>
</div>
