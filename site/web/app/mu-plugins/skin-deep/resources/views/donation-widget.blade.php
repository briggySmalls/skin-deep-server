<div class="jumbotron">
  <h1 class="display-4">Donation</h1>
  <form action="" novalidate>
    <div class="form-row">
    @php
    $col_width = 'col';
    @endphp
      <div class="form-group {{ $col_width }}">
        @php
        $amount_input_id = uniqid('amount-');
        @endphp
        <label for="{{ $amount_input_id }}">Amount you wish to donate (in GBP)</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">£</span>
          </div>
          <input class="form-control donate-amount"
              id="{{ $amount_input_id }}"
              type="number"
              min="0.01"
              step="0.01"
              value="{{ $price }}"
              required
            >
          <div class="invalid-feedback">
            Please supply a valid amount
          </div>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="{{ $col_width }}">
        <button class="snipcart-add-item buy-button donate-button"
            type="submit"
            data-item-id="{{ $id }}"
            data-item-name="{{ $title }}"
            data-item-price="{{ $price }}"
            data-item-url="{{ $url }}"
            data-item-description="{{ $description}}"
            data-item-stackable="false"
            data-item-shippable="false"
          >
            Donate
        </button>
      </div>
    </div>
  </form>
</div>
