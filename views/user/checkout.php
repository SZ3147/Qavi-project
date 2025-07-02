<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>



<div class="container mt-5">
    <h2><?=Lang::get('Checkout')?></h2>
    <form method="POST" action="/QaviEcommerce/user/placeOrder" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <div class="card mb-4">
            <div class="card-header">
                <h5><?=Lang::get('Shipping_Information')?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label"><?=Lang::get('Full_Name')?></label>
                        <input type="text" id="name" name="name" class="form-control" required autocomplete="name"
                            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        <div class="invalid-feedback"><?=Lang::get('Please_enter_your_full_name.')?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label"><?=Lang::get('Phone_Number')?></label>
                        <input type="tel" id="phone" name="phone" class="form-control" required autocomplete="tel"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        <div class="invalid-feedback"><?=Lang::get('Please_enter_a_valid_phone_number.')?></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label"><?=Lang::get('Address')?></label>
                    <textarea id="address" name="address" class="form-control" required autocomplete="street-address"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    <div class="invalid-feedback"><?=Lang::get('Please_enter_your_shipping_address.')?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="city" class="form-label"><?=Lang::get('City')?></label>
                        <input type="text" id="city" name="city" class="form-control" required autocomplete="address-level2"
                            value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                        <div class="invalid-feedback"><?=Lang::get('Please_enter_your_city.')?></div>
                    </div>
                    <div class="col-md-4">
                        <label for="state" class="form-label"><?=Lang::get('State')?></label>
                        <input type="text" id="state" name="state" class="form-control" required autocomplete="address-level1"
                            value="<?= htmlspecialchars($_POST['state'] ?? '') ?>">
                        <div class="invalid-feedback"><?=Lang::get('Please_enter_your_state.')?></div>
                    </div>
                    <div class="col-md-4">
                        <label for="zip" class="form-label"><?=Lang::get('ZIPCode')?></label>
                        <input type="text" id="zip" name="zip" class="form-control" required autocomplete="postal-code"
                            value="<?= htmlspecialchars($_POST['zip'] ?? '') ?>">
                        <div class="invalid-feedback"><?=Lang::get('Please_enter_your_ZIP_code.')?></div>
                    </div>
                </div>
                <div class="mb-3">

                    <label for="payment_method" class="form-label"><?= Lang::get('PaymentMethod') ?></label>

                    <select id="payment_method" name="payment_method" class="form-select" required>
                <?php

                $methods = ['CashonDelivery', 'CreditCard', 'PayPal'];
                $selectedMethod = $_POST['payment_method'] ?? '';
                foreach ($methods as $method):

                    $translatedText = Lang::get($method);
                ?>
                <option value="<?= htmlspecialchars($method) ?>" <?= $selectedMethod === $method ? 'selected' : '' ?>>

                    <?= htmlspecialchars($translatedText) ?>
                </option>
                <?php endforeach; ?>

                 </select>
                    <div class="invalid-feedback"><?=Lang::get('Please_select_a_payment_method')?>.</div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success"><?=Lang::get('PlaceOrder')?></button>
    </form>
</div>

<script>
// Bootstrap 5 validation example
(() => {
    'use strict'
    const forms = document.querySelectorAll('form')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
