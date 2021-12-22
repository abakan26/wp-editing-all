<?php
/**
 * @global $args
 */

?>
<div class="wea-wrap">
    <form action="" class="wea-form" data-bind="submit: weaSynchronize">
        <div class="wea-form__field">
            <label for="domain">Домен, из которого брать аттрибуты</label>
            <select name="domain" id="domain"
                    data-bind="value: domain, enable: !loadingStatus()">
                <option value="">Выберите домен</option>
				<?php foreach ( $args['sites'] as $site ): ?>
                    <option value="<?= $site->domain ?>"><?= $site->domain ?></option>
				<?php endforeach; ?>
            </select>
        </div>
        <div class="wea-form__field">
            <div class="weo-submit-wrap" data-bind="class: loadingClass">
                <img class="wea-loader" src="/wp-admin/images/wpspin_light.gif" alt="">
                <button type="submit"
                        data-bind="enable: domain() != '' && !loadingStatus()"
                        class="button button-primary button-large">
                    Синхронизировать аттрибуты
                </button>
            </div>
        </div>
    </form>
    <div data-bind="visible: results().length" style="border: 1px solid;margin-top: 8px;    max-width: 400px;">
        <div data-bind="foreach: results">
            <p>
                <a data-bind="attr: { href: url }">
                    <span class="dashicons dashicons-saved" style="color: #7ad03a"></span> <b data-bind="text: site_domain"></b>
                </a> синхронизация прошла успешно
            </p>
        </div>
    </div>

    <div style="text-align: center;">
        <img src="/wp-admin/images/wpspin_light.gif" alt="" data-bind="visible: loadingAttr">
        <div id="attributes" data-bind="foreach: attributes" class="attributes-tb">
            <div class="attributes-tb__item">
                <p data-bind="text: attribute.attribute_label" class="attributes-tb__th"></p>
                <ol data-bind="foreach: terms" class="attributes-tb__terms">
                    <li data-bind="text: name" class="attributes-tb__term"></li>
                </ol>
            </div>
        </div>
    </div>
</div>
