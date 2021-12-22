<?php /**
 * @global $args
 */
?>
<div class="wea-wrap">
    <h1>Редактировать наценку</h1>
    <div id="message" class="notice notice-success" data-bind="visible: notice">
        <div class="wea-notice-content" data-bind="html: notice"></div>
    </div>
    <div id="message" class="notice notice-error" data-bind="visible: errors">
        <p data-bind="text: errors"></p>
    </div>
    <form action="" class="wea-form" data-bind="submit: registerClick">
        <div class="wea-form__field">
            <label for="product_cat">Выберите категорию</label>
            <select name="product_cat" id="product_cat" data-bind="value: productCat">
                <option value="">Категория товаров</option>
				<?php foreach ( $args['cats'] as $cat ): ?>
                    <option value="<?= $cat->slug ?>"><?= $cat->name ?></option>
				<?php endforeach; ?>
            </select>
            <div class="wea-error" data-bind="visible: !productCatIsValid() && !isFirst()">Укажите категорию!</div>
        </div>
        <div class="wea-form__field">
            <label for="extra">Наценка (%)</label>
            <input type="number" id="extra" name="extra" data-bind="value: extra">
            <div class="wea-error" data-bind="visible: !extraIsValid() && !isFirst()">Укажите корректную наценку!</div>
        </div>
        <div class="wea-form__field">
            <div class="weo-submit-wrap" data-bind="class: loadingStatus">
                <img class="wea-loader" src="/wp-admin/images/wpspin_light.gif" alt="">
                <input type="submit"
                       class="button button-primary button-large"
                       value="Сохранить">
            </div>
        </div>
    </form>
    <p class="weo-description">
        <span class="weo-help-tip"></span> Изменить наценку категории на всех субдомены
    </p>
</div>
